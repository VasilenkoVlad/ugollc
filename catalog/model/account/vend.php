<?php
class ModelAccountVend extends Model {
	public function getOrderDetailsForVend($order_id) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}
                        
                        //delivery fee query
                        $delivery_fee_query = $this->db->query("SELECT `value` FROM " . DB_PREFIX . "order_total WHERE order_id = " . (int)$order_id . " and code = 'shipping' and title = 'Flat Shipping Rate'");
                        if($delivery_fee_query->num_rows > 0){
                            $delivery_fee = $delivery_fee_query->row['value'];
                        }else {
                            $delivery_fee = 0.00;
                        }
                        
                        $speedy_fee_query = $this->db->query("SELECT `value` FROM " . DB_PREFIX . "order_total WHERE order_id = " . (int)$order_id . " and code = 'speedy_fee' and title = 'Speedy Delivery Fee'");
                        
                        if($speedy_fee_query->num_rows > 0){
                            $speedy_fee = $speedy_fee_query->row['value'];
                        }else {
                            $speedy_fee = 0.00;
                        }
                        
                        $distance_fee_query = $this->db->query("SELECT `value` FROM " . DB_PREFIX . "order_total WHERE order_id = " . (int)$order_id . " and code = 'distance_fee' and title = 'Distance Fee'");
                        
                        if($distance_fee_query->num_rows > 0){
                            $distance_fee = $distance_fee_query->row['value'];
                        }else {
                            $distance_fee = 0.00;
                        }
                        
                        //store credit query
                        $store_credit_query = $this->db->query("SELECT `value` FROM " . DB_PREFIX . "order_total WHERE order_id = " . (int)$order_id . " and code = 'credit' and title = 'Store Credit'");
                        
                        if($store_credit_query->num_rows > 0){
                            $store_credit = $store_credit_query->row['value'];
                        }else {
                            $store_credit = 0.00;
                        }
                        
                        $check_sales_receipt_query	= $this->db->query("SELECT * FROM " . DB_PREFIX . "vend_sales_receipt_summary WHERE order_id = " .$order_id." AND status = 'Processed'");
			
			if($check_sales_receipt_query->num_rows) {
				exit; 
			}

			$product_query	= $this->db->query("SELECT " . DB_PREFIX . "product.*," . DB_PREFIX . "order_product.name," . DB_PREFIX . "order_product.quantity as order_quantity FROM " . DB_PREFIX . "product JOIN " . DB_PREFIX . "order_product ON ". DB_PREFIX . "product.product_id = ". DB_PREFIX . "order_product.product_id WHERE " . DB_PREFIX ."order_product.order_id = " .$order_id);
			
			$order_products = array();
			if($product_query->num_rows)
			{
				$i = 0;
				foreach($product_query->rows as $product_row) 
				{
                                    $order_products[$i]['product_sku']			= $product_row['sku'];
                                    $order_products[$i]['product_name']			= $product_row['name'];
                                    $order_products[$i]['product_price']		= $product_row['price'];
                                    $order_products[$i]['product_quantity']		= $product_row['order_quantity'];	
                                    $i++;
				}
				$coupon_data = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_query->row['order_id'] . "' and code = 'coupon'");
                                if($coupon_data->num_rows){
					$order_products[$i]['product_sku']			= "10820";
					$order_products[$i]['product_name']			= $coupon_data->row['title'];
					$order_products[$i]['product_price']		=   str_replace("-", "", $coupon_data->row['value']);
					$order_products[$i]['product_quantity']		= "-1";
                                }
                                
                        }else {
                          $credit_query = $this->db->query("SELECT " . DB_PREFIX . "order_credit.* FROM " . DB_PREFIX ."order_credit WHERE " . DB_PREFIX ."order_credit.order_id = " .$order_id);
                        
                          if($credit_query->num_rows){
                              $i = 0;
                              foreach($credit_query->rows as $product_row) 
                               {
                                    $order_products[$i]['product_sku']			= 10986;
                                    $order_products[$i]['product_name']			= "Store Credit";
                                    $order_products[$i]['product_price']		= $product_row['amount'];
                                    $order_products[$i]['product_quantity']		= 1;	
                                    $i++;
                               }    
                            }
                        }       
                       
			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'language_id'             => $order_query->row['language_id'],
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'date_modified'           => $order_query->row['date_modified'],
				'date_added'              => $order_query->row['date_added'],
				'ip'                      => $order_query->row['ip'],
                                'delivery_fee'            => round($delivery_fee,2),
                                'speedy_fee'              => round($speedy_fee, 2),
                                'distance_fee'            => round($distance_fee,2),
                                'store_credit'            => round($store_credit,2),
				'products'			  	  => $order_products
			);
		} else {
			return false;
		}
	}

	public function insert_vend_sale_into_opencart($vend_sale_array)
	{
		$status = $this->db->query("INSERT INTO " . DB_PREFIX . "vend_sales_receipt_summary SET order_id=".$vend_sale_array['order_id'].", status='".$vend_sale_array['status']."'");

		return $this->db->getLastId();
	}

	public function update_vend_sale_into_opencart($vend_sale_array)
	{
		$status = $this->db->query("UPDATE " . DB_PREFIX . "vend_sales_receipt_summary SET vend_sale_id='".$vend_sale_array['vend_sale_id']."', status='".$vend_sale_array['status']."',sale_check= '0',vend_status='".$vend_sale_array['vend_status']."' WHERE id=".$vend_sale_array['id']);

		return $status;
	}
        
        //Custom Added: Get sale data from vend_sales_receipt_summary table
        public function get_vend_sale_data(){
            $vend_sales_data = $this->db->query("SELECT vs.vend_sale_id,vs.order_id,vs.status,o.date_added FROM " . DB_PREFIX . "vend_sales_receipt_summary as vs join ".DB_PREFIX."order as o on vs.order_id = o.order_id WHERE (vs.sale_check = '0' || vs.vend_status ='ONACCOUNT') and  o.date_added >= DATE_SUB(NOW(),INTERVAL 1 HOUR)");
            
            return $vend_sales_data;
        }
        
        
        //Custom Added: To update sale_id check status 
         public function update_check_status($order_id,$vend_status){
            $status = $vend_sales_data = $this->db->query("UPDATE " . DB_PREFIX . "vend_sales_receipt_summary SET sale_check='1',vend_status = '".$vend_status."'WHERE order_id=".$order_id);
        
            return $status;
         }
}