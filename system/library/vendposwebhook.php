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
class Vendposwebhook {

    private $apiurl = 'https://ugollc.vendhq.com/api/2.0/'; // api url of vend store
    private $authorization = 'Bearer 6yQxsfiYriDXYnqCD6mTea_K09bpdLtmiDhTt6NL'; //authorize token

    public function __construct($registry) {
        $this->config = $registry->get('config');
        $this->customer = $registry->get('customer');
        $this->session = $registry->get('session');
    }

    public function getProductDetails($productId) {
        if ($productId != '') {
            $curlData = array();
            $curlData['apiParameter'] = 'products/' . $productId;
            $curlData['apiRequest'] = 'GET';
            return $this->vendApiCurlRequest($curlData);
        }
    }

    public function getProductInventory($productId) {
        if ($productId != '') {
            $curlData = array();
            $curlData['apiParameter'] = 'products/' . $productId.'/inventory';
            $curlData['apiRequest'] = 'GET';
            return $this->vendApiCurlRequest($curlData);
        }
    }

    public function vendApiCurlRequest($curlData=array()) {
//        echo "<pre>";print_r($curlData);exit;
        $curlRequesturl = $this->apiurl . $curlData['apiParameter'];
        //initialise a CURL session
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curlRequesturl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $curlData['apiRequest']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "authorization: $this->authorization",
            "content-type: application/json",
        ));

        if (isset($curlData['postData']) && $curlData['postData'] != '') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlData['postData']));
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return $err;
        } else {
            return json_decode($response,1);
        }
    }

}