<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description vend api library *
 * @author Codaemon Softwares *
 */
class Vendpos {
    /*********************** DEV DETAILS  START *********************************************************/
    // private $apiurl             = 'https://ecommercetest.vendhq.com/api/'; // api url of vend store
    // private $authorization      = 'Bearer Kh7UKpuhyOjFA0uMfJPdI_kZ5jghdFv01sRRNFJV'; //authorize token
    // private $user_id            = '0af7b240-abc5-11e8-eddc-3f0995001ca3'; //user id in Vend
    // private $tax_id             = '0af7b240-abff-11e8-eddc-495cb7a539e8';
    // private $tax_rate           = 0.09;
    // private $delivery_fee_id    = '0af7b240-abff-11e8-eddc-47a5244423a0';
    // private $delivery_fee       = 2.99;
    // private $low_order_fee_id   = '0af7b240-abff-11e8-eddc-47a544879d23';
    // private $low_order_fee      = 2.50;
    // private $cod_payment_id     = '0af7b240-abc5-11e8-eddc-3f0995094712';
    // private $credit_payment_id  = '0af7b240-abc5-11e8-eddc-3f099509702c';
    // private $paypal_payment_id  = '0af7b240-abff-11e8-eddc-495e07ffd2d2';
    // private $stripe_payment_id  = '0af7b240-abff-11e8-eddc-495e0e25e28a';
    // private $cash_tip_id        = '0af7b240-abff-11e8-eddc-47a47e5cf935';
    // private $cash_tip_price     = 1.00;
    // private $credit_tip_id      = '0af7b240-abff-11e8-eddc-47a525b4fc9f';
    // private $credit_tip_price   = 1.00;
    // private $bama_payment_id    = '0af7b240-abff-11e8-eddc-495de78d8ea8';
    // private $driver_tip_name    = 'UGODRIVERTIP';
    /*********************** DEV DETAILS END *********************************************************/
    /*********************** LIVE DETAILS START *********************************************************/
    private $apiurl             = 'https://ugollc.vendhq.com/api/'; // api url of vend store
    private $authorization      = 'Bearer 6yQxsfiYriDXYnqCD6mTea_K09bpdLtmiDhTt6NL'; //authorize token
    private $user_id            = 'e52b2846-e9be-11e5-f98b-7c25ab397c3f'; //user id in Vend
    private $tax_id             = '064dce89-c7ae-11e5-ec2a-bbc871b8ba3e';
    private $tax_rate           = 0.09;
    private $delivery_fee_id    = '064dce89-c7ae-11e5-ec2a-bc0173206f98';
    private $delivery_fee       = 3.99;
    private $speedy_fee_id      = '2d1f29c9-7ecf-baff-df68-b0707deed533';
    private $speedy_fee         = 0.00;
    private $distance_fee       = 0.00;
    private $distance_fee_id    = 'a514e106-d7bf-8f1e-88db-23a434c5f63e';
    private $low_order_fee_id   = '06e08a30-eeae-11e7-ec24-636e4726c872';
    private $low_order_fee      = 2.79;
    private $cod_payment_id     = '064dce89-c7ae-11e5-ec2a-b95d1d822208';
    private $credit_payment_id  = 'e52b2846-e9be-11e5-f98b-7c25ab38019e';
    private $paypal_payment_id  = '022894d1-fdae-11e8-e6e7-431fd5a712ab';
    private $stripe_payment_id  = '022894d1-fdae-11e8-e6e7-43200b7d33ae';
    private $cash_tip_id        = '061992b3-760b-f7f5-7b27-ea77bfa66ae1';
    private $cash_tip_price     = 1.00;
    private $credit_tip_id      = '064dce89-c7ae-11e5-ec2a-be3264b64213';
    private $credit_tip_price   = 1.00;
    private $ugo_credit_id      = '2aac6c2a-a1b5-ba56-6c24-c8c3dded4329';
    private $store_credit_id    = '3c8ceb96-b49e-55b9-966d-921e538341fc'; 
    private $bama_payment_id    = '064dce89-c7ae-11e5-ec2a-bd6e30523564';
    private $dd_payment_id      = '54d286ce-0dd3-4955-942d-65be1047cc8c';
    private $driver_tip_name    = 'UGODRIVERTIP';
    /*********************** LIVE DETAILS END *********************************************************/

    public function __construct($registry) {
        $this->config = $registry->get('config');
        $this->customer = $registry->get('customer');
        $this->session = $registry->get('session');
    }

    /****************************************************
    function to process orders from opnecart to Vend POS
    ******************************************************/
    public function vend_automation($order_data)
    {
        $vend_sale_id   = 0;

        //check customer present or not if not then insert the customer
        $vend_customer_id   = $this->get_vend_customer_id($order_data);

        //check product present or not if not then insert the product
        $vend_product_ids   = $this->get_vend_product_id($order_data);
        
        $vend_data = array();
        
        if($vend_customer_id && $vend_product_ids)
        {
            //insert sales into vend
            $response = $this->insert_vend_sales($vend_customer_id,$vend_product_ids,$order_data);

            if(isset($response['register_sale']['id']))
            {
                //set log contents
                $log_contents   = 'Sales record created in VendPOS for the order id '.$order_data['order_id'];
                
                //write to log if fails
                $this->log_content($log_contents);  

                $vend_data['sale_id']   = $response['register_sale']['id'];
                $vend_data['vend_order_status'] = $response['register_sale']['status'];
            }
            else
            {
                //set log contents
                $log_contents   = 'Sales record not able to create in VendPOS for the order id '.$order_data['order_id'];

                //write to log if fails
                $this->log_content($log_contents);   
            }
        }
        else
        {
            //set log contents
            $log_contents   = 'Failed to create the sales record because both the customer and product ids are not found in VendPOS for the order id '.$order_data['order_id'];

            //write to log if fails
            $this->log_content($log_contents);
        }

        //return $vend_sale_id;
        return $vend_data;
    }

    /***************************************************************
    function to check customer present or not if not then insert it
    ****************************************************************/
    public function get_vend_customer_id($order_data=array())
    {   
        $vend_customer_id = 0;

        if($order_data)
        {
            //set api url and method
            $curl_data = array();
            $curl_data['api_parameter']   = "customers/email/".$order_data['email'];
            $curl_data['api_request']     = 'GET';
            
            //call Vend API
            $response   = $this->vend_api_curl_request($curl_data,$order_data);
            
            //check customer present or not
            if(isset($response['customers']['0']['id'])) //if customer is present
            {
                $vend_customer_id   = $response['customers']['0']['id'];

                //create customer array
                $customer_array = $this->create_customer_array($order_data);

                //create array to update physical address
                $customer_array = $this->create_physical_address($order_data,$customer_array);
                
                //create array to update postal address
                $customer_array = $this->create_postal_address($order_data,$customer_array);
                
                //prepare url segments for inserting a customer
                $curl_data['api_parameter']   = "2.0/customers/".$vend_customer_id;
                $curl_data['api_request']     = 'PUT';
                
                //call Vend API to insert the customer
                $response   = $this->vend_api_curl_request($curl_data,$customer_array);
                
                if(isset($response['data']['id']))
                {
                    //set log contents
                    $log_contents   = 'Success to update the customer address in Vend POS for order Id : '.$order_data['order_id'];

                    //write to log
                    $this->log_content($log_contents);
                }
                else
                {
                    //set log contents
                    $log_contents   = 'Failed to update the customer address in Vend POS for order Id : '.$order_data['order_id'];

                    //write to log if fails
                    $this->log_content($log_contents);
                }
            }
            else //if not present then insert a new customer
            {
                //create customer array
                $customer_array = $this->create_customer_array($order_data);

                //create array for physical address
                $customer_array = $this->create_physical_address($order_data,$customer_array);
                
                //create array for postal address
                $customer_array = $this->create_postal_address($order_data,$customer_array);

                //prepare url segments for inserting a customer
                $curl_data['api_parameter']   = "2.0/customers";
                $curl_data['api_request']     = 'POST';

                //call Vend API to insert the customer
                $response   = $this->vend_api_curl_request($curl_data,$customer_array);
                
                if(isset($response['data']['id']))
                {
                    $vend_customer_id = $response['data']['id'];

                    //set log contents
                    $log_contents   = 'Success to insert the customer in Vend POS for order Id : '.$order_data['order_id'];

                    //write to log
                    $this->log_content($log_contents);
                }
                else
                {
                    //set log contents
                    $log_contents   = 'Failed to create the customer in Vend POS for order Id : '.$order_data['order_id'];

                    //write to log if fails
                    $this->log_content($log_contents);
                }
            }
        }
        else
        {
            //set log contents
            $log_contents   = 'Failed on success page while creating the customer because the order data not found.';

            //write to log if fails
            $this->log_content($log_contents);
        }

        return $vend_customer_id;
    }

    /***************************************************************
    function to create customer array
    ****************************************************************/
    public function create_customer_array($order_data)
    {
        //prepare customer array
        $customer_array = array(
            'first_name'=> $order_data['firstname'], 
            'last_name' => $order_data['lastname'],     
            'phone'     => $order_data['telephone'],
            'email'     => $order_data['email']
        );

        return $customer_array;
    }

    /***************************************************************
    function to create physical address of the customer
    ****************************************************************/
    public function create_physical_address($order_data,$customer_array)
    {
        $customer_array['physical_address_1']   = 'Phone : '.$order_data['telephone'].', '.$order_data['payment_address_1'];
        $customer_array['physical_address_2']   = $order_data['payment_address_2'];
        $customer_array['physical_city']        = $order_data['payment_city'];
        $customer_array['physical_postcode']    = $order_data['payment_postcode'];
        $customer_array['physical_state']       = $order_data['payment_zone'];
        $customer_array['physical_country_id']  = $order_data['payment_iso_code_2'];

        return $customer_array;
    }

    /***************************************************************
    function to create postal address of the customer
    ****************************************************************/
    public function create_postal_address($order_data,$customer_array)
    {
        $customer_array['postal_address_1']     = 'Phone : '.$order_data['telephone'].', '.$order_data['shipping_address_1'];
        $customer_array['postal_address_2']     = $order_data['shipping_address_2'];
        $customer_array['postal_city']          = $order_data['shipping_city'];
        $customer_array['postal_postcode']      = $order_data['shipping_postcode'];
        $customer_array['postal_state']         = $order_data['shipping_zone'];
        $customer_array['postal_country_id']    = $order_data['shipping_iso_code_2'];    

        return $customer_array;
    }

    /***************************************************************
    function to check product present or not if not then insert it
    ****************************************************************/
    public function get_vend_product_id($order_data=array())
    {
        $vend_product_ids = array();

        if(isset($order_data['products']))
        {
            $i = 0;
            //go through array
            foreach($order_data['products'] as $product)
            {
                if(isset($product['product_name']))
                {
                    if($product['product_sku'] != '' || (trim($product['product_name']) == $this->driver_tip_name))
                    {
                        //call Vend API
                        if(trim($product['product_name']) != $this->driver_tip_name)
                        {
                            //set api url and method
                            $curl_data = array();
                            
                            $curl_data['api_parameter']   = "products/sku/".$product['product_sku'];
                            
                            $curl_data['api_request']     = 'GET';

                            $response   = $this->vend_api_curl_request($curl_data);   
                        }
                        else
                        {
                            $response   = array();   
                        }

                        //check if product is present or not
                        if(isset($response['products']['0']['id'])) //if product is present
                        {
                            $vend_product_ids[$i]['product_id'] = $response['products']['0']['id'];
                        }
                        else //if not present then insert a new product
                        {
                            if(trim($product['product_name']) != $this->driver_tip_name)
                            {
                                //check email
                                $to = "jay@ugollc.com,aakriti.kishore@codaemonsoftwares.com";
                                $subject = "product lookup in vend fails!";
                                $txt = $product['product_name']." product not present in Vend! Please create sales receipt for #".$order_data['order_id']." manually in vend.";
                                $headers = "From: jay@ugollc.com" . "\r\n";                
                                mail($to,$subject,$txt,$headers);

                                $this->log_content($txt);
                                return 0;                            }
                            else
                            {
                                $vend_product_ids[$i]['product_id'] = 'driver_tip';
                            }
                        }
                       
                        $vend_product_ids[$i]['product_quantity']   = $product['product_quantity'];
                        $vend_product_ids[$i]['product_price']      = $product['product_price'];
                        $vend_product_ids[$i]['product_name']       = $product['product_name'];

                        $i++; 
                    }
                    else
                    {
                        //check email
                        $to = "jay@ugollc.com,aakriti.kishore@codaemonsoftwares.com";
                        $subject = "product lookup in vend fails!";
                        $txt = $product['product_name']." product not present in Vend! Please create sales receipt for #".$order_data['order_id']." manually in vend.";
                        $headers = "From: jay@ugollc.com" . "\r\n";                
                        mail($to,$subject,$txt,$headers);

                        $this->log_content($txt);
                        return 0; 
                    }
                }
                else
                {
                    //set log contents
                    $log_contents   = 'Product details not found for the order id : '.$order_data['order_id'];

                    //write to log if fails
                    $this->log_content($log_contents);
                }
            }
        }
        else
        {
            //set log contents
            $log_contents   = 'Failed on success page while creating the product because the order data not found.';

            //write to log if fails
            $this->log_content($log_contents);
        }
        return $vend_product_ids;
    }

    /***************************************************************
    function to insert the sales
    ****************************************************************/
    public function insert_vend_sales($vend_customer_id,$vend_product_ids,$order_data)
    {
        //prepare sales array
        $sales_array    = array(
            'user_id'           => $this->user_id,
            'customer_id'       => $vend_customer_id,
            //'status'            => 'CLOSED', 
            'invoice_number'    => 'UGO-'.$order_data['order_id'],
            'note'              => 'Mobile number : '.$order_data['telephone']
        );

        $i = 0; $total_price = 0; $total_tax = 0;

        //Add products
        foreach($vend_product_ids as $key=>$vend_product_id)
        {
            $sales_array['status'] = 'CLOSED';
            $sales_array['register_sale_products'][$i]['product_id']    = $vend_product_id['product_id'];
            
            $sales_array['register_sale_products'][$i]['quantity']      = $vend_product_id['product_quantity'];
            
            $sales_array['register_sale_products'][$i]['price']         = $vend_product_id['product_price'];
            
            //check if driver tip is there or not
            if($vend_product_id['product_id'] == 'driver_tip')
            {
                //check payment method
                if(stripos($order_data['payment_method'],'Cash On Delivery') !== FALSE)
                {
                    //set cash tip id
                    $sales_array['register_sale_products'][$i]['product_id']    = $this->cash_tip_id;
                }
                else
                {
                    //set credit tip id
                    $sales_array['register_sale_products'][$i]['product_id']    = $this->credit_tip_id;   
                }
                
                $sales_array['register_sale_products'][$i]['tax']       = 0;    
            } else if($vend_product_id['product_id'] == $this->ugo_credit_id){
              //Add UGO credit purchse request in Vend  
              $sales_array['status'] = 'ONACCOUNT';
              $sales_array['register_sale_products'][$i]['tax']       = 0; 
            
            }
            else
            {
                $sales_array['register_sale_products'][$i]['tax']       = $vend_product_id['product_price'] * $this->tax_rate;
                
                $sales_array['register_sale_products'][$i]['tax_id']    = $this->tax_id;
            }

            //set total price and tax
            $total_price    = $total_price + $sales_array['register_sale_products'][$i]['price'] *  $sales_array['register_sale_products'][$i]['quantity'];
            $total_tax      = $total_tax + $sales_array['register_sale_products'][$i]['tax'] *  $sales_array['register_sale_products'][$i]['quantity'];
            
            $i++;   
        }
        
        //Add lower order fee if order price is less than $6.00
        if($total_price < 6.00)
        {
            $sales_array['register_sale_products'][$i]['product_id']    = $this->low_order_fee_id;
        
            $sales_array['register_sale_products'][$i]['quantity']      = 1;

            $sales_array['register_sale_products'][$i]['price']         = $this->low_order_fee;

            $sales_array['register_sale_products'][$i]['tax']           = $this->low_order_fee * $this->tax_rate;
            
            $sales_array['register_sale_products'][$i]['tax_id']        = $this->tax_id;

            $total_price    = $total_price + $sales_array['register_sale_products'][$i]['price'];

            $total_tax      = $total_tax + $sales_array['register_sale_products'][$i]['tax'];

            $i = $i + 1;
        }
        //Add note
        $sales_array['note']    = 'Shipping method : '.$order_data['shipping_method']."\n";
        $sales_array['note']    .= 'Comments : '.$order_data['comment'];
        
        if(stripos($order_data['shipping_method'],'Rate'))
        {
            $sales_array['register_sale_products'][$i]['product_id']    = $this->delivery_fee_id;
        
            $sales_array['register_sale_products'][$i]['quantity']      = 1;

            $sales_array['register_sale_products'][$i]['price']         = $order_data['delivery_fee']; 

            $sales_array['register_sale_products'][$i]['tax']           = $order_data['delivery_fee'] * $this->tax_rate;
            
            $sales_array['register_sale_products'][$i]['tax_id']        = $this->tax_id;

            $total_price    = $total_price + $sales_array['register_sale_products'][$i]['price'];

            $total_tax      = $total_tax + $sales_array['register_sale_products'][$i]['tax'];

            $i = $i + 1;
        }
        
        //Add speed delivery fee
        if(isset($order_data['speedy_fee']) && $order_data['speedy_fee'] != 0.00)
        {
            $sales_array['register_sale_products'][$i]['product_id']    = $this->speedy_fee_id;
        
            $sales_array['register_sale_products'][$i]['quantity']      = 1;

            $sales_array['register_sale_products'][$i]['price']         = $order_data['speedy_fee']; 

            $sales_array['register_sale_products'][$i]['tax']           = $order_data['speedy_fee'] * $this->tax_rate;
            
            $sales_array['register_sale_products'][$i]['tax_id']        = $this->tax_id;

            $total_price    = $total_price + $sales_array['register_sale_products'][$i]['price'];

            $total_tax      = $total_tax + $sales_array['register_sale_products'][$i]['tax'];

            $i = $i + 1;
        }
        
        //Add distance delivery fee
        if(isset($order_data['distance_fee']) && $order_data['distance_fee'] != 0.00)
        {
            $sales_array['register_sale_products'][$i]['product_id']    = $this->distance_fee_id;
        
            $sales_array['register_sale_products'][$i]['quantity']      = 1;

            $sales_array['register_sale_products'][$i]['price']         = $order_data['distance_fee']; 

            $sales_array['register_sale_products'][$i]['tax']           = $order_data['distance_fee'] * $this->tax_rate;
            
            $sales_array['register_sale_products'][$i]['tax_id']        = $this->tax_id;

            $total_price    = $total_price + $sales_array['register_sale_products'][$i]['price'];

            $total_tax      = $total_tax + $sales_array['register_sale_products'][$i]['tax'];

            $i = $i + 1;
        }
        
        //Add Store Credit
        if(isset($order_data['store_credit']) && $order_data['store_credit'] != 0.00 )
        {
            $sales_array['register_sale_products'][$i]['product_id']    = $this->store_credit_id;
        
            $sales_array['register_sale_products'][$i]['quantity']      = 1;

            $sales_array['register_sale_products'][$i]['price']         = $order_data['store_credit']; 

            $sales_array['register_sale_products'][$i]['tax']           = 0 ;
            
            $total_price    = $total_price + $sales_array['register_sale_products'][$i]['price'];

            $i = $i + 1;
        }

        //Find payment method of the order
        if(stripos($order_data['payment_method'],'Cash On Delivery') !== FALSE)
        {   
            //check for CWID
            if(stripos($order_data['comment'],'CWID') !== FALSE)
            {
                //set bama payment id
                $sales_array['register_sale_payments'][0]['retailer_payment_type_id'] = $this->bama_payment_id;
            }
            else
            {
                //set cod payment type id
                $sales_array['register_sale_payments'][0]['retailer_payment_type_id'] = $this->cod_payment_id;
            }
        }
        elseif(stripos($order_data['payment_method'],'BAMA Cash') !== FALSE)
        {
             $sales_array['register_sale_payments'][0]['retailer_payment_type_id'] = $this->bama_payment_id;
        }
        elseif(stripos($order_data['payment_method'],'DD') !== FALSE)
        {
            $sales_array['register_sale_payments'][0]['retailer_payment_type_id'] = $this->dd_payment_id;
        }
        elseif(stripos($order_data['payment_method'],'Paypal') !== FALSE)
        {
            //set paypal payment id
            $sales_array['register_sale_payments'][0]['retailer_payment_type_id'] = $this->paypal_payment_id;
        }
        elseif(stripos($order_data['payment_method'],'Stripe') !== FALSE)
        {
            //set stripe payment id
            $sales_array['register_sale_payments'][0]['retailer_payment_type_id'] = $this->stripe_payment_id;
        }
        elseif(stripos($order_data['payment_method'],'Credit') !== FALSE)
        {
            //set credit payment id
            $sales_array['register_sale_payments'][0]['retailer_payment_type_id'] = $this->credit_payment_id; 
        }

        //Add total amount
        $sales_array['register_sale_payments'][0]['amount'] = abs($total_price + $total_tax);
        
        //prepare url segments for inserting the sales info
        $curl_data['api_parameter']   = "register_sales"; 
        $curl_data['api_request']     = 'POST';

        //call Vend API to insert the sales
        $response   = $this->vend_api_curl_request($curl_data,$sales_array);
        return $response;
    }

    public function vend_api_curl_request($curl_data=array(),$post_data=array()) 
    {
        $curlRequesturl = $this->apiurl . $curl_data['api_parameter'];
        
        //initialise a CURL session
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curlRequesturl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $curl_data['api_request']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "authorization: $this->authorization",
            "content-type: application/json",
        ));
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($ch);
        
        $err = curl_error($ch);

        curl_close($ch);
        
        if($err)
        {
            return $err;
        } 
        else 
        {
            return json_decode($response,1);
        }
    }

    //Log content if fails any operation
    public function log_content($content) 
    {
        $content = gmdate("Y-m-d\TH:i:s", time()) . ' - ' . $content;
        $filename = DIR_LOGS . 'Vend_process_' . date("Ymd") . '_log.txt';
        $logFile = fopen($filename, 'a');
        fwrite($logFile, $content . "\n");
        fclose($logFile);
    }
}