<?php

class ControllerCartCartBaseAPI extends ApiController {
	private $type = 'module';
	private $name = 'optional_fee_discount';
	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			unset($this->session->data[$this->name]);
			$this->get();
		}
		else {
			$this->setOptions();
		 	//throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}


//==============================================================================
	// Ajax functions
	//==============================================================================
	public function setOptions() {

		if (empty($this->request->post)) return;
		
		foreach ($this->request->post as $key => $value) {
			$this->session->data[$this->name][$key] = $value;					
		}
		


	}

	public function redirect($url, $status = 302) {
		switch($url) {
			case 'checkout/cart': // Success

			break;
		}
	}


//==============================================================================
	// Private functions
	//==============================================================================
	private function getSettings() {
		$settings = array();
		$settings_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "' ORDER BY `key` ASC");
		
		foreach ($settings_query->rows as $setting) {
			$value = $setting['value'];
			if ($setting['serialized']) {
				$value = (version_compare(VERSION, '2.1', '<')) ? unserialize($setting['value']) : json_decode($setting['value'], true);
			}
			$split_key = preg_split('/_(\d+)_?/', str_replace($this->name . '_', '', $setting['key']), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			
			if (count($split_key) == 1) $settings[$split_key[0]] = $value;
			elseif (count($split_key) == 2) $settings[$split_key[0]][$split_key[1]] = $value;
			elseif (count($split_key) == 3) $settings[$split_key[0]][$split_key[1]][$split_key[2]] = $value;
			elseif (count($split_key) == 4) $settings[$split_key[0]][$split_key[1]][$split_key[2]][$split_key[3]] = $value;
			else 							$settings[$split_key[0]][$split_key[1]][$split_key[2]][$split_key[3]][$split_key[4]] = $value;
		}
		
		return $settings;
	}
	/**
	 * Resource methods
	 */

	public function get() {
		$dataold = parent::getInternalRouteData('checkout/cart');

		ApiException::evaluateErrors($dataold, true);

		$data['type'] = $this->type;
		$data['name'] = $this->name;
		
		$order_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'total' AND `code` = '" . $this->db->escape($this->name) . "'");
		if (!$order_total_query->num_rows) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "extension SET `type` = 'total', `code` = '" . $this->db->escape($this->name) . "'");
		}
		
		$data['session_data'] = (isset($this->session->data[$this->name])) ? $this->session->data[$this->name] : array();
		$data['store_id'] = $this->config->get('config_store_id');
		$data['language'] = $this->session->data['language'];
		$data['customer_group_id'] = (version_compare(VERSION, '2.0') < 0) ? (int)$this->customer->getCustomerGroupId() : (int)$this->customer->getGroupId();
		$data['currency'] = $this->session->data['currency'];
		$data['settings'] = $this->getSettings();
		
		$order_totals = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'total' ORDER BY `code` ASC")->rows;
		$sort_order = array();
		foreach ($order_totals as $key => $value) $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
		array_multisort($sort_order, SORT_ASC, $order_totals);
		
		$total_data = array();
		$order_total = 0;
		$taxes = $this->cart->getTaxes();
		
		foreach ($order_totals as $ot) {
                    if($ot['code'] != 'credit') {
			if (!$this->config->get($ot['code'] . '_status')) continue;
			$this->load->model('total/' . $ot['code']);
			
			if ($ot['code'] != $this->name) {
				$this->{'model_total_' . $ot['code']}->getTotal($total_data, $order_total, $taxes);
			} else {
				$this->{'model_total_' . $ot['code']}->getTotal($total_data, $order_total, $taxes, true);
				break;
			}
                    }    
		}
		
		$data['grouped_options'] = array();
		foreach ($total_data as $td) {
			if ($td['code'] != $this->name) continue;
			$data['grouped_options'][$td['heading']][] = $td;
		}
		if (!empty($data['grouped_options'])) {
			$cart = array('cart' => $this->getCart($dataold),'optional_fee_discount'=> $data);
		}else{
			$cart = array('cart' => $this->getCart($dataold));

		}

		$this->response->setOutput($cart);
	}

	/**
	 * Helper methods
	 */

	protected function getCart($data) {
		$reward_points_total = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$reward_points_total += $product['points'];
			}
		}	

		$cart = array(
			'products' 					=> isset($data['products']) ? $data['products'] : null,
			'vouchers' 					=> isset($data['vouchers']) ? $data['vouchers'] : null,
			'credits' 					=> isset($data['credits']) ? $data['credits'] : null,
                        'totals' 					=> isset($data['totals']) ? $data['totals'] : null,
			'weight' 					=> isset($data['weight']) ? $data['weight'] : null,
			'coupon_status' 			=> $this->config->get('coupon_status') == '1' ? true : false,
			'coupon' 					=> isset($data['coupon']['coupon']) && !empty($data['coupon']['coupon']) ? $data['coupon']['coupon'] : null,
			'voucher_status' 			=> $this->config->get('voucher_status') == '1' ? true : false,
			'voucher' 					=> isset($data['voucher']['voucher']) && !empty($data['voucher']['voucher']) ? $data['voucher']['voucher'] : null,
		        'credit_status' 			=> $this->config->get('credit_status') == '1' ? true : false,
			'reward' 					=> isset($data['reward']['reward']) && !empty($data['reward']['reward']) ? $data['reward']['reward'] : 0,
			'max_reward_points_to_use' 	=> $reward_points_total,
			'shipping_status' 			=> $this->cart->hasShipping(),
			'error_warning'				=> !isset($data['error_warning']) || $data['error_warning'] == '' ? null : $data['error_warning']
			);

                return $this->processCart($cart);
}

protected function processCart($cart) {
	if(isset($cart['products'])) {
		$cart['products'] = $this->processProducts($cart['products']);
	}
	
	if(isset($cart['totals'])) {
		$cart['totals'] = $this->processTotals($cart['totals']);
	}
	
	if(isset($cart['vouchers'])) {
		$cart['vouchers'] = $this->processVouchers($cart['vouchers']);
	}
        
        if(isset($cart['credits'])) {
		$cart['credits'] = $this->processCredits($cart['credits']);
	}

	return $cart;
}

protected function processProducts($products) {
	foreach ($products as &$product) {
		$product['thumb_image'] = $product['thumb'];
		$product['in_stock'] = $product['stock'];
		unset($product['stock']);
		unset($product['thumb']);
		unset($product['href']);
	}

	return $products;
}

protected function processVouchers($vouchers) {
	foreach($vouchers as &$voucher) {
		unset($voucher['remove']);
	}

	return $vouchers;
}

protected function processCredits($credits) {
	foreach($credits as &$credit) {
                unset($credit['remove']);
	}

	return $credits;
}

protected function processTotals($totals) {
	return $totals;
}

}

?>
