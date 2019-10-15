<?php
class ControllerCustomSettingsDeliveryFeeBamaCash extends Controller {
	private $error = array();

	public function index() {
            
		$this->load->language('custom_settings/delivery_fee');

		$this->document->setTitle($this->language->get('bama_cash_heading_title'));

		$this->load->model('custom_settings/delivery_fee');

		$this->getList();
	}

	public function edit() {
            
		$this->load->language('custom_settings/delivery_fee');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('custom_settings/delivery_fee');
                 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
                    
                    $id = $this->request->get['delivery_fee_id'];
                    
                    $this->model_custom_settings_delivery_fee->editDeliveryFee($id, $this->request->post);

                    $this->session->data['success'] = $this->language->get('text_success');

                    $url = '';

                    if (isset($this->request->get['page'])) {
                            $url .= '&page=' . $this->request->get['page'];
                    }
                    
                    $this->response->redirect($this->url->link('custom_settings/delivery_fee/bama_cashy', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		
                }
                   
	}
        
        public function edit_range_fee() {
            
		$this->load->language('custom_settings/delivery_fee');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('custom_settings/delivery_fee');
                
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
                    
                    $id = $this->request->get['delivery_range_fee_id'];
                    
                    $this->model_custom_settings_delivery_fee->editDeliveryRangeFee($id, $this->request->post);

                    $this->session->data['success'] = $this->language->get('text_success');

                    $url = '';

                    if (isset($this->request->get['page'])) {
                            $url .= '&page=' . $this->request->get['page'];
                    }
                    
                    $this->response->redirect($this->url->link('custom_settings/delivery_fee/bama_cash', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                }
	}
        
        public function add() {
            
		$this->load->language('custom_settings/delivery_fee');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('custom_settings/delivery_fee');
                 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
                        $this->model_custom_settings_delivery_fee->addDeliveryFee($this->request->post);

                        $this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
                        
                        $this->response->redirect($this->url->link('custom_settings/delivery_fee/bama_cash', 'token=' . $this->session->data['token'] . $url, 'SSL'));

                }
                   
	}
        
        public function addRangeFee() {
                
		$this->load->language('custom_settings/delivery_fee');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('custom_settings/delivery_fee');
                
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
    
                        $this->model_custom_settings_delivery_fee->addDeliveryRangeFee($this->request->post);

                        $this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
                        
                        $this->response->redirect($this->url->link('custom_settings/delivery_fee/bama_cash', 'token=' . $this->session->data['token'] . $url, 'SSL'));

                }
                   
	}
        
        public function delete() {
            
		$this->load->model('custom_settings/delivery_fee');
                 
                $id = $this->request->get['delivery_fee_id'];
	        
                $this->model_custom_settings_delivery_fee->deleteDeliveryFee($id);
                
                $this->session->data['success'] = "Delivery Fee deleted";

                $this->response->redirect($this->url->link('custom_settings/delivery_fee/bama_cash', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                
                $this->getList();
	}
        
        public function delete_range_fee() {
            
		$this->load->model('custom_settings/delivery_fee');
                 
                echo $id = $this->request->get['delivery_range_fee_id']; 
	        
                $this->model_custom_settings_delivery_fee->deleteDeliveryRangeFee($id);
                
                $this->session->data['success'] = "Delivery Fee deleted";

                $this->response->redirect($this->url->link('custom_settings/delivery_fee/bama_cash', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                
                $this->getList();
	}

	protected function getList(){
            
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('bama_cash_heading_title'),
			'href' => $this->url->link('custom_settings/delivery_fee/bama_cash', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['payments'] = array();

		$filter_data = array(
                        'payment_type' => 3,
                        'delivery_fee_type' => 'Cart Amount',
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$count_cod_fee_total = $this->model_custom_settings_delivery_fee->getTotalBcashFees();

		$results = $this->model_custom_settings_delivery_fee->getFees($filter_data);
                
		foreach ($results as $result) {
			$data['cod'][] = array(
                                'delivery_fee_id' => $result['delivery_fee_id'],
				'payment_method_id' => $result['payment_method_id'],
                                'cart_amount_range_criteria' => $result['cart_amount_range_criteria'],
                                'cart_amount1' => $result['cart_amount1'],
                                'cart_amount2' => $result['cart_amount2'],
                                'fee_type' => $result['fee_type'],
                                'basic_fee' => $result['basic_fee'],
                                'edit'      => $this->url->link('custom_settings/delivery_fee/bama_cash/edit', 'token=' . $this->session->data['token'] . '&delivery_fee_id=' . $result['delivery_fee_id'] . $url, 'SSL'),
				'edit_detail'      => $this->url->link('custom_settings/delivery_fee/bama_cash/edit_detail', 'token=' . $this->session->data['token'] . '&delivery_fee_id=' . $result['delivery_fee_id'] . $url, 'SSL'),
                                'delete'    => $this->url->link('custom_settings/delivery_fee/bama_cash/delete', 'token=' . $this->session->data['token'] . '&delivery_fee_id=' . $result['delivery_fee_id'] . $url, 'SSL'),
			);
		}
                 
                //Get range delivey fee from database
		$range_delivey_fee_list = $this->model_custom_settings_delivery_fee->getRangeFee(3);
                foreach ($range_delivey_fee_list as $range_fee) {
			$data['delivery_range_fee'][] = array(
                                'delivery_range_fee_id' => $range_fee['delivery_range_fee_id'],
				'payment_type_id' => $range_fee['payment_type_id'],
                                'range_type' => $range_fee['range_type'],
                                'fee'     => $range_fee['fee'],
                                'range_1' => $range_fee['range_1'],
                                'range_2' => $range_fee['range_2'],
                                'edit_range'      => $this->url->link('custom_settings/delivery_fee/bama_cash/edit_range_fee', 'token=' . $this->session->data['token'] . '&delivery_range_fee_id=' . $range_fee['delivery_range_fee_id'] . $url, 'SSL'),
                                'delete_range' => $this->url->link('custom_settings/delivery_fee/bama_cash/delete_range_fee', 'token=' . $this->session->data['token'] . '&delivery_range_fee_id=' . $range_fee['delivery_range_fee_id'] . $url, 'SSL'),
                        );
		}
                $data['add'] = $this->url->link('custom_settings/delivery_fee/bama_cash/add', 'token=' . $this->session->data['token'] , 'SSL');
                $data['add_range'] = $this->url->link('custom_settings/delivery_fee/bama_cash/addRangeFee', 'token=' . $this->session->data['token'] , 'SSL');
                $data['column_range_1'] =  $this->language->get('column_range_1');
                $data['column_range_2'] =  $this->language->get('column_range_2');
                $data['range_type']     =  $this->language->get('column_cart_amount_range_type');      
		$data['column_delivery_fee'] = $this->language->get('column_delivery_fee');
		$data['heading_title'] = $this->language->get('bama_cash_heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
                $data['text_percent'] = $this->language->get('text_percent');
                $data['text_fix_amount'] = $this->language->get('text_fix_amount');
                $data['column_delivery_fee'] = $this->language->get('column_delivery_fee');
                $data['column_cart_amount_range_type'] = $this->language->get('column_cart_amount_range_type');
                $data['column_cart_amount_range_1'] = $this->language->get('column_cart_amount_range_1');
                $data['column_cart_amount_range_2'] = $this->language->get('column_cart_amount_range_2');
                $data['column_fee_type'] = $this->language->get('column_fee_type');
		$data['column_action'] = $this->language->get('column_action');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		}else if(isset($this->error['cart_amount1'])) {
			$data['error_warning'] = $this->error['cart_amount1'];
		}else if (isset($this->error['cart_amount2'])) {
                        $data['error_warning'] = $this->error['cart_amount2'];
		}else if (isset($this->error['invalid_cart_amount2'])) {
			$data['error_warning'] = $this->error['invalid_cart_amount2'];
		}else if (isset($this->error['basic_delivery_fee'])) {
			$data['error_warning'] = $this->error['basic_delivery_fee'];
                }else{
                    $data['error_warning'] = '';
                }

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
                
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$url = '';

		$pagination = new Pagination();
		$pagination->total = $count_cod_fee_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('custom_settings/delivery_fee', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($count_cod_fee_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($count_cod_fee_total - $this->config->get('config_limit_admin'))) ? $count_cod_fee_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $count_cod_fee_total, ceil($count_cod_fee_total / $this->config->get('config_limit_admin')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('custom_settings/delivery_fee/bama_cash/bama_cash_fee_list.tpl', $data));
	}

	protected function getDetailForm() {
            
                $url = '';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('bama_cash_heading_title'),
			'href' => $this->url->link('custom_settings/delivery_fee/bama_cash', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
                
                $data['breadcrumbs'][] = array(
			'text' => $this->language->get('bama_cash_detail_heading_title'),
			'href' => $this->url->link('custom_settings/delivery_fee/bama_cash/edit_detail', 'token=' . $this->session->data['token'] . '&delivery_fee_id=' . $this->request->get['delivery_fee_id']  . $url, 'SSL')
		);
		
                //Get speed delivey fee from database
		$speedy_fee_list = $this->model_custom_settings_delivery_fee->getSpeedyFee($this->request->get['delivery_fee_id']);
                foreach ($speedy_fee_list as $speed_fee) {
			$data['speedy_delivery_fee'][] = array(
                                'speedy_delivery_fee_id' => $speed_fee['speedy_delivery_fee_id'],
				'delivery_fee_id' => $speed_fee['delivery_fee_id'],
                                'fee' => $speed_fee['fee'],
                                'time_slot' => $speed_fee['time_slot'],
                                'edit'      => $this->url->link('custom_settings/delivery_fee/bama_cash/edit_detail', 'token=' . $this->session->data['token'] . '&delivery_fee_id='.$this->request->get['delivery_fee_id']. $url, 'SSL'),
			);
		}
		$data['heading_title'] = $this->language->get('bama_cash_detail_heading_title');
		$data['text_speedy_delivery'] = $this->language->get('text_speedy_delivery');
		$data['entry_speedy_delivery'] = $this->language->get('entry_speedy_delivery');
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('custom_settings/delivery_fee/bama_cash/bama_cash_fee_detail.tpl', $data));
	}

	protected function validateForm() {
		
                if (!$this->user->hasPermission('modify', 'custom_settings/delivery_fee')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
                

		if ((utf8_strlen($this->request->post['cart_amount_1']) < 1) || (utf8_strlen($this->request->post['cart_amount_1']) > 64)) {
			$this->error['cart_amount1'] = $this->language->get('error_cart_amount1');
                }
                
                if (($this->request->post['range_type'] == 'below' || $this->request->post['range_type'] == 'above') && $this->request->post['cart_amount_2'] != '') {
                    $this->error['invalid_cart_amount2'] = $this->language->get('error_invalid_cart_amount2');
                }
                
                if ($this->request->post['range_type'] == 'in_between' && $this->request->post['cart_amount_2'] == '') {
			$this->error['cart_amount2'] = $this->language->get('error_cart_amount2');
                }
                
                if ((utf8_strlen($this->request->post['basic_fee']) < 1) || (utf8_strlen($this->request->post['basic_fee']) > 64)) {
			$this->error['basic_delivery_fee'] = $this->language->get('error_basic_delivery_fee');
                }
                
                return !$this->error;
	}
        
        public function edit_detail() {
            
		$this->load->language('custom_settings/delivery_fee');

		$this->document->setTitle($this->language->get('bama_cash_detail_heading_title'));

		$this->load->model('custom_settings/delivery_fee');
                
                $id = $this->request->get['delivery_fee_id'];
                 
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
                    
                    
                    $this->model_custom_settings_delivery_fee->editSpeedyFee($this->request->post['Speedy_delivery_id'], $this->request->post);

                    $this->session->data['success'] = $this->language->get('text_success');

                    $url = '';

                    if (isset($this->request->get['page'])) {
                            $url .= '&page=' . $this->request->get['page'];
                    }
                    
                }
                $this->getDetailForm();      
	}
}