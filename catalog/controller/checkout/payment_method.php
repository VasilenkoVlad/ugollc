<?php
class ControllerCheckoutPaymentMethod extends Controller {
	public function index() {
		$this->load->language('checkout/checkout');

		if (isset($this->session->data['payment_address'])) {
			// Totals
			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array.
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);
			
			$this->load->model('extension/extension');

			$sort_order = array();

			$results = $this->model_extension_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);

					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}

			// Payment Methods
			$method_data = array();

			$this->load->model('extension/extension');

			$results = $this->model_extension_extension->getExtensions('payment');

			$recurring = $this->cart->hasRecurringProducts();

			foreach ($results as $result) {
                            
                            //Custom Added: To prevent cod while purchase credits
                            if(!empty($this->session->data['credits'])){
                                
                                if($result['code'] == "cod"){
                                    
                                    continue;
                                }
                            }
                            //End
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('extension/payment/' . $result['code']);

					$method = $this->{'model_extension_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);

					if ($method) {
						if ($recurring) {
							if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
								$method_data[$result['code']] = $method;
							}
						} else {
							$method_data[$result['code']] = $method;
						}
					}
				}
			}

			$sort_order = array();

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);

			$this->session->data['payment_methods'] = $method_data;
		}

		$data['text_payment_method'] = $this->language->get('text_payment_method');
		$data['text_comments'] = $this->language->get('text_comments');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_continue'] = $this->language->get('button_continue');

		if (empty($this->session->data['payment_methods'])) {
			$data['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['payment_methods'])) {
			$data['payment_methods'] = $this->session->data['payment_methods'];
		} else {
			$data['payment_methods'] = array();
		}

		if (isset($this->session->data['payment_method']['code'])) {
			$data['code'] = $this->session->data['payment_method']['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->session->data['comment'])) {
			$data['comment'] = $this->session->data['comment'];
		} else {
			$data['comment'] = '';
		} 
                
                if($this->customer->isLogged()) {
                    $cwid = $this->get_cwid();
                } else {
                    $cwid = NULL;
                }
                
                if($cwid != NULL) {
                   $data['payment_id'] = $cwid['cwid'];
                } else {
                   $data['payment_id'] = '';
                }

		$data['scripts'] = $this->document->getScripts();

		if ($this->config->get('config_checkout_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_checkout_id'), true), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}

		if (isset($this->session->data['agree'])) {
			$data['agree'] = $this->session->data['agree'];
		} else {
			$data['agree'] = '';
		}
               
		$this->response->setOutput($this->load->view('checkout/payment_method', $data));
	}

	public function save() {
		$this->load->language('checkout/checkout');

		$json = array();

		// Validate if payment address has been set.
		if (!isset($this->session->data['payment_address'])) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', true);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers']) && empty($this->session->data['credits'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');

				break;
			}
		}
		
                if (!isset($this->request->post['payment_method'])) {
			$json['error']['warning'] = $this->language->get('error_payment');
		} elseif ($this->request->post['payment_method'] != 'BAMA Cash' && $this->request->post['payment_method'] !='DD') {
                    if( !isset($this->session->data['payment_methods'][$this->request->post['payment_method']])){
                        $json['error']['warning'] = $this->language->get('error_payment');
                    }
                }
                
                //Custom added : To check if payment id is not there
                if  (($this->request->post['payment_method'] == 'BAMA Cash') && (!isset($this->request->post['payment_id']) || $this->request->post['payment_id'] == "") && ($this->request->post['order_type'] == "web")) {
                    $json['error']['warning'] = "Enter your " .$this->request->post['payment_method']. " unique id";
                }
                
                if (($this->request->post['payment_method'] == 'DD') && (!isset($this->request->post['payment_id']) || $this->request->post['payment_id'] == "") && ($this->request->post['order_type'] == "web")) {
                    $json['error']['warning'] = "Enter your Dining Dollars unique id";
                }
                
                //Custom added : To check if payment id is valid
                if  (isset($this->request->post['payment_id']) && $this->request->post['payment_id'] != "" && in_array($this->request->post['payment_method'],array('BAMA Cash','DD')) && $this->request->post['order_type'] == "web" && (strlen($this->request->post['payment_id']) != 8 && strlen($this->request->post['payment_id']) != 16 && strlen($this->request->post['payment_id']) != 19)) {

                    $json['error']['warning'] = "Enter valid CWID#";
                }
                
                //Save CWID
                if (isset($this->request->post['payment_id']) && $this->request->post['payment_id'] != "" && in_array($this->request->post['payment_method'],array('BAMA Cash','DD')) ){
                    
                    if($this->customer->isLogged()){
                        $this->save_cwid($this->request->post['payment_id']);
                    }
                    
                }
                
                //Custom added : Forbidden check
                if($this->request->post['payment_method'] == 'DD' && !isset($_SESSION['credits'])) {
                    $warning = $this->forbidden_check();
                    if ($warning) {
                        $json['error']['warning'] = $warning;
                    }
                }

		if ($this->config->get('config_checkout_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

			if ($information_info && !isset($this->request->post['agree'])) {
				$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}
                
                if(isset($this->request->post['api_call']) && $this->request->post['api_call'] == "1") {
                    $this->session->data['speedy_delivery'] = $this->request->post['speedy_delivery'];
                }
		
                if (!$json) {
                    if(($this->request->post['payment_method'] != 'BAMA Cash' && $this->request->post['payment_method'] !='DD')){
                        $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
                        $this->session->data['comment'] = strip_tags($this->request->post['comment']);
                    }else{
                        $this->session->data['payment_method'] = $this->request->post['payment_method'];
                        $this->session->data['comment'] = "CWID: ".$this->request->post['payment_id'].'<br/>'.strip_tags($this->request->post['comment']);
                    }

                    //Get delivery fee according to payment method and cart amount
                    if (isset($this->session->data['shipping_method']) && $this->session->data['shipping_method']['code'] == "flat.flat") {
                        $fee = $this->get_delivery_fee($this->request->post['payment_method']);
                        $delivery_fee = $fee['basic_fee'];
                        $speedy_delivery_fee = $fee['speedy_delivery_fee'];
                        $delivery_cost = $delivery_fee + $speedy_delivery_fee;
                        $this->session->data['shipping_method']['output_cost'] = $delivery_fee;
                        $this->session->data['shipping_method']['text'] = "$".$delivery_fee;
                        
                         if (isset($this->session->data['speedy_delivery']) && $this->session->data['speedy_delivery'] == "yes") {
                           $this->session->data['speedy_delivery_fee']['cost'] = $speedy_delivery_fee;
                           $this->session->data['speedy_delivery_fee']['text'] = $speedy_delivery_fee; 
                         }
                         
                    } else if(isset($this->session->data['shipping_method']) && $this->session->data['shipping_method']['code'] == "free.free" && (isset($this->session->data['speedy_delivery']) && $this->session->data['speedy_delivery'] == 'yes')) {
                        $speedy_delivery_fee = $this->get_max_speedy_delivery_fee($this->request->post['payment_method']);
                        $delivery_fee = $speedy_delivery_fee;
                        $delivery_cost = $speedy_delivery_fee;
                        $this->session->data['shipping_method']['output_cost'] = 0.00;
                        $this->session->data['shipping_method']['text'] = "$.$delivery_fee";
                        $this->session->data['speedy_delivery_fee']['cost'] = $speedy_delivery_fee;
                        $this->session->data['speedy_delivery_fee']['text'] = $speedy_delivery_fee;
                        $this->session->data['shipping_method']['tax_class_id'] = $this->config->get('flat_tax_class_id');
                    }
                    
                     
                    if(isset($delivery_cost)) {
                        $this->session->data['shipping_method']['cost'] = $delivery_cost;
                    } 
                    
                    //Get distance delivery fee
                    if(isset($this->session->data['shipping_method']) && $this->session->data['shipping_method']['code'] != "pickup.pickup" ) {
                        $distance_delivery_fee = $this->get_distance_delivery_fee($this->request->post['payment_method']);
                        if($distance_delivery_fee != null & $distance_delivery_fee > 0) {
                            $this->session->data['shipping_method']['cost'] += $distance_delivery_fee;
                            $this->session->data['distance_delivery_fee']['cost'] = $distance_delivery_fee;
                            $this->session->data['distance_delivery_fee']['text'] = $distance_delivery_fee;
                            $this->session->data['shipping_method']['tax_class_id'] = $this->config->get('flat_tax_class_id');
                        }else {
                            unset($this->session->data['distance_delivery_fee']);
                        }
                    }
                   
                }
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
        
        //Custom added : Function to forbidden check
        public function forbidden_check() {
		$products = $this->cart->getProducts();

                foreach($products as $pro) {
                    $product_ids[] = $pro['product_id'];
                }
                
                $this->load->model('catalog/product');
                $result = $this->model_catalog_product->checkForbidden($product_ids);
                if ($result) {
                    $result_cnt = count($result);
                    if($result_cnt == count($product_ids) || $this->request->get['newFlow'] == '') {
                        if ($result_cnt > 0 && $result_cnt < 2 ) {
                            $warning = "Sorry " .$result[0]['name']. " is not eligible for Dining Dollar Payment. Please select any other payment method.";
                        } else {
                            $warning ="Sorry ";

                            for($i = 0; $i < $result_cnt - 1; $i++) {
                                $warning .= $result[$i]['name'];
                                if($i < $result_cnt - 2 ) {
                                   $warning .=", "; 
                                } else {
                                    $warning .=" and "; 
                                }
                            }

                            $warning .= " ".$result[$result_cnt-1]['name']." ";
                            $warning .= " which are not eligible for Dining Dollar Payment. Please select any other payment method.";        

                        }
                    } elseif($this->request->get['newFlow'] == 1) {
                        
                        if ($result_cnt > 0 && $result_cnt < 2 ) {
                            
                            //$warning = "Your cart includes product " .$result[0]['name']. " is not permitted using payment type: Dining Dollars.  You can purchase this product using other payment types such as:  Gift cards, credit cards, or cash.";
                            $warning = "Your cart includes product which is not permitted using payment type: Dining Dollars.  You can purchase this product using other payment types such as Gift cards, Credit cards, or Cash.";

                        } else {
                            $warning ="Your cart includes products ";

                            /*for($i = 0; $i < $result_cnt - 1; $i++) {
                                $warning .= $result[$i]['name'];
                                if($i < $result_cnt - 2 ) {
                                   $warning .=", "; 
                                } else {
                                    $warning .=" and "; 
                                }
                            }*/

                            //$warning .= " ".$result[$result_cnt-1]['name']." ";
                            $warning .= "which are not permitted using payment type: Dining Dollars.  You can purchase these products using other payment types such as Gift cards, Credit cards, or Cash.";        
                            
                        }
                            foreach($result as $res) {
                                $forbidden_product_ids[] = $res['product_id'];
                            }
                            $i = 0;
                            foreach($products as $pro) {
                                if(in_array($pro['product_id'],$forbidden_product_ids)){
                                    $forbidden_product_array[$i]['key'] = $pro['key'];
                                    $forbidden_product_array[$i]['product_id'] = $pro['product_id'];
                                    $forbidden_product_array[$i]['name'] = $pro['name'];
                                    $forbidden_product_array[$i]['quantity'] = $pro['quantity'];
                                    $forbidden_product_array[$i]['price'] = $pro['price'];

                                    
                                    $forbidden_product_prices[]= $pro['total'];
                                    $forbidden_product_keys[] = $pro['key'];
                                    $i++;
                                }
                            }
                            $json['price'] = array_sum($forbidden_product_prices); 
                            $this->remove_forbidden_product($forbidden_product_array);
                    }   
                        $json['error']['warning'] = $warning;
                        $this->response->addHeader('Content-Type: application/json');
                        
                        if(isset($this->request->get['web_call']) && $this->request->get['web_call'] == 1) {
                            $this->response->setOutput(json_encode($json));
                        } else {
                            $json['price'] = array_sum($forbidden_product_prices);
                            $json['keys'] = implode(",",$forbidden_product_keys);
                            $this->response->setOutput($json);
                        } 

                } else {
                    return 0;
                }
                
	}
        
        //Custom Added : Get delivery fee based on selected payment type
        public function get_delivery_fee($payment_method_code) {
            $this->load->model('shipping/flat');
            
            if(isset($this->request->post['api_call']) && isset($this->request->post['payment_method'])){
                $payment_method_code = $this->request->post['payment_method'];
                if(($this->request->post['payment_method'] != 'BAMA Cash' && $this->request->post['payment_method'] !='DD')){
                    $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
                }else{
                    $this->session->data['payment_method'] = $this->request->post['payment_method'];
                }
                if($this->request->post['speedy_fee']  == "yes"){
                   $this->session->data['speedy_delivery'] = "yes";
                }
            }
            
            $payment_method_id = $this->model_shipping_flat->getPaymentCode($payment_method_code);
            $result = $this->model_shipping_flat->getDeliveryFee($payment_method_id);
            
           
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput($result);
                        
            return $result;
        }
        
        //Custom Added : Get speed delivery fee based on selected payment type
        public function get_max_speedy_delivery_fee($payment_method_code) {
            $this->load->model('shipping/flat');
        
            $payment_method_id = $this->model_shipping_flat->getPaymentCode($payment_method_code);
            
            $delivery_fee_id = $this->model_shipping_flat->getDeliveryFeeId($payment_method_id);
            
            $result = $this->model_shipping_flat->getSpeedyDeliveryFee($delivery_fee_id);

            return $result;
        }
        
        //Custom Added : Get speed delivery fee based on selected payment type
        public function get_distance_delivery_fee($payment_method_code) {
            $this->load->model('shipping/flat');
            
            if(isset($this->request->get['payment_method']) && $this->request->get['payment_method'] != "") {
                $payment_method_code = $this->request->get['payment_method'];
            }
        
            $payment_method_id = $this->model_shipping_flat->getPaymentCode($payment_method_code);
            $result = $this->model_shipping_flat->get_area_based_fee($payment_method_id);
            
            if(isset($this->request->get["api_call"]) && $this->request->get["api_call"] == "1") {
                $json['distance_delivery_fee'] = $result;
                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput($json);
            }
            return $result;
        }
        
        //Custom Added : To save Cwid
        public function save_cwid($cwid){
            $this->load->model('account/customer');
            $result = $this->model_account_customer->saveCwid($cwid);
            return $result;
            
        }
        
        //Custom Added : To get Cwid
        public function get_cwid(){
            $this->load->model('account/customer');
            $result['cwid'] = $this->model_account_customer->getCwid();
            
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput($result);
            
            return $result; 
            
        }
        
        public function remove_forbidden_product($forbidden_product_array) {
                

                $this->load->language('checkout/cart');

		$json = array();
		// Remove forbiddebn product from cart
		if ($forbidden_product_array) {
                        //for($i = 0; $i < count($keys); $i++) 
                         foreach($forbidden_product_array as $fp_array) { 
                            $this->cart->remove($fp_array['key']);
                        }
                        
                        $this->model_catalog_product->add_product_removal_record($forbidden_product_array);
                        
                        if(isset($this->session->data['coupon']) && $this->session->data['coupon'] != NUll){
                            $this->revalidateCoupon();
                        }
                        
                        $subTotal = $this->cart->getSubTotal();
                        $this->load->controller('ShippingMethod');
                        
                        if ($subTotal < $this->config->get('free_total')) {
                            $shipping_method = "flat.flat";
                        } else {
                            $shipping_method = "free.free";
                        }
                       
                        $shipping = explode('.', $shipping_method);
                        $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
		}
	}
        
        public function revalidateCoupon(){
            
            $coupon = $this->session->data['coupon'];
            
            $this->load->model('checkout/coupon');
            
            $coupon_info = $this->model_checkout_coupon->getCoupon($coupon);
		
            if ($coupon_info['status'] == false) {			
                  unset($this->session->data['coupon']);  
            }     
        } 
}
