<?php
class ModelExtensionTotalCredit extends Model {
	public function getTotal($total) {
		$this->load->language('extension/total/credit');


            $status = true;
            
            $total_credit =0;
            
            if (isset($this->session->data['credits'])) {
        		foreach ($this->session->data['credits'] as $credit) {
                      $total_credit += $credit['amount'];	
                }
            }
            
            if (!empty($this->session->data['credits'])) {

                if ($this->config->get('buy_credit_use') == 'never') {
                    $status = false;
                } elseif ($this->config->get('buy_credit_use') == 'cart'){
                    $status = true;
                }

            } else {

                $status = true;
            }
            
		$balance = $this->customer->getBalance();

		
            if ((float)$balance && $status) {
            
			
            if (isset($this->session->data['credits']) && $this->config->get('buy_credit_use') == 'never') {
                $credit = 0;
            } elseif (isset($this->session->data['credits']) && $this->config->get('buy_credit_use') == 'cart'){
                $credit = min($balance, (float)($total['total'] - $total_credit));
            } else {
                $credit = min($balance, $total['total']);
            }
            

			if ($credit > 0) {
				$total['totals'][] = array(
					'code'       => 'credit',
					'title'      => $this->language->get('text_credit'),
					'value'      => -$credit,
					'sort_order' => $this->config->get('credit_sort_order')
				);
                                if(!isset($this->request->get['api_call']) || $this->request->get['api_call'] != 1) {
                                            $total -= $credit;
                                }
			}
		}
	}

	public function confirm($order_info, $order_total) {
		$this->load->language('extension/total/credit');

		if ($order_info['customer_id']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$order_info['customer_id'] . "', order_id = '" . (int)$order_info['order_id'] . "', description = '" . $this->db->escape(sprintf($this->language->get('text_order_id'), (int)$order_info['order_id'])) . "', amount = '" . (float)$order_total['value'] . "', date_added = NOW()");
		}
	}

	public function unconfirm($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");
	}
}