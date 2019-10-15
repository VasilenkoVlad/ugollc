<?php
class ModelCustomSettingsDeliveryFee extends Model {

	public function editDeliveryFee($delivery_fee_id, $data) {

		$this->db->query("UPDATE " . DB_PREFIX . "new_delivery_fee SET cart_amount_range_criteria = '" . $this->db->escape($data['range_type']) . "',cart_amount1 = '".$this->db->escape($data['cart_amount_1'])."',cart_amount2 = '".$this->db->escape($data['cart_amount_2'])."', fee_type = '".$this->db->escape($data['fee_type'])."',basic_fee = '".$this->db->escape($data['basic_fee'])."',date_modified = NOW() WHERE delivery_fee_id = '" . (int)$delivery_fee_id . "'");

	}
        
        public function editDeliveryRangeFee($delivery_range_fee_id, $data) {
				
                $this->db->query("UPDATE " . DB_PREFIX . "delivery_range_fee SET  payment_type_id = '".(int)$data['payment_type_id']."', range_type = '" . $this->db->escape($data['range_type']) . "',range_1 = '".$this->db->escape($data['range_1'])."',range_2 = '".$this->db->escape($data['range_2'])."',fee = '".$this->db->escape($data['fee'])."',date_modified = NOW() WHERE delivery_range_fee_id = '" . (int)$delivery_range_fee_id . "'");
		
	}
        
        public function addDeliveryFee($data) {
       
                $q = $this->db->query("INSERT INTO " . DB_PREFIX . "new_delivery_fee SET payment_method_id = '".(int)$data['payment_method_id']."', cart_amount_range_criteria = '" . $this->db->escape($data['range_type']) . "',cart_amount1 = '".$this->db->escape($data['cart_amount_1'])."',cart_amount2 = '".$this->db->escape($data['cart_amount_2'])."', fee_type = '".$this->db->escape($data['fee_type'])."',basic_fee = '".$this->db->escape($data['basic_fee'])."',date_added = NOW(), date_modified = NOW()");
                
                $delivery_fee_id  = $this->db->getLastId();

                $this->db->query("INSERT INTO " . DB_PREFIX . "speedy_delivery_fee SET delivery_fee_id = '".$delivery_fee_id."', time_slot = '8 AM - 7 PM', start_time = '13:00:00', end_time = '23:59:59', date_added = NOW(), date_modified = NOW(), status = 1");
                $this->db->query("INSERT INTO " . DB_PREFIX . "speedy_delivery_fee SET delivery_fee_id = '".$delivery_fee_id."', time_slot = '7 PM - 12 AM',start_time = '00:00:00', end_time = '04:59:59', date_added = NOW(), date_modified = NOW(), status = 1");
                $this->db->query("INSERT INTO " . DB_PREFIX . "speedy_delivery_fee SET delivery_fee_id = '".$delivery_fee_id."', time_slot = '12 AM - 8 AM',start_time = '05:00:00', end_time = '12:59:59', date_added = NOW(), date_modified = NOW(), status = 1");

	}
        
        public function addDeliveryRangeFee($data) {
        
		$q = $this->db->query("INSERT INTO " . DB_PREFIX . "delivery_range_fee SET payment_type_id = '".(int)$data['payment_method_id']."', range_type = '" . $this->db->escape($data['range_type']) . "',range_1 = '".$this->db->escape($data['range_1'])."',range_2 = '".$this->db->escape($data['range_2'])."',fee = '".$this->db->escape($data['fee'])."',date_added = NOW(), date_modified = NOW(),status = 1");
              
                $delivery_fee_range_id  = $this->db->getLastId();
                
	}
        
        public function deleteDeliveryFee($delivery_fee_id) {

		$this->db->query("UPDATE " . DB_PREFIX . "new_delivery_fee SET status = 0,date_modified = NOW() WHERE delivery_fee_id = '" . (int)$delivery_fee_id . "'");
                
                $this->db->query("UPDATE " . DB_PREFIX . "speedy_delivery_fee SET status = 0,date_modified = NOW() WHERE delivery_fee_id = '" . (int)$delivery_fee_id . "'");

    	}
        
        public function deleteDeliveryRangeFee($delivery_range_fee_id) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "delivery_range_fee SET status = 0,date_modified = NOW() WHERE delivery_range_fee_id = '" . (int)$delivery_range_fee_id . "'");
                
	}

	public function getFee($payment_method_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "delivery_fee WHERE payment_method_id = '" . (int)$payment_method_id . "' and status = 1");

		return $query->row;
	}
        
        public function getFees($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "new_delivery_fee";
                
                if (isset($data['payment_type']) && isset($data['delivery_fee_type'])) {
			$sql .= " Where payment_method_id = " . $data['payment_type']." and status = 1 ";
		}
                
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);
                return $query->rows;
	}
        
        public function getTotalCodFees() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "new_delivery_fee where payment_method_id = 1");

		return $query->row['total'];
	}
        
        public function getTotalCardPaymentFees() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "new_delivery_fee where payment_method_id = 2");

		return $query->row['total'];
	}
        
        public function getTotalBcashFees() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "new_delivery_fee where payment_method_id = 3");

		return $query->row['total'];
	}
        public function getTotalDDFees() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "new_delivery_fee where payment_method_id = 4");

		return $query->row['total'];
	}
        public function getTotalStripeFees() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "new_delivery_fee where payment_method_id = 5");

		return $query->row['total'];
	}
        
        public function getSpeedyFee($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "speedy_delivery_fee where delivery_fee_id = '".$id."' and status = 1 ");

		return $query->rows;
	}
        
        public function getRangeFee($id) {
		
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "delivery_range_fee where payment_type_id = '".$id."' and status = 1 ");

		return $query->rows;
	}
        
        public function editSpeedyFee($id,$data) {

		$this->db->query("UPDATE " . DB_PREFIX . "speedy_delivery_fee SET fee = '" . $this->db->escape($data['speedy_delivery_fee']) . "',date_modified = NOW() WHERE speedy_delivery_fee_id = '" . (int)$id . "'");

	}
}