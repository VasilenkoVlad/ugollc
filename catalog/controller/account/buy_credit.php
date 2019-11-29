<?php 
class ControllerAccountBuyCredit extends Controller { 
	private $error = array();

	public function index() {
        $this->load->language('account/buy_credit');

	$this->document->setTitle($this->language->get('heading_title'));

        if (!$this->config->get('buy_credit_status')) {
            
            $this->response->redirect($this->url->link('error/not_found', '', 'SSL'));
    	
        } else if (!$this->customer->isLogged()) {

            $this->session->data['redirect'] = $this->url->link('account/buy_credit', '', 'SSL');

            $this->session->data['error'] = $this->language->get('error_logged');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
    	} else if ($this->cart->hasProducts()) {

            $this->session->data['redirect'] = $this->url->link('account/buy_credit', '', 'SSL');

            $this->session->data['error'] = $this->language->get('error_cart');

            $this->response->redirect($this->url->link('checkout/cart', '', 'SSL'));
    	} 

		if (!isset($this->session->data['credits'])) {
			$this->session->data['credits'] = array();
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->session->data['credits'][mt_rand()] = array(
				'description'      => sprintf($this->language->get('text_desc'), $this->currency->format($this->currency->convert($this->request->post['amount'], $this->session->data['currency'], $this->config->get('config_currency')), $this->session->data['currency'])),
                'customer_id'      => $this->customer->getId(),
				'firstname'        => $this->customer->getFirstname(),
				'lastname'         => $this->customer->getLastname(),
				'email'            => $this->customer->getEmail(),
				'amount'           => $this->currency->convert($this->request->post['amount'], $this->session->data['currency'], $this->config->get('config_currency'))
			);

            $this->response->redirect($this->url->link('account/buy_credit/success'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/buy_credit', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_description'] = $this->language->get('text_description');
		$data['text_agree'] = $this->language->get('text_agree');

		$data['entry_amount'] = $this->language->get('entry_amount');
        $data['entry_min'] = ($this->config->get('buy_credit_min') ? sprintf($this->language->get('entry_min'), $this->currency->format($this->config->get('buy_credit_min'), $this->session->data['currency'])): '');
        $data['entry_max'] = ($this->config->get('buy_credit_max') ? sprintf($this->language->get('entry_max'), $this->currency->format($this->config->get('buy_credit_max'), $this->session->data['currency'])): '');
               
		$data['button_continue'] = $this->language->get('button_continue');
        $data['button_shopping'] = $this->language->get('button_shopping');
        $data['button_buy'] = $this->language->get('button_buy');

        if ($this->config->get('buy_credit_type') == 'free' && ($this->config->get('buy_credit_min') !== '' || $this->config->get('buy_credit_max') !== '')) { 
            $data['help_amount'] = '';

            if ($this->config->get('buy_credit_min') !== '') {
                $data['help_amount'] .= ($this->config->get('buy_credit_min') ? sprintf($this->language->get('help_min'), $this->currency->format($this->config->get('buy_credit_min'), $this->session->data['currency'])): '');
            }   
            
            $data['help_amount'] .= (($this->config->get('buy_credit_min') && $this->config->get('buy_credit_max')) ? "<br/>" : "");
            
            if ($this->config->get('buy_credit_max') !== '') {
                $data['help_amount'] .= ($this->config->get('buy_credit_max') ? sprintf($this->language->get('help_max'), $this->currency->format($this->config->get('buy_credit_max'), $this->session->data['currency'])): '');
            }
        
        } else {
            $data['help_amount'] = '';
        }

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['amount'])) {
			$data['error_amount'] = $this->error['amount'];
		} else {
			$data['error_amount'] = '';
		}

		$data['action'] = $this->url->link('account/buy_credit', '', 'SSL');
        $data['continue'] = $this->url->link('common/home', '', 'SSL');

		if (isset($this->request->post['amount'])) {
			$data['amount'] = $this->request->post['amount'];
		} else {
			$data['amount'] = $this->currency->format($this->config->get('buy_credit_default'), $this->config->get('config_currency'), false, false);
		}

		if (isset($this->request->post['agree'])) {
			$data['agree'] = $this->request->post['agree'];
		} else {
			$data['agree'] = false;
		}	
        
        $data['buy_credit_type'] = $this->config->get('buy_credit_type');
        
        $amounts = explode(',', $this->config->get('buy_credit_fixed'));
        
        foreach ($amounts as $amount) {
            $data['amounts'][] = array(
				'price'           => $this->currency->format($amount, $this->session->data['currency'], '1'),
                'amount'          => $amount
			);
        }

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		$this->response->setOutput($this->load->view('account/buy_credit', $data));
	}

	public function success() {
		$this->language->load('account/buy_credit');

		$this->document->setTitle($this->language->get('heading_title')); 

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/buy_credit')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_message'] = $this->language->get('text_message');

		$data['button_continue'] = $this->language->get('button_continue');

		$data['continue'] = $this->url->link('checkout/cart');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}

	protected function validate() {
	   
        if (!$this->currency->convert($this->request->post['amount'], $this->session->data['currency'], $this->config->get('config_currency'))) {
			$this->error['amount'] = $this->language->get('error_empty');
		} else {
            if ($this->config->get('buy_credit_min') !== '' && $this->currency->convert($this->request->post['amount'], $this->session->data['currency'], $this->config->get('config_currency')) < $this->config->get('buy_credit_min') && $this->config->get('buy_credit_type') == 'free') {
                $this->error['amount'] = sprintf($this->language->get('error_min_amount'), $this->currency->format($this->config->get('buy_credit_min'), $this->session->data['currency']), $this->currency->format($this->config->get('buy_credit_min'), $this->session->data['currency']));
    		}
    
            if ($this->config->get('buy_credit_max') !== '' && $this->currency->convert($this->request->post['amount'], $this->session->data['currency'], $this->config->get('config_currency')) > $this->config->get('buy_credit_max') && $this->config->get('buy_credit_type') == 'free') {
    		    $this->error['amount'] = sprintf($this->language->get('error_max_amount'), $this->currency->format($this->config->get('buy_credit_max'), $this->session->data['currency']), $this->currency->format($this->config->get('buy_credit_max'), $this->session->data['currency']));
            }
		}

		if (!isset($this->request->post['agree'])) {
			$this->error['warning'] = $this->language->get('error_agree');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}