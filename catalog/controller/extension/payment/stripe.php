<?php
class ControllerExtensionPaymentStripe extends Controller {
	public function index() {
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['text_loading'] = $this->language->get('text_loading');

		$data['continue'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/stripe.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/extension/payment/stripe.tpl', $data);
		} else {
			return $this->load->view('default/template/extension/payment/stripe.tpl', $data);
		}
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'stripe') {
			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('stripe_order_status_id'));
		}
	}
}