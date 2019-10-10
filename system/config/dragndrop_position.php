<?php
/*
  Easy Sort Order with Drag'n Drop
  Premium Extension
  
  Copyright (c) 2013 - 2019 Adikon.eu
  http://www.adikon.eu/
  
  You may not copy or reuse code within this file without written permission.
*/
$_['ddp_module_type']  = 'module';
$_['ddp_module_name']  = 'module_dragndrop_position';
$_['ddp_module_path']  = 'extension/module/dragndrop_position';
$_['ddp_module_model'] = 'model_extension_module_dragndrop_position';
$_['ddp_fields'] = array(
	'status' => array('default' => '0', 'decode' => false, 'required' => true),
	'access' => array('default' => '', 'decode' => false, 'required' => true),
	'sort' => array('default' => '0', 'decode' => false, 'required' => false)
);
$_['ddp_menu'] = array();