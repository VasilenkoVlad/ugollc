<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {
		$this->load->language('checkout/success');

		if (isset($this->session->data['order_id'])) {

			//generate vend receipt
			$this->generate_vend_receipt($this->session->data['order_id']);

			$this->cart->clear();

			// Add to activity log
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			if ($this->customer->isLogged()) {
				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
					'order_id'    => $this->session->data['order_id']
				);

				$this->model_account_activity->addActivity('order_account', $activity_data);
			} else {
				$activity_data = array(
					'name'     => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
					'order_id' => $this->session->data['order_id']
				);

				$this->model_account_activity->addActivity('order_guest', $activity_data);
			}
                }       

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
                        unset($this->session->data['credits']);

            unset($this->session->data['credits']);
            
			unset($this->session->data['totals']);
                    }

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}

		$data['button_continue'] = $this->language->get('button_continue');

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));		
	}

	public function generate_vend_receipt($order_id=0) 
	{
		//set the vend POS library
		$vend = $this->registry->set('vendpos', new Vendpos ($this->registry));

		if($order_id)
		{
			//get order related data
			$this->load->model('account/vend');
			$order_data 	= $this->model_account_vend->getOrderDetailsForVend($order_id);
			if($order_data)
			{
				//insert status as pending
				//prepare sales array
				$vend_sale_array	= array(
					'order_id'	=> $order_id,
					'status'	=> 'Pending'
				);

				$oc_vend_id	= $this->model_account_vend->insert_vend_sale_into_opencart($vend_sale_array);
				
				if($oc_vend_id)
				{
					//call to vend POS API
                                        $vend_data 	= $this->vendpos->vend_automation($order_data);
                                        $sale_id = $vend_data['sale_id'];
                                        $status = $vend_data['vend_order_status'];

					//prepare sales array
					$vend_sale_array	= array(
						'id'			=> $oc_vend_id,
						'order_id'		=> $order_id,
						'vend_sale_id'	=> $sale_id,
					);

					if($sale_id && $status=='ONACCOUNT')
					{
                                            //set status as SAVED for UGO Credit
                                            $vend_sale_array['status']	= 'Processed';
                                        }else if($sale_id && $status == 'CLOSED') {
                                            //set status as processed
                                            $vend_sale_array['status']	= 'Processed';
					}
					else
					{
						//set status as Unprocessed
						$vend_sale_array['status']	= 'Unprocessed';
					}
                                        
                                        $vend_sale_array['vend_status']	= $status;

					//update in database
					$oc_vend_status	= $this->model_account_vend->update_vend_sale_into_opencart($vend_sale_array);

					if($oc_vend_status)
					{
						//set log contents
						$log_contents	= 'Updated status for order id: '.$order_id.' in opencart.';

						//write to log
						$this->vendpos->log_content($log_contents);
					}
					else
					{
						//set log contents
						$log_contents	= 'Problem in updating the status for order id: '.$order_id.' in opencart.';

						//write to log if fails
						$this->vendpos->log_content($log_contents);
					}
				}
				else
				{
					//set log contents
					$log_contents	= 'Not able to insert order id in vend sales summary table.';

					//write to log if fails
					$this->vendpos->log_content($log_contents);
				}
			}
			else
			{
				//set log contents
				$log_contents	= 'Failed on success page because the order data not found.';

				//write to log if fails
				$this->vendpos->log_content($log_contents);
			}
		}
		else
		{
			//set log contents
			$log_contents	= 'Order Id not provided for making entry in Vend POS';

			//write to log if fails
			$this->vendpos->log_content($log_contents);
		}
	}
}