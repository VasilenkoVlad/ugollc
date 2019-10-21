<?php 
class ControllerVendEntry extends Controller {
	public function index() 
	{
		//set the vend POS library
		$vend = $this->registry->set('vendpos', new Vendpos ($this->registry));

		if(isset($this->request->get['order_id']))
		{

			$order_id = $this->request->get['order_id'];

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
                                        //$sale_id	= $this->vendpos->vend_automation($order_data);
					$vend_data 	= $this->vendpos->vend_automation($order_data);
                                        $sale_id = $vend_data['sale_id'];
                                        $status = $vend_data['vend_order_status'];

					//prepare sales array
					$vend_sale_array	= array(
						'id'			=> $oc_vend_id,
						'order_id'		=> $order_id,
						'vend_sale_id'	=> $sale_id,
						//'status'		=> 'Processed'
					);

					if($sale_id && $status == 'ONACCOUNT')
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