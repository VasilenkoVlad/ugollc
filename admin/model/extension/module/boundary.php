<?php
class ModelExtensionModuleBoundary extends Model {

	public function getBoundaryDetails() {
		$setting_data = array();
		$query 			= $this->db->query("SELECT * FROM " . DB_PREFIX . "store_boundry");
		$store_data		= $query->row;
		return $store_data;
	}

	public function editSetting($code, $data, $store_id = 0) {
		if( is_array( $data ) ){
			$this->db->query("DELETE FROM `" . DB_PREFIX . "store_boundry`");
			$this->db->query("INSERT INTO " . DB_PREFIX . "store_boundry SET store_radius = '" . (int)$data['store_radius'] . "', latitude = '" . $data['latitude'] . "',longitude='".$data['longitude']."',status='".$data['status']."'");
		}
	}

	public function getBoundaryStatus() {
		$extension_data = array();
		$qry = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "store_boundry'");
 		if ( $qry->num_rows > 0) {
			$query = $this->db->query("SELECT status FROM " . DB_PREFIX . "store_boundry");
			if( $query->num_rows>=1 ){
				$store_status		= $query->row['status'];
				return $store_status;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}

	public function updateBoundaryStatus( $status ){
		$query = $this->db->query("UPDATE " . DB_PREFIX . "store_boundry SET status='".$status."'");
	}

	public function deleteSetting($code, $store_id = 0) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");
	}

	public function editSettingValue($code = '', $key = '', $value = '', $store_id = 0) {
		if (!is_array($value)) {
			$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($value) . "', serialized = '0'  WHERE `code` = '" . $this->db->escape($code) . "' AND `key` = '" . $this->db->escape($key) . "' AND store_id = '" . (int)$store_id . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1' WHERE `code` = '" . $this->db->escape($code) . "' AND `key` = '" . $this->db->escape($key) . "' AND store_id = '" . (int)$store_id . "'");
		}
	}
}
