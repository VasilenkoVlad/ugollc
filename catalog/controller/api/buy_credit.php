<?php
class ControllerApiBuyCredit extends Controller {
	public function add() {
		$this->load->language('api/buy_credit');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			// Add keys for missing post vars
			$keys = array(
				'credit_id',
				'description',
				'customer_id',
				'firstname',
				'lastname',
				'email',
				'amount-credit'    
			);

			foreach ($keys as $key) {
				if (!isset($this->request->post[$key])) {
					$this->request->post[$key] = '';
				}
			}

			if (isset($this->request->post['credit'])) {
				$this->session->data['credits'] = array();

				foreach ($this->request->post['credit'] as $credit) {
					if (isset($credit['customer_id']) && isset($credit['firstname']) && isset($credit['lastname']) && isset($credit['email']) && isset($credit['amount'])) {
                        $code = mt_rand();
                        $this->session->data['credits'][$code] = array(
                            'code'             => $code,
							'description'      => sprintf($this->language->get('text_desc'), $this->currency->format($this->currency->convert($credit['amount'], $this->session->data['currency'], $this->config->get('config_currency')), $this->session->data['currency'])),
							'customer_id'      => $credit['customer_id'],
							'firstname'       => $credit['firstname'],
							'lastname'        => $credit['lastname'],
							'email'            => $credit['email'],
							'amount'           => $this->currency->convert($credit['amount'], $this->session->data['currency'], $this->config->get('config_currency'))
						);
					}
				}

				$json['success'] = $this->language->get('text_cart');

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			} else {
				// Add a new voucher if set

                if (!$this->request->post['amount-credit'] || empty($this->request->post['amount-credit'])) {
        			$json['error']['amount-credit'] = $this->language->get('error_empty');
        		} else
        
                if (!$this->request->post['amount-credit'] || empty($this->request->post['amount-credit'])) {
        			$json['error']['amount-credit'] = $this->language->get('error_empty');
        		} elseif ($this->config->get('buy_credit_min') !== '' && $this->request->post['amount-credit'] < $this->config->get('buy_credit_min') && $this->config->get('buy_credit_type') == 'free') {
                    $json['error']['amount-credit'] = sprintf($this->language->get('error_min_amount'), $this->currency->format($this->config->get('buy_credit_min'), $this->session->data['currency']), $this->currency->format($this->config->get('buy_credit_min'), $this->session->data['currency']));
        		}
        
                if ($this->config->get('buy_credit_max') !== '' && $this->request->post['amount-credit'] > $this->config->get('buy_credit_max') && $this->config->get('buy_credit_type') == 'free') {
        		    $json['error']['amount-credit'] = sprintf($this->language->get('error_max_amount'), $this->currency->format($this->config->get('buy_credit_max'), $this->session->data['currency']), $this->currency->format($this->config->get('buy_credit_max'), $this->session->data['currency']));
                }


				if (!$json) {
                    $code = mt_rand();
                    
    				$this->session->data['credits'][$code] = array(
                        'code'             => $code,
                        'description'      => sprintf($this->language->get('text_desc'), $this->currency->format($this->currency->convert($this->request->post['amount-credit'], $this->session->data['currency'], $this->config->get('config_currency')), $this->session->data['currency'])),
    					'customer_id'      => $this->session->data['customer']['customer_id'],
    					'firstname'       => $this->session->data['customer']['firstname'],
    					'lastname'        => $this->session->data['customer']['lastname'],
    					'email'            => $this->session->data['customer']['email'],
                        'amount'           => $this->currency->convert($this->request->post['amount-credit'], $this->session->data['currency'], $this->config->get('config_currency'))
                                            
    				);

					$json['success'] = $this->language->get('text_cart');

					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				}
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

}
