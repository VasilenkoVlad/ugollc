<?php
class ControllerCommonFooter extends Controller {
	public function index() {

			$this->load->config('dragndrop_position');

			$token_name = (version_compare(VERSION, '3') >= 0) ? 'user_token' : 'token';

			$data['dragndrop_save_url'] = $this->config->get('ddp_module_path') . '/position';
			$data['token_name'] = $token_name;
			$data['token_value'] = isset($this->session->data[$token_name]) ? $this->session->data[$token_name] : '';
			$data['page'] = isset($this->request->get['page']) ? $this->request->get['page'] : 0 ;
			$data['limit_admin'] = $this->config->get('config_admin_limit') ? $this->config->get('config_admin_limit') : (($this->config->get('config_limit_admin')) ? $this->config->get('config_limit_admin') : 0);
			
		$this->load->language('common/footer');

		$data['text_footer'] = $this->language->get('text_footer');

		if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), VERSION);
		} else {
			$data['text_version'] = '';
		}
		
		return $this->load->view('common/footer', $data);
	}
}
