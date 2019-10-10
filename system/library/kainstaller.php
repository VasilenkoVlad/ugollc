<?php
/*
	Project: Ka Extensions
	Author : karapuz <support@ka-station.com>

	Version: 3 ($Revision: 42 $)
*/

require_once(modification(DIR_APPLICATION . 'controller/extension/modification.php'));
class KaModificationController extends ControllerExtensionModification {
	protected function getList() {
		return true;
	}
	
	public function refresh($data = array()) {
		$GLOBALS['ka_silent_return'] = true;
		$result = parent::refresh($data);
		$GLOBALS['ka_silent_return'] = false;
		return $result;
	}
}


class KaInstaller extends KaController {

	// contstants
	//
	protected $extension_version = '0.0.0';
	protected $min_store_version = '2.3.0.0';
	protected $max_store_version = '2.3.0.9';
	
	protected $tables;
	protected $xml_file = '';

	/*
		Returns
			true  - db is compatible
			false - db is not compatible
	*/
	protected function checkDBCompatibility(&$messages) {

		$this->load->kamodel('extension/ka_extensions');
		$result = $this->model_extension_ka_extensions->checkDBCompatibility($this->tables, $messages);
		
		return $result;
	}

		
	protected function patchDB(&$messages) {

		$this->load->kamodel('extension/ka_extensions');
		$result = $this->model_extension_ka_extensions->patchDB($this->tables, $messages);
		
		return $result;
	}

	
	protected function applyXml() {
		
		$modification_controller = new KaModificationController($this->registry);
		$result = $modification_controller->refresh();
		
		return $result;
	}

		
	protected function checkCompatibility(&$messages) {
	
		// check store version 
		if (version_compare(VERSION, $this->min_store_version, '<')
			|| version_compare(VERSION, $this->max_store_version, '>'))
		{
			$messages .= "compatibility of this extension with your store version (" . VERSION . ") was not checked.
				Please contact ka-station team for update.";
			return false;
		}
		
		//check database
		//
		if (!$this->checkDBCompatibility($messages)) {
			return false;
		}
    
		return true;
	}
	
	
	public function install() {

		if (!$this->checkCompatibility($messages)) {
			$this->addTopMessage($messages, 'E');
			return false;
		}
		
		if (!$this->patchDB($messages)) {
			$this->addTopMessage($messages, 'E');
			return false;
		}
		
		if (!$this->applyXml()) {
			$messages .= "Possible errors in applying xml file. Check the log files and 
				contact ka-support at <b>support@ka-station.com</b> for assistance.
			";
		
			$this->addTopMessage($messages, 'E');
			return false;
		}
		
		return true;
	}
	
	public function uninstall() {
		$this->addTopMessage("The extension has been uninstalled successfully");	
		return true;
	}
	
	
	public function getTitle() {
		$str = str_replace('{{version}}', $this->extension_version, $this->language->get('heading_title_ver'));
		return $str;
	}
	
}

?>