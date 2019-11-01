<?php
/*
	Project: Ka Extensions
	Author : karapuz <support@ka-station.com>

	Version: 3 ($Revision: 42 $)
*/

class ControllerCommonKaTop extends Controller {

	/*
		$data - exact copy of the $this->data array assigned to the parent controller;
	*/
	public function index($data) {
		$this->data = $data;

		$template = '/template/common/ka_top.tpl';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $template)) {
			$this->template = $this->config->get('config_template') . $template;
		} else {
			$this->template = 'default' . $template;
		}
		
    	$this->render();
  	}
}
?>