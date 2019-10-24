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
class Vendsales {
    /*********************** LIVE DETAILS START *********************************************************/
    private $apiurl             = 'https://ugollc.vendhq.com/api/2.0/'; // api url of vend store
    private $authorization      = 'Bearer 6yQxsfiYriDXYnqCD6mTea_K09bpdLtmiDhTt6NL'; //authorize token
    private $user_id            = 'e52b2846-e9be-11e5-f98b-7c25ab397c3f'; //user id in Vend
    
    /*********************** LIVE DETAILS END *********************************************************/

    public function __construct($registry) {
        $this->config = $registry->get('config');
        $this->customer = $registry->get('customer');
        $this->session = $registry->get('session');
    }

    /****************************************************
    function to get orders from vend to OpenCart
    ******************************************************/
    public function get_vend_sales_details($sales_id)
    {   
       
            $curl_data['api_parameter']   = "sales/".$sales_id; 
            $curl_data['api_request']     = 'GET';
            $response = $this->vend_api_curl_request($curl_data);
            if(isset($response['data']['id']))
                {
                    $vend_sale_id = $response['data']['id'];

                    //set log contents
                    $log_contents   = 'Successfully get response for vend sale id : '.$vend_sale_id;

                    //write to log
                    $this->log_content($log_contents);
                    
                }
                else
                {
                    
                    //set log contents
                    $log_contents   = 'Failed to get response for vend sale id : '.$sales_id;

                    //write to log if fails
                    $this->log_content($log_contents);
                    
                }
        return $response;
    }
    
   
    public function vend_api_curl_request($curl_data = array()) 
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