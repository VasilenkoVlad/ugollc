<?php
class ControllerExtensionModuleBuyCredit extends Controller { 
	private $error = array();
	
	public function index() {  
		$this->load->language('extension/module/buy_credit');

		$this->document->setTitle($this->language->get('heading_title'));
		
        $this->load->model('design/layout');
		$this->load->model('tool/image');
		$this->load->model('setting/setting');
				 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('buy_credit', $this->request->post);	

			$this->session->data['success'] = $this->language->get('text_success');

			if(isset($this->request->post['save_stay']) and $this->request->post['save_stay']=1){
			    $this->response->redirect($this->url->link('extension/module/buy_credit', 'token=' . $this->session->data['token'], 'SSL')); 
            } else { 
                $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
            }
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');
        $data['text_send_credit'] = $this->language->get('text_send_credit');
        $data['text_apply_credit'] = $this->language->get('text_apply_credit');
        $data['text_buy_credit'] = $this->language->get('text_buy_credit');
        $data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');
        $data['text_free'] = $this->language->get('text_free');
        $data['text_fixed'] = $this->language->get('text_fixed');
        $data['text_use_never'] = $this->language->get('text_use_never');
        $data['text_use_cart'] = $this->language->get('text_use_cart');
        
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_type_buy_credit'] = $this->language->get('entry_type');
        $data['entry_custom_subject'] = $this->language->get('entry_custom_subject');
        $data['entry_message_from_email'] = $this->language->get('entry_message_from_email');
        $data['entry_message_to_email'] = $this->language->get('entry_message_to_email');
        $data['entry_admin_subject'] = $this->language->get('entry_admin_subject');
        $data['entry_message_admin'] = $this->language->get('entry_message_admin');
        $data['entry_subject'] = $this->language->get('entry_subject');
        $data['entry_message'] = $this->language->get('entry_message');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_default'] = $this->language->get('entry_default');
        $data['entry_min'] = $this->language->get('entry_min');
        $data['entry_max'] = $this->language->get('entry_max');
        $data['entry_send_email'] = $this->language->get('entry_send_email');
        $data['entry_type'] = $this->language->get('entry_type');
        $data['entry_fixed'] = $this->language->get('entry_fixed');
        $data['entry_use_credit'] = $this->language->get('entry_use_credit');

        $data['help_status'] = $this->language->get('help_status');
        $data['help_order_status'] = $this->language->get('help_order_status');
		$data['help_type_buy_credit'] = $this->language->get('help_type');
        $data['help_custom_subject'] = $this->language->get('help_custom_subject');
        $data['help_message_from_email'] = $this->language->get('help_message_from_email');
        $data['help_message_to_email'] = $this->language->get('help_message_to_email');
        $data['help_admin_subject'] = $this->language->get('help_admin_subject');
        $data['help_message_admin'] = $this->language->get('help_message_admin');
        $data['help_subject'] = $this->language->get('help_subject');
        $data['help_message'] = $this->language->get('help_message');
        $data['help_image'] = $this->language->get('help_image');
        $data['help_default'] = $this->language->get('help_default');
        $data['help_min'] = $this->language->get('help_min');
        $data['help_max'] = $this->language->get('help_max');
        $data['help_send_email'] = $this->language->get('help_send_email');
        $data['help_type'] = $this->language->get('help_type');
        $data['help_fixed'] = $this->language->get('help_fixed');
        $data['help_message_desc'] = $this->language->get('help_message_desc');
        $data['help_use_credit'] = $this->language->get('help_use_credit');
        
        $data['tab_settings'] = $this->language->get('tab_settings');
        $data['tab_email'] = $this->language->get('tab_email');

        $data['send_credit_default_msg'] = $this->language->get('send_credit_default_msg');

        $data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_save_stay'] = $this->language->get('button_save_stay');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['default'])) {
			$data['error_default'] = $this->error['default'];
		} else {
			$data['error_default'] = '';
		}

		if (isset($this->error['min'])) {
			$data['error_min'] = $this->error['min'];
		} else {
			$data['error_min'] = '';
		}

		if (isset($this->error['max'])) {
			$data['error_max'] = $this->error['max'];
		} else {
			$data['error_max'] = '';
		}

		if (isset($this->error['fixed'])) {
			$data['error_fixed'] = $this->error['fixed'];
		} else {
			$data['error_fixed'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/buy_credit', 'token=' . $this->session->data['token'], 'SSL')
		);

        $data['action'] = $this->url->link('extension/module/buy_credit', 'token=' . $this->session->data['token'], true);
        
        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		
        if (isset($this->request->post['buy_credit_status'])) {
			$data['buy_credit_status'] = $this->request->post['buy_credit_status'];
		} elseif ($this->config->get('buy_credit_status')) {
			$data['buy_credit_status'] = $this->config->get('buy_credit_status');		
		} else {
			$data['buy_credit_status'] = 0;
		}

		if (isset($this->request->post['buy_credit_send_email'])) {
			$data['buy_credit_send_email'] = $this->request->post['buy_credit_send_email'];
		} elseif ($this->config->get('buy_credit_send_email')) {
			$data['buy_credit_send_email'] = $this->config->get('buy_credit_send_email');		
		} else {
			$data['buy_credit_send_email'] = 0;
		}

		if (isset($this->request->post['buy_credit_default'])) {
			$data['buy_credit_default'] = $this->request->post['buy_credit_default'];
		} elseif ($this->config->get('buy_credit_default')) {
			$data['buy_credit_default'] = $this->config->get('buy_credit_default');
		} else {
			$data['buy_credit_default'] = '';
		}

		if (isset($this->request->post['buy_credit_min'])) {
			$data['buy_credit_min'] = $this->request->post['buy_credit_min'];
		} elseif ($this->config->get('buy_credit_min')) {
			$data['buy_credit_min'] = $this->config->get('buy_credit_min');		
		} else {
			$data['buy_credit_min'] = '';
		}

		if (isset($this->request->post['buy_credit_max'])) {
			$data['buy_credit_max'] = $this->request->post['buy_credit_max'];
		} elseif ($this->config->get('buy_credit_max')) {
			$data['buy_credit_max'] = $this->config->get('buy_credit_max');		
		} else {
			$data['buy_credit_max'] = '';
		}

		if (isset($this->request->post['buy_credit_fixed'])) {
			$data['buy_credit_fixed'] = $this->request->post['buy_credit_fixed'];
		} elseif ($this->config->get('buy_credit_fixed')) {
			$data['buy_credit_fixed'] = $this->config->get('buy_credit_fixed');		
		} else {
			$data['buy_credit_fixed'] = '';
		}

		if (isset($this->request->post['buy_credit_type'])) {
			$data['buy_credit_type'] = $this->request->post['buy_credit_type'];
		} elseif ($this->config->get('buy_credit_type')) {
			$data['buy_credit_type'] = $this->config->get('buy_credit_type');		
		} else {
			$data['buy_credit_type'] = 'free';
		}

		if (isset($this->request->post['buy_credit_order_status_id'])) {
			$data['buy_credit_order_status_id'] = $this->request->post['buy_credit_order_status_id'];
		} else {
			$data['buy_credit_order_status_id'] = $this->config->get('buy_credit_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();


		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $language) {
    		if (isset($this->request->post['buy_credit_email_subject_' . $language['language_id']])) {
    			$data['buy_credit_email_subject_' . $language['language_id']] = $this->request->post['buy_credit_email_subject_' . $language['language_id']];
            } else {
    			$data['buy_credit_email_subject_' . $language['language_id']] = $this->config->get('buy_credit_email_subject_' . $language['language_id']);
            }
 
    		if (isset($this->request->post['buy_credit_email_msg_' . $language['language_id']])) {
    			$data['buy_credit_email_msg_' . $language['language_id']] = $this->request->post['buy_credit_email_msg_' . $language['language_id']];
            } else {
    			$data['buy_credit_email_msg_' . $language['language_id']] = $this->config->get('buy_credit_email_msg_' . $language['language_id']);
            }
        }

		$this->load->model('tool/image');

		if (isset($this->request->post['buy_credit_image'])) {
			$data['buy_credit_image'] = $this->request->post['buy_credit_image'];
		} else {
			$data['buy_credit_image'] = $this->config->get('buy_credit_image');
		}

		if (isset($this->request->post['buy_credit_image']) && is_file(DIR_IMAGE . $this->request->post['buy_credit_image'])) {
			$data['buy_credit_thumb'] = $this->model_tool_image->resize($this->request->post['buy_credit_image'], 100, 100);
		} elseif ($this->config->get('buy_credit_image') && is_file(DIR_IMAGE . $this->config->get('buy_credit_image'))) {
			$data['buy_credit_thumb'] = $this->model_tool_image->resize($this->config->get('buy_credit_image'), 100, 100);
		} else {
			$data['buy_credit_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['buy_credit_use'])) {
			$data['buy_credit_use'] = $this->request->post['buy_credit_use'];
		} elseif ($this->config->get('buy_credit_use')) {
			$data['buy_credit_use'] = $this->config->get('buy_credit_use');		
		} else {
			$data['buy_credit_use'] = 'never';
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
            
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/buy_credit.tpl', $data));
	}
		
	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/buy_credit')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

        if ($this->request->post['buy_credit_type'] == 'free') {
    
    		if (empty($this->request->post['buy_credit_default'])) {
    			$this->error['default'] = $this->language->get('error_default');
    		} else if ($this->request->post['buy_credit_default'] > $this->request->post['buy_credit_max'] && $this->request->post['buy_credit_max'] !== '') {
    		    $this->error['default'] = $this->language->get('error_default_max');
            } else if ($this->request->post['buy_credit_default'] < $this->request->post['buy_credit_min'] && $this->request->post['buy_credit_min'] !== '') {
    		    $this->error['default'] = $this->language->get('error_default_min');
            }  
    
    		if ($this->request->post['buy_credit_min'] >= $this->request->post['buy_credit_max'] && $this->request->post['buy_credit_max'] !== '' && $this->request->post['buy_credit_min'] !== '') {
    			$this->error['min'] = $this->language->get('error_min');
    		}
    
    		if ($this->request->post['buy_credit_max'] <= $this->request->post['buy_credit_min'] && $this->request->post['buy_credit_min'] !== '' && $this->request->post['buy_credit_max'] !== '') {
    			$this->error['max'] = $this->language->get('error_max');
    		}
    
        }

		if (empty($this->request->post['buy_credit_type']) || (!preg_match('/^[0-9,]+$/', $this->request->post['buy_credit_fixed']) && $this->request->post['buy_credit_type'] !== 'free')) {
			$this->error['fixed'] = $this->language->get('error_fixed');
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

    public function install() {
	    $this->load->model('extension/module/buy_credit');
	    $this->model_extension_module_buy_credit->install();
    }
    
    public function uninstall() {
        $this->load->model('extension/module/buy_credit');
        $this->model_extension_module_buy_credit->uninstall();
    }

}