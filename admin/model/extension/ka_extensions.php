<?php
/*
	Project: Ka Extensions
	Author : karapuz <support@ka-station.com>

	Version: 3 ($Revision$)
*/

class ModelExtensionKaExtensions extends KaModel {

	/*
		Compatible db may be fully patched or not patched at all. Partial changes are
		treated as a corrupted db.

		The method extends tables information with an 'exists' flag for existing elements.
		
		Returns
			true  - db is compatible
			false - db is not compatible

	*/
	public function checkDBCompatibility(&$tables, &$messages) {
	
		if (empty($tables)) {
			return true;
		}

		foreach ($tables as $tk => $tv) {

			$tbl = DB_PREFIX . $tk;
			$res = $this->kadb->safeQuery("SHOW TABLES LIKE '$tbl'");

			if (!empty($res->rows)) {
				$tables[$tk]['exists'] = true;
			} else {
				continue;
			}

			$fields = $this->kadb->safeQuery("DESCRIBE `$tbl`");
			if (empty($fields->rows)) {
				$messages .= "Table '$tbl' exists in the database but it is empty.";
				return false;
			}

			// check fields 

			$db_fields = array();
			foreach ($fields->rows as $v) {
				$db_fields[$v['Field']] = array(
					'type'  => $v['Type']
				);
			}

			foreach ($tv['fields'] as $fk => $field) {
			
				if (empty($db_fields[$fk])) {
					continue;
				}

				// if the field is found we validate its type

				$db_field = $db_fields[$fk];

				if ($field['type'] != $db_field['type']) {
					$messages .= "Field type '$db_field[type]' for '$fk' in the table '$tbl' does not match the required field type '$field[type]'.";
					return false;
				} else {
					$tables[$tk]['fields'][$fk]['exists'] = true;
				}
			}

			// check indexes
			/*
				We do not compare index fields yet, just ensure that the index with the appropriate
				name exists for the table.
			*/
			if (!empty($tv['indexes'])) {

				$rec = $this->kadb->safeQuery("SHOW INDEXES FROM `$tbl`");
				$db_indexes = array();
				foreach ($rec->rows as $v) {
					$db_indexes[$v['Key_name']]['columns'][] = $v['Column_name'];
				}

				foreach ($tv['indexes'] as $ik => $index) {
					if (!empty($db_indexes[$ik])) {
						$tables[$tk]['indexes'][$ik]['exists'] = true;
					}
				}
			}
		}

		return true;
	}

			
	public function patchDB($tables, &$messages) {

		if (empty($tables)) {
			return true;
		}

		foreach ($tables as $tk => $tv) {
			if (empty($tv['exists'])) {
				$this->kadb->safeQuery($tv['query']);
				continue;
			}

			if (!empty($tv['fields'])) {
				foreach ($tv['fields'] as $fk => $fv) {
					if (empty($fv['exists'])) {
 						if (empty($fv['query'])) {
 							$messages .= "Installation error. Cannot create '$tk.$fk' field.";
 							return false;
 						}
						$this->kadb->safeQuery($fv['query']);
					}
				}
			}

			if (!empty($tv['indexes'])) {
				foreach ($tv['indexes'] as $ik => $iv) {
					if (empty($iv['exists']) && !empty($iv['query'])) {
						$this->kadb->safeQuery($iv['query']);
					}
				}
			}
		}
	
		return true;
	}
	
	
	public function getExtensionInfoByObject($class, $heading_title = '') {

		$ext_info = array(
			'name'    => '',
			'version' => '',
		);

		// retrieve version and name from old headers
		//
		if (preg_match("/(.*)([\d\.]+)[\)]*$/U", $heading_title, $matches)) {
			$ext_info['name']    = trim(preg_replace("/ver$/", "", preg_replace("/\(.*/", "", trim($matches[1]))));
			$ext_info['version'] = trim(preg_replace("/[^\d\.]/", "", trim($matches[2])), '.');
		}
			
		// get version with a method when available
		//
		if (method_exists($class, 'getVersion')) {
			if (empty($ext_info['name'])) {
				$ext_info['name'] = $heading_title;
			}
			$ext_info['version'] = $class->getVersion();
		}

		return $ext_info;
	}
	
	
	public function updateRelated($related) {
	
		if (empty($related)) {
			return false;
		}
		
		foreach ($related as $k => $v) {
			$this->db->query("UPDATE " . DB_PREFIX . "extension SET show_related = " . (int)$v . " 
				WHERE code = '" . $this->db->escape($k) . "'"
			);
		}
	}
	
	public function getKaInstalled($type = 'ka_extensions') {
		$extension_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension 
			WHERE `type` = '" . $this->db->escape($type) . "'
		");
		
		if (empty($query->rows)) {
			return $extension_data;
		}
		
		foreach ($query->rows as $row) {
			$extension_data[$row['code']] = $row;
		}
		
		return $extension_data;
	}
}
