<?php
    class ModelCheckoutApiCouponConfirm extends Model {

        //Get customer id
        public function getCustomerId($order_id){
            $customer_query = $this->db->query("SELECT customer_id FROM `" . DB_PREFIX . "order` WHERE order_id = '" . $this->db->escape($order_id) . "'");
                return array(
                    'customer_id'     => $customer_query->row['customer_id'],
                );
            }

        //Get coupon info
        public function getCoupon($code){
            $coupon_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon` WHERE code = '" . $this->db->escape($code) . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND status = '1'");
            return array(
                'coupon_id'     => $coupon_query->row['coupon_id'],
                'code'          => $coupon_query->row['code'],
                'discount'      => $coupon_query->row['discount'],
                'date_added'    => $coupon_query->row['date_added']
            );
        }

        //Add coupon history
        public function addApiCouponHistory($coupon_info,$order_id,$customer_id){
            if($coupon_info) {
               $this->db->query("INSERT INTO `" . DB_PREFIX . "coupon_history` SET coupon_id = '" . (int)$coupon_info['coupon_id'] . "', order_id = '" . (int)$order_id . "', customer_id = '" . (int)$customer_id . "', amount = '" . (float)$coupon_info['discount'] . "', date_added = NOW()");
                   return $coupon_historty_id = $this->db->getLastId();
           }
        }    

        //Add order total with coupon in order_total table
        public function addApiOrderTotal($coupon_info,$order_id){
            $code = 'coupon';
            $title = 'COUPON('.$coupon_info['code'].')';
            $coupon_discount = $coupon_info['discount'];
            $sort_order = 4;
            $order_total_exist = $this->db->query("Select * from `" . DB_PREFIX . "order_total` where order_id = '" . (int)$order_id . "' and code = 'coupon'");
            if($order_total_exist->num_rows == 0){
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($code) . "', title = '" . $this->db->escape($title) . "', `value` = '" . (float)$coupon_discount . "', sort_order = '".(int)$sort_order."'");       
            }
            $customer_id = $this->customer->getId();
            $history_id = $this->addApiCouponHistory($coupon_info, $order_id,$customer_id);           
            return $history_id;
            
        }
    }
?>
