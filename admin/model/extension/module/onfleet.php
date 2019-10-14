<?php
class ModelExtensionModuleOnfleet extends Model {

	public function getInstalled($type) {
		$extension_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' ORDER BY code");

		foreach ($query->rows as $result) {
			$extension_data[] = $result['code'];
		}
                
		return $extension_data;
                
	}
        
  public function getSetting( $code, $store_id = 0 ) {
    
		$setting_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "onfleet");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$setting_data[$result['key']] = $result['value'];
			} else {
				$setting_data[$result['key']] = json_decode($result['value'], true);
			}
		}

		return $setting_data;
	}
        
  public function getonfleetDetails() {

		$setting_data = array();
		$query 			= $this->db->query("SELECT * FROM " . DB_PREFIX . "onfleet");
		$store_data		= $query->row;
                return $store_data;
	}
        
        
        public function editSetting($code, $data, $store_id = 0) {
           
            if(is_array($data)){
			$this->db->query("DELETE FROM `" . DB_PREFIX . "onfleet`");
			$this->db->query("INSERT INTO " . DB_PREFIX . "onfleet SET api_key = '" .$data['mymodule_title'] . "', key_name = '" . $data['mymodule_text'] . "',organisation_id='".$data['organisation_text']."',status='".$data['status']."'");
            } 
           
           
        }

        public function testfunction(){
            $this->db->query("INSERT INTO ".DB_PREFIX . "testevent (testname,testvalue) VALUES ('Imtestname','Eventvalue')");
        }
                
	public function install($type, $code) {
    
             //$this->load->model('extension/event');
             //$this->model_extension_store_onfleet->createTask()
             //  $this->model_extension_event->addEvent('onfleet','post.order.add', 'model/extension/store_onfleet/createTask');
    	      $this->db->query("INSERT INTO " . DB_PREFIX . "extension SET `type` = '" . $this->db->escape($type) . "', `code` = '" . $this->db->escape($code) . "'");
              $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "onfleet`(
			  `onfleet_id` int(11) ,
			  `API_KEY` varchar(500) ,
                          `key_name` varchar(200) ,
                          `organisation_id` varchar(200),
                          `status` tinyint(1)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		");
              
                $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "recipients`(
                            
			  `recipient_id` varchar(250),
			  `timeCreated` varchar(250),
                          `timeLastModified` varchar(250),
                          `name` varchar(500) ,
                          `phone` varchar(500) ,
                          `notes` text ,
                          `skipSMSNotifications` tinyint(1) 
                       ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		");
              
                
                $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "destination`(
			  `destination_id` varchar(250),
			  `timeCreated` varchar(250),
                          `timeLastModified` varchar(250),
                          `location` varchar(500) ,
                          `address` text ,
                          `notes` text ,
                          `tasks` text 
                    	) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		");
           
        $this->db->query("
			   CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "tasks`(
			  `task_id` varchar(250),
        `order_id` int(11),
			  `timeCreated` varchar(250),
                          `timeLastModified` varchar(250),
                          `organization` varchar(500) ,
                          `shortId` varchar(250) ,
                          `trackingURL` varchar(500) ,
                          `worker` varchar(250) ,
                          `merchant` varchar(250) ,
                          `executor` varchar(250) ,
                          `creator` varchar(250) ,
                          `dependencies`  varchar(250) ,
                          `state` varchar(200) ,
                          `completeAfter` varchar(250) ,
                          `completeBefore` varchar(250) ,
                          `pickupTask` varchar(250) ,
                          `completionDetails` text,
                          `feedback` text,
                          `metadata` text,
                          `overrides` text,
                          `recipients` text,
                          `destination` text,
                          `task_status` varchar(10),
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		");
                
}

  public function uninstall($type, $code) {

      $this->db->query("DELETE FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");
      $this->db->query("DROP TABLE ". DB_PREFIX ."recipients" );
      $this->db->query("DROP TABLE ". DB_PREFIX ."destination" );
      $this->db->query("DROP TABLE ". DB_PREFIX ."tasks" );

      /*
      $this->db->query("DELETE FROM " . DB_PREFIX . "recipients WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");
      $this->db->query("DELETE FROM " . DB_PREFIX . "destination WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");
      $this->db->query("DELETE FROM " . DB_PREFIX . "tasks WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");
      */
  }

}