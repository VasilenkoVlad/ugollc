<?php
class ModelCheckoutBuyCredit extends Model {
	
	public function Confirm($order_id, $data, $order_status_id) {
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		if ($order_info) {
			$this->load->model('localisation/language');
		            
    		$language = new Language($order_info['language_code']);
    		$language->load($order_info['language_code']);
    		$language->load('account/buy_credit');

            $text_credit = sprintf($language->get('text_buy_credit'), $data['order_id']);
 
            $credit_comment = sprintf($language->get('text_credit_added'), $this->currency->format($data['amount'], $order_info['currency_code'], $order_info['currency_value']));

            $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '1', comment = '" . $this->db->escape($credit_comment) . "', date_added = NOW()");
 
            $this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$data['customer_id'] . "', order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($text_credit) . "', amount = '" . $data['amount'] . "', date_added = NOW()");                

            return $this->db->getLastId();

		}
    
    }

	public function ConfirmMail($order_id, $data) {
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		if ($order_info) {
			$this->load->model('localisation/language');
            
				$language = new Language($order_info['language_code']);
				$language->load($order_info['language_code']);
				$language->load('account/buy_credit');

				
                $firstname = $data['firstname'];
                $lastname = $data['lastname'];
                $email = $data['email'];
                $order_id = $data['order_id'];
                
                $amount = $this->currency->format($data['amount'], $order_info['currency_code'], $order_info['currency_value']);
                $total = $this->currency->format($this->getTransactionTotal($data['customer_id']), $order_info['currency_code'], $order_info['currency_value']);

                // HTML Mail
                if ($this->config->get('buy_credit_send_email')){

                    if ($this->config->get('buy_credit_email_subject_' . $this->config->get('config_language_id'))!=='') {
                        $subjectToCustomer = $this->config->get('buy_credit_email_subject_' . $this->config->get('config_language_id'));
                    } else {
                        $subjectToCustomer = sprintf($language->get('text_credit_subject'), $this->config->get('config_name'));    
                    }
                    
            		$messageToCustomer = html_entity_decode($this->config->get('buy_credit_email_msg_' . $this->config->get('config_language_id')), ENT_QUOTES, 'UTF-8');
            		$wordsTemplateCustomer = array("{firstname}", "{lastname}", "{email}", "{order_id}", "{amount}", "{total}",);
            		$wordsCustomer = array($firstname,$lastname,$email,$order_id,$amount,$total);					
            		$messageToCustomer = str_replace($wordsTemplateCustomer, $wordsCustomer, $messageToCustomer);
                               								
        			$mailToCustomer = new Mail();
                    $mailToCustomer->protocol = $this->config->get('config_mail_protocol');
        			$mailToCustomer->parameter = $this->config->get('config_mail_parameter');
        			$mailToCustomer->hostname = $this->config->get('config_smtp_host');
        			$mailToCustomer->username = $this->config->get('config_smtp_username');
        			$mailToCustomer->password = $this->config->get('config_smtp_password');
        			$mailToCustomer->port = $this->config->get('config_smtp_port');
        			$mailToCustomer->timeout = $this->config->get('config_smtp_timeout');
        			$mailToCustomer->setTo($data['email']);
        			$mailToCustomer->setFrom($this->config->get('config_email'));
        		    $mailToCustomer->setSender($this->config->get('config_name'));
        		    $mailToCustomer->setSubject(html_entity_decode($subjectToCustomer, ENT_QUOTES, 'UTF-8'));
    				$mailToCustomer->setHtml($messageToCustomer);
        			$mailToCustomer->send();

                }

			}
	}
	
    public function getTransactionTotal($customer_id) {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
		
		if ($query->num_rows) {
			return $query->row['total'];
		} else {
			return 0;
		}
    }

}
?>