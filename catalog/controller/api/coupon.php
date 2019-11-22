<?php
class ControllerApiCoupon extends Controller {
	public function index() {
		$this->load->language('api/coupon');

		// Delete past coupon in case there is an error
		unset($this->session->data['coupon']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('extension/total/coupon');

			if (isset($this->request->post['coupon'])) {
				$coupon = $this->request->post['coupon'];
			} else {
				$coupon = '';
			}

			$coupon_info = $this->model_extension_total_coupon->getCoupon($coupon);

			if ($coupon_info) {
				$this->session->data['coupon'] = $this->request->post['coupon'];

				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_coupon');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
        
        public function confirm() {
        $coupon_code = $this->request->post['coupon_code'];
        $order_id = $this->request->post['order_id'];
	$json = array();
        $this->load->model('checkout/api_coupon_confirm');
        $customer = $this->model_checkout_api_coupon_confirm->getCustomerId($order_id);
        $customer_id = $customer['customer_id']; 
        $coupon_info = $this->model_checkout_api_coupon_confirm->getCoupon($coupon_code);
        if($coupon_info){
            $order_total_id = $this->model_checkout_api_coupon_confirm->addApiOrderTotal($coupon_info,$order_id);
            $json['order_total_id'] = $order_total_id ;
            
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));         
        }
    }
}
