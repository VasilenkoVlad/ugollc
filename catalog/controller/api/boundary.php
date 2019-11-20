<?php
class ControllerApiBoundary extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('api/boundary');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/module/boundary');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_extension_module_boundry->editSetting('store_base', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/module/boundary', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['store_circle_radius'] = $this->language->get('store_circle_radius');
		$data['store_latitude'] = $this->language->get('store_latitude');
		$data['store_longitude'] = $this->language->get('store_longitude');


		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_data_feed'] = $this->language->get('entry_data_feed');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_feed'),
			'href' => $this->url->link('extension/boundry', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('boundry/store_base', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('boundry/store_base', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/boundry', 'token=' . $this->session->data['token'], 'SSL');
		//die("dshksd");
                $data['boundry_details'] = $this->model_extension_module_boundary->getBoundaryDetails();
                /*echo "<pre>";
		print_r($data);
		echo "<pre>";
		die;*/

		$data['data_feed'] = HTTP_CATALOG . 'index.php?route=boundry/store_base';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('boundry/store_base.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'boundry/store_base')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}