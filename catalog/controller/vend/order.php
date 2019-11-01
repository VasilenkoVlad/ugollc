<?php

class ControllerVendOrder extends Controller {

    public function __constract() {        
        $this->load->model('account/vend');
        $this->load->model('checkout/order');
    }

    public function orderSalesWebhook() {  
        $this->load->model('account/vend');
        //Getting new sale ids to check
        $vend_sale_data = $this->model_account_vend->get_vend_sale_data();
        //Check if new sale 
        $this->load->model('checkout/order');
        if($vend_sale_data->num_rows){
            for($i=0; $i < $vend_sale_data->num_rows; $i++ ){
                $sale_id = $vend_sale_data->rows[$i]['vend_sale_id'];
                echo $order_id = $vend_sale_data->rows[$i]['order_id'];
                $status = $vend_sale_data->rows[$i]['status'];
                if($sale_id != 0){
                    // Call library function & get sale details from Vend
                    $result = $this->vendsales->get_vend_sales_details($sale_id);
                    if($result['data']['status'] == 'VOIDED') {
                        $order_data['order_status_id'] = 7;
                        //Check if store credit used in cancelled order
                        $store_credit = $this->model_checkout_order->check_store_credit_order($order_id);
                        if($store_credit->num_rows > 0){
                            $amount = abs($store_credit->row['value']);
                            //Add transaction
                            $this->model_checkout_order->add_Transaction($order_id,$amount);
                            //$this->model_checkout_order->add_Transaction($order_id,"Credit", $store_credit['value']);
                        }
                    }elseif($result['data']['status'] == 'CLOSED' || $result['data']['status'] == 'ONACCOUNT_CLOSED' ){
                        $order_data['order_status_id'] = 5;
                    }elseif($result['data']['status'] == 'ONACCOUNT'){
                         $order_data['order_status_id'] = 2;
                    }
                    
                    if($result['data']['status'] != 'ONACCOUNT') {
                        //Edit order status & add order history
                        $this->model_checkout_order->editOrderForVend($order_id, $order_data);
                    }
                }else {
                    echo "Vend sale id = 0";
                }
                //Update check_status from Vend Sales Reciept table
                $this->model_account_vend->update_check_status($order_id,$result['data']['status']);
            }
        } else{
            echo "No new sales";
        }
    }
}
