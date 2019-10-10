<?php
/*
  Easy Sort Order with Drag'n Drop
  Premium Extension
  
  Copyright (c) 2013 - 2019 Adikon.eu
  http://www.adikon.eu/
  
  You may not copy or reuse code within this file without written permission.
*/
class ModelExtensionModuleDragNDropPosition extends Model {
	private $compatibility = null;

	/*
	  Set compatibility for all versions of Opencart
	*/
	public function __construct($registry) {
		parent::__construct($registry);

		include_once DIR_SYSTEM . 'library/vendors/dragndrop_position/compatibility.php';

		$this->compatibility = new OVCompatibility_13($registry);
		$this->compatibility->setApp('admin');
	}

	/*
	  Return compatibility instance
	*/
	public function compatibility() {
		return $this->compatibility;
	}

	public function sortExtensions($type, $data, $extensions = array()) {
		foreach ($data as $key => $value) {
			if (in_array($key, $extensions)) {
				$key = (version_compare(VERSION, '3') >= 0) ? $type . '_' . $key : $key;

				$query = $this->db->query("SELECT `key` FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($key) . "' AND (`key` = '" . $this->db->escape($key) . "_sort_order' OR `key` LIKE '%_sort_order') AND serialized = '0'");

				if ($query->num_rows) {
					$this->db->query("UPDATE " . DB_PREFIX . "setting SET value = '" . (int)$value . "' WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($key) . "' AND `key` = '" . $this->db->escape($query->row['key']) . "'");
				}
			}
		}
	}

	public function sortProducts($data) {
		foreach ($data as $key => $sort_order) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET sort_order = '" . (int)$sort_order . "' WHERE product_id = '" . (int)$key . "'");
		}

		$this->cache->delete('product');
	}

	public function sortFilters($data) {
		foreach ($data as $key => $sort_order) {
			$this->db->query("UPDATE " . DB_PREFIX . "filter_group SET sort_order = '" . (int)$sort_order . "' WHERE filter_group_id = '" . (int)$key . "'");
		}
	}

	public function sortAttributeGroups($data) {
		foreach ($data as $key => $sort_order) {
			$this->db->query("UPDATE " . DB_PREFIX . "attribute_group SET sort_order = '" . (int)$sort_order . "' WHERE attribute_group_id = '" . (int)$key . "'");
		}
	}

	public function sortAttributes($data) {
		foreach ($data as $key => $sort_order) {
			$this->db->query("UPDATE " . DB_PREFIX . "attribute SET sort_order = '" . (int)$sort_order . "' WHERE attribute_id = '" . (int)$key . "'");
		}
	}

	public function sortOptions($data) {
		foreach ($data as $key => $sort_order) {
			$this->db->query("UPDATE " . DB_PREFIX . "option SET sort_order = '" . (int)$sort_order . "' WHERE option_id = '" . (int)$key . "'");
		}
	}

	public function sortCategoriess($data) {
		foreach ($data as $key => $sort_order) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET sort_order = '" . (int)$sort_order . "' WHERE category_id = '" . (int)$key . "'");
		}

		$this->cache->delete('category');
	}

	public function sortManufacturers($data) {
		foreach ($data as $key => $sort_order) {
			$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET sort_order = '" . (int)$sort_order . "' WHERE manufacturer_id = '" . (int)$key . "'");
		}

		$this->cache->delete('manufacturer');
	}

	public function sortInformations($data) {
		foreach ($data as $key => $sort_order) {
			$this->db->query("UPDATE " . DB_PREFIX . "information SET sort_order = '" . (int)$sort_order . "' WHERE information_id = '" . (int)$key . "'");
		}

		$this->cache->delete('information');
	}

	public function sortCustomerGroups($data) {
		foreach ($data as $key => $sort_order) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer_group SET sort_order = '" . (int)$sort_order . "' WHERE customer_group_id = '" . (int)$key . "'");
		}
	}

	public function sortLanguages($data) {
		foreach ($data as $key => $sort_order) {
			$this->db->query("UPDATE " . DB_PREFIX . "language SET sort_order = '" . (int)$sort_order . "' WHERE language_id = '" . (int)$key . "'");
		}

		$this->cache->delete('language');
	}

	/*
	  Installation & Update
	  Table structure for the module
	*/
	public function install() {
		if (!$this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product_attribute LIKE 'sort_order'")->row) {
			$this->db->query("ALTER TABLE  " . DB_PREFIX . "product_attribute ADD sort_order INT(11) NOT NULL DEFAULT '0'");
		}

		if (!$this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product_option_value LIKE 'sort_order'")->row) {
			$this->db->query("ALTER TABLE  " . DB_PREFIX . "product_option_value ADD sort_order INT(11) NOT NULL DEFAULT '0'");
		}
	}

	public function uninstall() {
		
	}
}