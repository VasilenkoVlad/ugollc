<?php
class ModelExtensionShippingFlat extends Model {
	function getQuote($address) {            
		$this->load->language('extension/shipping/flat');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('flat_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('flat_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();

			$quote_data['flat'] = array(
				'code'         => 'flat.flat',
				'title'        => $this->language->get('text_description'),
				'cost'         => $this->config->get('flat_cost'),
				'tax_class_id' => $this->config->get('flat_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($this->config->get('flat_cost'), $this->config->get('flat_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
			);

			$method_data = array(
				'code'       => 'flat',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('flat_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}
        
        function getPaymentCode($payment_method){
            $query = $this->db->query("SELECT payment_method_id FROM " . DB_PREFIX . "payment_method WHERE payment_method_code = '" . $payment_method . "'");
            
            return $query->row['payment_method_id']; 
            
        }

        //Custom Added : Get delivery fee based on selected payment type
        function getDeliveryFee($payment_method_code) {  
            
            //Get cart total
            $cartCost = $this->cart->getSubTotal();
            
            //Get basic fee based on cart total
            $sql = "SELECT * FROM " . DB_PREFIX . "new_delivery_fee";
                
            if (isset($payment_method_code) && $payment_method_code != null) {
                    $sql .= " Where payment_method_id = " . $payment_method_code." and status = 1 ";
            }
            $query = $this->db->query($sql);
            
            $total_rows = count($query->rows);
            
            $basic_fee = '0.00';
            $speedy_delivery_fee = '0.00';
            $delivery_fee = array();
            
            //To get delivery by cart amount range
            if($cartCost < floatval($query->rows[0]['cart_amount1'])){
                $basic_fee = $query->rows[0]['basic_fee'];
                if(isset($_SESSION['speedy_delivery']) && $_SESSION['speedy_delivery'] == 'yes'){
                    $speedy_delivery_fee = $this->getSpeedyDeliveryFee($query->rows[0]['delivery_fee_id']);
                }
                    $type =   $query->rows[0]['fee_type'];
                
            }elseif($cartCost > floatval ($query->rows[$total_rows -1]['cart_amount1'])){
              
                $basic_fee = $query->rows[$total_rows -1]['basic_fee'];
                if(isset($_SESSION['speedy_delivery']) && $_SESSION['speedy_delivery'] == 'yes'){
                    $speedy_delivery_fee = $this->getSpeedyDeliveryFee($query->rows[$total_rows -1]['delivery_fee_id']);
                }
                $type =   $query->rows[$total_rows -1]['fee_type'];
            }else {
               
                for($i=1; $i < count($query->rows)- 1; $i++){
                    if($cartCost >= floatval ($query->rows[$i]['cart_amount1']) && $cartCost <= floatval ($query->rows[$i]['cart_amount2']) && $cartCost < floatval ($query->rows[$i+1]['cart_amount1']) ){
                       $basic_fee = $query->rows[$i]['basic_fee'];
                       if(isset($_SESSION['speedy_delivery']) && $_SESSION['speedy_delivery'] == 'yes'){
                            $speedy_delivery_fee = $this->getSpeedyDeliveryFee($query->rows[$i]['delivery_fee_id']);
                        }
                       $type =   $query->rows[$i]['fee_type'];
                    }
                }
            }
            
            if($type == 'P') {
                
                $products = $this->cart->getProducts();
                foreach($products as $pro) {
                    if($pro['name'] != "UGODRIVERTIP"){
                        $product_totals[] = $pro['total'];
                    }
                }
                $product_total = round(array_sum($product_totals),2);
                
                $delivery_fee['basic_fee'] = round($product_total * $basic_fee/100,2);
                
                if($delivery_fee['basic_fee'] < 3.99){
                    $delivery_fee['basic_fee'] = 3.99;
                }
                
            } else {
                 $delivery_fee['basic_fee']  = $basic_fee;
            }
            
            $delivery_fee['basic_fee'] = floatval($delivery_fee['basic_fee']);
            $delivery_fee['speedy_delivery_fee'] = floatval($speedy_delivery_fee);
            $delivery_fee['fee_type'] = $type; //for older app version 
            $delivery_fee['delivery_fee'] = strval($delivery_fee['basic_fee']);  //for older app version 
            return $delivery_fee;
            
        }
        
        function get_area_based_fee($payment_method_id) {

            $range_fee = 0.00;
            $sql = "Select * from ".DB_PREFIX. "delivery_range_fee where payment_type_id = ".$payment_method_id." and status =1";
            $query = $this->db->query($sql);
            $total_rows = count($query->rows);
            if($query->num_rows > 0){
                if(!isset( $this->request->get['api_call'])) {
                    $this->load->model('account/address');
                    if ($this->customer->isLogged()) 	$address = $this->model_account_address->getAddress($this->customer->getAddressId());
                    if (!empty($this->session->data['country_id']))  $address['country_id'] = $this->session->data['country_id'];
                    if (!empty($this->session->data['zone_id']))  $address['zone_id'] = $this->session->data['zone_id'];
                    if (!empty($this->session->data['postcode'])) $address['postcode'] = $this->session->data['postcode'];
                    if (!empty($this->session->data['city']))  $address['city'] = $this->session->data['city'];
                    if (!empty($this->session->data['shipping_address_id'])) $address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
                    if (!empty($this->session->data['shipping_address'])) $address = $this->session->data['shipping_address'];
                    if (empty($address['address_1']))	$address['address_1'] = '';
                    if (empty($address['address_2']))	$address['address_2'] = '';
                    if (empty($address['city']))		$address['city'] = '';
                    if (empty($address['postcode']))	$address['postcode'] = '';                    
                    $context = stream_context_create(array('http' => array('ignore_errors' => '1')));
                    $customer_address = $address['address_1'] . ' ' . $address['address_2'] . ' ' . $address['city'] . ' ' . $address['zone'] . ' ' . $address['country'] . ' ' . $address['postcode'];
                    $customer_address = html_entity_decode(preg_replace('/\s+/', '+', $customer_address), ENT_QUOTES, 'UTF-8');
                } else if(isset($this->request->get['api_call']) && $this->request->get['api_call'] == "1" && $this->request->get['addrString'] != "") {
                      $customer_address = $this->request->get['addrString'];
                      $customer_address = html_entity_decode(preg_replace('/\s+/', '+', $customer_address), ENT_QUOTES, 'UTF-8');
                }
                
                $store_address = html_entity_decode(preg_replace('/\s+/', '+', $this->config->get('config_address')), ENT_QUOTES, 'UTF-8');

                //Store geolocation
                if ($this->config->get('config_geocode')) {
                        $xy = explode(',', $this->config->get('config_geocode'));
                        $x1 = $xy[0];
                        $y1 = $xy[1];
                } else {
                    $geocode = json_decode(file_get_contents('https://maps.google.com/maps/api/geocode/json?key=AIzaSyDK_ECq9TWXRh2pHykP_nES5fAM3Mv260M&address='.$store_address.'&sensor=false', false, $context));
                    if (empty($geocode->results)) {
                        sleep(1);
                        $geocode = json_decode(file_get_contents('https://maps.google.com/maps/api/geocode/json?key=AIzaSyDK_ECq9TWXRh2pHykP_nES5fAM3Mv260M&address='.$store_address.'&sensor=false', false, $context));
                    }
                    $x1 = $geocode->results[0]->geometry->location->lat;
                    $y1 = $geocode->results[0]->geometry->location->lng;
                }
             
                //Customer geoloacation
                $geocode = json_decode(file_get_contents('https://maps.google.com/maps/api/geocode/json?key=AIzaSyDK_ECq9TWXRh2pHykP_nES5fAM3Mv260M&address='.$customer_address.'&sensor=false', false, $context));
                if (empty($geocode->results)) {
                        sleep(1);
                        $geocode = json_decode(file_get_contents('https://maps.google.com/maps/api/geocode/json?key=AIzaSyDK_ECq9TWXRh2pHykP_nES5fAM3Mv260M&address='.$customer_address.'&sensor=false', false, $context));
                }
                $x2 = $geocode->results[0]->geometry->location->lat;
                $y2 = $geocode->results[0]->geometry->location->lng;
                
             $distance = rad2deg(acos(sin(deg2rad($x1)) * sin(deg2rad($x2)) + cos(deg2rad($x1)) * cos(deg2rad($x2)) * cos(deg2rad($y1 - $y2)))) * 60 * 114 / 99;

             //To get delivery by cart amount range
            if($distance < floatval($query->rows[0]['range_1'])){
                $range_fee = $query->rows[0]['fee'];
                
            }elseif($distance > floatval ($query->rows[$total_rows -1]['range_1'])){
                $range_fee = $query->rows[$total_rows -1]['fee'];
            }else {
                for($i=1; $i < count($query->rows)- 1; $i++){
                    if($distance >= floatval ($query->rows[$i]['range_1']) && $distance <= floatval ($query->rows[$i]['range_2']) && $distance < floatval ($query->rows[$i+1]['range_1']) ){
                       $range_fee = $query->rows[$i]['fee'];
                    }
                }
            }
               
            }
            return $range_fee;
        }
        
        
        
        
        function getDeliveryFeeId($payment_method_id){
            
            $query = $this->db->query("SELECT delivery_fee_id FROM " . DB_PREFIX . "new_delivery_fee WHERE payment_method_id = '" . $payment_method_id . "' and cart_amount_range_criteria = 'above' and status = 1");
           
            return $query->row['delivery_fee_id']; 
            
        }
        
        //Get speedy delivery fee based on cart total
        function getSpeedyDeliveryFee($delivery_fee_id) {  
            
            $sql = "SELECT fee  FROM " . DB_PREFIX . "speedy_delivery_fee";
            
            $sql .= " Where CAST(end_time AS TIME) >= '".date("H:i:s")."' and CAST(start_time AS TIME) <= '".date("H:i:s")."' and status = 1 and delivery_fee_id =". $delivery_fee_id;
            
            $query = $this->db->query($sql);

            return floatval($query->row['fee']);
        }
}