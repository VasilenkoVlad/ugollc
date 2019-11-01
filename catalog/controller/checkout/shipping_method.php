<?php
class ControllerCheckoutShippingMethod extends Controller {
	public function index() {
		$this->load->language('checkout/checkout');

		if (isset($this->session->data['shipping_address'])) {
			// Shipping Methods
			$method_data = array();

			$this->load->model('extension/extension');

			$results = $this->model_extension_extension->getExtensions('shipping');

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('extension/shipping/' . $result['code']);

					$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

					if ($quote) {
						$method_data[$result['code']] = array(
							'title'      => $quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
				}
			}

			$sort_order = array();

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);

			$this->session->data['shipping_methods'] = $method_data;
		}

		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['text_comments'] = $this->language->get('text_comments');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_continue'] = $this->language->get('button_continue');

		if (empty($this->session->data['shipping_methods'])) {
			$data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['shipping_methods'])) {
			$data['shipping_methods'] = $this->session->data['shipping_methods'];
		} else {
			$data['shipping_methods'] = array();
		}

		if (isset($this->session->data['shipping_method']['code'])) {
			$data['code'] = $this->session->data['shipping_method']['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->session->data['comment'])) {
			$data['comment'] = $this->session->data['comment'];
		} else {
			$data['comment'] = '';
		}
                                
		$this->response->setOutput($this->load->view('checkout/shipping_method', $data));
		
                if (isset($this->session->data['speedy_delivery']) && $this->session->data['speedy_delivery'] == 'yes' ) {
			$data['speedy_delivery'] = $this->session->data['speedy_delivery'];
		} else {
			$data['speedy_delivery'] = '';
		}
                
                $express_option_query = "SELECT value FROM oc_setting where `key` = 'config_express_delivery_status' and `code` = 'config'";
                $express_option = $this->db->query($express_option_query);
                if(isset($express_option->row['value']) && $express_option->row['value'] == 1){
                    $data['express_option'] = 1;
                 } else {
                    $data['express_option'] = 0;
                 }

                $this->response->setOutput($this->load->view('checkout/shipping_method.tpl', $data));
	}	

	public function save() {
		$this->load->language('checkout/checkout');

		$json = array();

		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', true);
		}

		// Validate if shipping address has been set.
		if (!isset($this->session->data['shipping_address'])) {
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

		if (!isset($this->request->post['shipping_method'])) {
			$json['error']['warning'] = $this->language->get('error_shipping');
		} else {
			$shipping = explode('.', $this->request->post['shipping_method']);

			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$json['error']['warning'] = $this->language->get('error_shipping');
			}
		}

		if (!$json) {
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

			$this->session->data['comment'] = strip_tags($this->request->post['comment']);
                        if(isset($this->request->post['speedy_delivery'])) {
                            $this->session->data['speedy_delivery'] = $this->request->post['speedy_delivery'];
                        } else {
                            unset($this->session->data['speedy_delivery']);
                            unset($this->session->data['speedy_fee']);
                        }
                        
                        if(isset($this->session->data['shipping_method']) && $this->session->data['shipping_method']['code'] == "pickup.pickup") {
                            unset($this->session->data['speedy_delivery']);
                            unset($this->session->data['speedy_fee']);
                            unset($this->session->data['distance_fee']);
                            
                            if(isset($this->session->data['shipping_method']['cost']) && $this->session->data['shipping_method']['cost'] != 0.00 ){
                                $this->session->data['shipping_method']['cost'] = 0.00;
                            }
                        }
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
        
        //Custom function called to get shipping method for API        
        public function get_shipping_method() {
		$this->load->language('checkout/checkout');

                    // Shipping Methods
                    $method_data = array();

                    $this->load->model('extension/extension');

                    $results = $this->model_extension_extension->getExtensions('shipping');

                    foreach ($results as $result) {
                            if ($this->config->get($result['code'] . '_status')) {
                                    $this->load->model('extension/shipping/' . $result['code']);
                                    
                                    if (isset($this->session->data['shipping_address'])) {

                                        $quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);
                                    }  else {
                                        
                                        $address = array();
                                        $address['zone_id'] = 3613;
                                        $address['country_id'] = 223;
                                        $quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($address);
                                    }
                                    
                                    if ($quote) {
                                            $method_data[$result['code']] = array(
                                                    'title'      => $quote['title'],
                                                    'quote'      => $quote['quote'],
                                                    'sort_order' => $quote['sort_order'],
                                                    'error'      => $quote['error']
                                            );
                                    }
                            }
                    }

                    $sort_order = array();

                    foreach ($method_data as $key => $value) {
                            $sort_order[$key] = $value['sort_order'];
                    }

                    array_multisort($sort_order, SORT_ASC, $method_data);

                    $this->session->data['shipping_methods'] = $method_data;
		

		if (empty($this->session->data['shipping_methods'])) {
			$data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['shipping_methods'])) {
			$data['shipping_methods'] = $this->session->data['shipping_methods'];
		} else {
			$data['shipping_methods'] = array();
		}

		if (isset($this->session->data['shipping_method']['code'])) {
			$data['code'] = $this->session->data['shipping_method']['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->session->data['comment'])) {
			$data['comment'] = $this->session->data['comment'];
		} else {
			$data['comment'] = '';
		}

		$this->response->addHeader('Content-Type: application/json');
	        $this->response->setOutput($data);
        }
        
        public function express_option() {
            
            $express_option_query = "SELECT value FROM oc_setting where `key` = 'config_express_delivery_status' and `code` = 'config'";
                $express_option = $this->db->query($express_option_query);
                if($express_option->row['value'] == 1){
                    $data['express_option'] = 1;
                 } else {
                    $data['express_option'] = 0;
                 }
                 
                $this->response->addHeader('Content-Type: application/json');
	        $this->response->setOutput($data);
            
        }
}