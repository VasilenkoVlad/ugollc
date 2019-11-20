<?php
class ControllerExtensionTotalCoupon extends Controller {
	public function index() {
		if ($this->config->get('coupon_status')) {
			$this->load->language('extension/total/coupon');

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_loading'] = $this->language->get('text_loading');

			$data['entry_coupon'] = $this->language->get('entry_coupon');

			$data['button_coupon'] = $this->language->get('button_coupon');

			if (isset($this->session->data['coupon'])) {
				$data['coupon'] = $this->session->data['coupon'];
			} else {
				$data['coupon'] = '';
			}

			return $this->load->view('extension/total/coupon', $data);
		}
	}

	public function coupon() {
		$this->load->language('extension/total/coupon');

		$json = array();

		$this->load->model('extension/total/coupon');

		if (isset($this->request->post['coupon'])) {
			$coupon = $this->request->post['coupon'];
		} else {
			$coupon = '';
		}

		$coupon_info = $this->model_extension_total_coupon->getCoupon($coupon);
		
                if (empty($this->request->post['coupon'])) {			
			$json['error'] = strtoupper($this->language->get('error_empty'));
                        unset($this->session->data['coupon']);
			$json['status'] = "invalid";			
		} elseif ($coupon_info['status'] == true) {			
			$this->session->data['coupon'] = $this->request->post['coupon'];
			$this->session->data['success'] = strtoupper($this->language->get('text_success'));
			$json['redirect'] = $this->url->link('checkout/cart');
			$json['status'] = "valid";
			$json['discount'] = $coupon_info['discount'];	
			$json['type']	= $coupon_info['type'];	
			$json['success'] = strtoupper($this->language->get('text_success'));
		} elseif ($coupon_info['status'] == false) {			
			$json['error'] = strtoupper($coupon_info['warning_text']);
			$json['status'] = "invalid";	
		}	

		if (isset($this->request->post['call_type'])) {
		   echo json_encode($json); die();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
