<?php
class DB {
	private $adaptor;

	public function __construct($adaptor, $hostname, $username, $password, $database, $port = NULL) {
		$class = 'DB\\' . $adaptor;

		if (class_exists($class)) {
			$this->adaptor = new $class($hostname, $username, $password, $database, $port);
		} else {
			throw new \Exception('Error: Could not load database adaptor ' . $adaptor . '!');
		}
	}

	public function query($sql, $params = array()) {
		return $this->adaptor->query($sql, $params);
	}

	public function escape($value) {
		return $this->adaptor->escape($value);
	}

	public function countAffected() {
		return $this->adaptor->countAffected();
	}

//karapuz (ka_extensions.ocmod.xml) 
  	public function isKaInstalled($extension) {
		static $installed = array();
		
		if (isset($installed[$extension])) {
			return $installed[$extension];
		}
		
		$query = $this->query("SELECT * FROM " . DB_PREFIX . "extension WHERE code = '$extension'");
		if (empty($query->num_rows)) {
			$installed[$extension] = false;
			return false;
		}
		
		$installed[$extension] = true;
		
		return true;
  	}
///karapuz (ka_extensions.ocmod.xml) 
	public function getLastId() {
		return $this->adaptor->getLastId();
	}
	
	public function connected() {
		return $this->adaptor->connected();
	}
}