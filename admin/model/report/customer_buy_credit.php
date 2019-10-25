<?php
class ModelReportCustomerBuyCredit extends Model {
	public function getBuyCredit($data = array()) { 
        $sql = "SELECT oc.date_added, oc.date_added, oc.date_credit, SUM(oc.amount) AS total, CONCAT(oc.firstname, ' ', oc.lastname) AS customer, oc.amount, oc.customer_id, oc.order_id, oc.email FROM `" . DB_PREFIX . "order_credit` AS oc WHERE oc.credit_id != 0";				
		
        if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(oc.firstname, ' ', oc.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "'";
		}

		if (!empty($data['filter_email'])) {
			$sql .= " AND LCASE(oc.email) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
		}
		
		$sql .= " GROUP BY oc.order_id ORDER BY oc.order_id DESC";
				
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

	public function getTotalOrders($data = array()) {
		$sql = "SELECT COUNT(DISTINCT oc.order_id) AS total FROM `" . DB_PREFIX . "order_credit` oc WHERE oc.order_id != 0 AND oc.credit_id != 0";
					
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(oc.firstname, ' ', oc.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "'";
		}

		if (!empty($data['filter_email'])) {
			$sql .= " AND LCASE(oc.email) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
?>