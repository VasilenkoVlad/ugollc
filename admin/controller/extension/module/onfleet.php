<?php
class ControllerExtensionModuleOnfleet extends Controller {
	private $error = array();

	public function index() {

                /* Load language file. */
		$this->load->language('extension/module/onfleet');
                $this->load->model('extension/module/onfleet');
                
		$this->document->setTitle($this->language->get('heading_title'));
                
		/* Check if data has been posted back. */
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			//$this->load->model('setting/setting');

                        $this->model_extension_module_onfleet->editSetting('onfleet', $this->request->post);
                        
			$this->session->data['success'] = $this->language->get('text_success');

			$this->cache->delete('onfleet');
                           
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
		}

		/* Load language strings. */
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_module'] = $this->language->get('text_module');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_text'] = $this->language->get('entry_text');
                $data['organisation_text'] = $this->language->get('organisation_text');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		/* Loading up some URLS. */
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');
		$data['form_action'] = $this->url->link('extension/module/onfleet', 'token=' . $this->session->data['token'], 'SSL');

		/* Present error messages to users. */
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}


		/* Initial values for form. */
		if (isset($this->request->post['mymodule_title'])) {
			$data['mymodule_title'] = $this->request->post['mymodule_title'];
		} else {
			$data['mymodule_title'] = $this->config->get('mymodule_title');
		}
		
		if (isset($this->request->post['mymodule_text'])) {
			$data['mymodule_text'] = $this->request->post['mymodule_text'];
		} else {
			$data['mymodule_text'] = $this->config->get('mymodule_text');
		}
              

		if (isset($this->request->post['mymodule_status'])) {
			$data['mymodule_status'] = $this->request->post['mymodule_status'];
		} else {
			$data['mymodule_status'] = $this->config->get('mymodule_status');
		}

		/* Breadcrumb. */
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('etension/module/onfleet', 'token=' . $this->session->data['token'], 'SSL')
		);

                $data['onfleet_details'] = $this->model_extension_module_onfleet->getonfleetDetails();
                
		/* Render some output. */
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('extension/module/onfleet.tpl', $data));
	}

        public function install() {
        
        $this->load->model('extension/event');

        $this->model_extension_event->addEvent('onfleet', 'post.order.add', 'checkout/onfleet/createTask');
            
        $this->db->query("
			   CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "onfleet`(
			  `onfleet_id` int(11) ,
			  `api_key` varchar(500) ,
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
			`task_status` varchar(10)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		");
        }
      

          public function uninstall() {

			 $this->load->model('extension/event');
			 $this->model_extension_event->deleteEvent('onfleet');
         }
   


        /* Check user input. */
	private function validate() {
                if (strlen($this->request->post['mymodule_title']) <= 3) {
                    $this->error['warning'] = $this->language->get('error_title');
		}

		if ($this->error) {
			return false;
		} else {
			return true;
		}
	}
}
