<?php
/*
	Project : Ka Extensions
	Author  : karapuz <support@ka-station.com>

	Version : 3 ($Revision: 53 $)
*/

class ControllerExtensionKaExtensions extends KaController {

	protected $extension_version = '3.6.3.0';
	protected $tables;

	public function onLoad() {
		$this->load->language('extension/ka_extensions');
		
 		$this->tables = array(
 			'extension' => array(
 				'fields' => array(
 					'show_related' => array(
 						'type' => 'tinyint(1)',
 						'query' => "ALTER TABLE `" . DB_PREFIX . "extension` ADD `show_related` TINYINT(1) NOT NULL DEFAULT '0'"
 					),
 				),
 			),
		);
		
		$this->load->kamodel('extension/ka_extensions');
	}

	
	public function index() {
		$this->document->setTitle($this->language->get('heading_title'));

		$messages = "";
		if ($this->model_extension_ka_extensions->checkDBCompatibility($this->tables, $messages)) {
			if (!$this->model_extension_ka_extensions->patchDB($this->tables, $messages)) {
				$this->addTopMessage($messages, "E");
			}
		} else {
			$this->addTopMessage($messages, "E");
		}

		// getList functionality
		//
		$this->data['heading_title']   = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_confirm']    = $this->language->get('text_confirm');

		$this->data['column_name']     = $this->language->get('column_name');
		$this->data['column_action']   = $this->language->get('column_action');

		$this->data['extension_version'] = $this->extension_version;
		
		$this->data['http_catalog'] = HTTP_CATALOG;
		$this->data['oc_version']   = VERSION;
		
		$this->load->model('extension/extension');

		$extensions = $this->model_extension_ka_extensions->getKaInstalled('ka_extensions');
		$extension_codes = array_keys($extensions);
		
		foreach ($extensions as $key => $value) {
			if (!file_exists(DIR_APPLICATION . 'controller/ka_extensions/' . $key . '.php')) {
				$this->model_extension_extension->uninstall('ka_extensions', $key);
				
				unset($extensions[$key]);
			}
		}
	
		$this->data['extensions'] = array();
		$files = glob(DIR_APPLICATION . 'controller/ka_extensions/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
				
				$this->loadLanguage('ka_extensions/' . $extension);

				require_once(modification(DIR_APPLICATION . 'controller/ka_extensions/' . $extension . '.php'));
				$class = 'ControllerKaExtensions' . str_replace('_', '', $extension);
				$class = new $class($this->registry);

				if (method_exists($class, 'getTitle')) {
					$heading_title = $class->getTitle();
				} else {
					$heading_title = $this->language->get('heading_title');
				}
				
				$action = array();
				
				$ext = array(
					'name'      => $heading_title,
					'extension' => $extension,
				);
				
				if (!empty($extensions[$extension])) {
					$ext['show_related'] = $extensions[$extension]['show_related'];
				}
				
				$ext = array_merge($ext, $this->model_extension_ka_extensions->getExtensionInfoByObject($class, $heading_title));
				
				if (!in_array($extension, $extension_codes)) {
					$action[] = array(
						'text' => $this->language->get('button_install'),
						'href' => $this->url->link('extension/ka_extensions/install', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL')
					);
				} else {
					$ext['is_installed'] = true;
					$action[] = array(
						'text' => $this->language->get('button_edit'),
						'href' => $this->url->link('ka_extensions/' . $extension . '', 'token=' . $this->session->data['token'], 'SSL')
					);
					
					$action[] = array(
						'text' => $this->language->get('button_uninstall'),
						'href' => $this->url->link('extension/ka_extensions/uninstall', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL')
					);
				}
				
				$ext['action'] = $action;

				$this->data['extensions'][] = $ext;
			}
		}

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		
//		$this->data['ka_station_url'] = '//localhost/ka-station/oc1513';
		$this->data['ka_station_url'] = 'https://www.ka-station.com';
		
		$this->data['token'] = $this->session->data['token'];		
		$this->template = 'extension/ka_extensions.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left'
		);
		$this->response->setOutput($this->render());
	}
	
	
	public function install() {

		if (!$this->user->hasPermission('modify', 'extension/ka_extensions') &&
			!$this->user->hasPermission('modify', 'user/user_permission')
		) {
			$this->addTopMessage($this->language->get('error_permission'), 'E'); 
			
			$this->redirect($this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL'));
		} else {

			$config_maintenance = $this->config->get('config_maintenance');					
			try {
				require_once(modification(DIR_APPLICATION . 'controller/ka_extensions/' . $this->request->get['extension'] . '.php'));
				
				$class = 'ControllerKaExtensions' . str_replace('_', '', $this->request->get['extension']);
				$class = new $class($this->registry);
				
				if (method_exists($class, 'prepareInstallation')) {
					$res = $class->prepareInstallation();
					
					if (empty($res)) {
						$this->redirect($this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL'));
					} elseif ($res == 'redirect') {
						$this->redirect($this->url->link('extension/ka_extensions/install', 'extension=' . $this->request->get['extension'] . '&token=' . $this->session->data['token'], 'SSL'));
					}
				}
				
				$success = false;
				if (method_exists($class, 'install')) {
					$success = $class->install();
				}
			} catch (Exception $e) {
			
			}

			if (strlen($config_maintenance) > 0) {
				$this->load->model('setting/setting');
				$config = $this->model_setting_setting->getSetting('config');
				if ($config_maintenance != $config['config_maintenance']) {
					$this->model_setting_setting->editSettingValue('config', 'config_maintenance', $config_maintenance);
				}
			}
			
			if ($success) {
				$this->load->model('extension/extension');
				$this->model_extension_extension->install('ka_extensions', $this->request->get['extension']);

				$this->load->model('user/user_group');
			
				$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'ka_extensions/' . $this->request->get['extension']);
				$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'ka_extensions/' . $this->request->get['extension']);
				
				$this->addTopMessage('Extension is installed successfully.');
			} else {
				$this->addTopMessage("Extension is not installed", 'E');
			}
			
			$this->redirect($this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
	
	
	public function uninstall() {

		if (!$this->user->hasPermission('modify', 'extension/ka_extensions') &&
			!$this->user->hasPermission('modify', 'user/user_permission')
		) {
			$this->addTopMessage($this->language->get('error_permission'), 'E'); 
			
			$this->redirect($this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL'));
		} else {		
			$this->load->model('extension/extension');
			$this->load->model('setting/setting');
					
			$this->model_extension_extension->uninstall('ka_extensions', $this->request->get['extension']);
		
			$this->model_setting_setting->deleteSetting($this->request->get['extension']);
		
			require_once(modification(DIR_APPLICATION . 'controller/ka_extensions/' . $this->request->get['extension'] . '.php'));
			
			$class = 'ControllerKaExtensions' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);
			
			if (method_exists($class, 'uninstall')) {
				$class->uninstall();
			}
		
			$this->redirect($this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL'));	
		}
	}
	
	public function updateRelated() {
	
		$related = $this->request->post;
		$this->model_extension_ka_extensions->updateRelated($related);
		
		$json = array();
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
}
?>