<?php 
require 'vendor/autoload.php';

class Clicksend{
 private static $instance;
   
  /**
   * @param  object  $registry  Registry Object
   */
  public static function get_instance($registry) {
    if (is_null(static::$instance)) {
      static::$instance = new static($registry);
    }
 
    return static::$instance;
  }
 public function send_sms($telephone,$country_code,$firstname,$order_id = '',$o_info='',$order_status_id=''){ 	 	
	try {
	    // Prepare ClickSend client.
              $client = new \ClickSendLib\ClickSendClient('seanz', 'B54FCAB3-337B-F483-D092-449E171071AE');
	    
            // Get SMS instance.
	    $sms = $client->getSMS();
            $messages = '';
	    // The payload.
            if($order_id != ''){
                if($order_status_id == 7){
                    $messages = array('0'=> array(
                        "source" =>"php",
                        "from" => "sendmobile",
                        "body" => $firstname.",\nYour UGO order ".$order_id." has been cancelled.\nPlease call (205)-632-3307 for further assistance.",
                        "to" => $country_code.$telephone,
                        "custom_string" => "this is a test"));
                }else if($order_status_id == 2){
                    if($o_info['credit_purchase'] == 0){
                    //$shipping_address = $o_info['shipping_address_1'].", ".$o_info['shipping_address_2'].", ".$o_info['shipping_city'].", ".$o_info['shipping_postcode'].", ".$o_info['shipping_zone'];
                    $messages = array('0'=> array(
                        "source" =>"php",
                        "from" => "sendmobile",
                        "body" => $firstname.",\nYour UGO order ".$order_id." is placed. You'll be notified shortly further!\n Please refer your friends to Ugo. You will earn $5 off for every friend you refer.\n\n We go where UGO!\n(205)632-3307",
                        "to" => $country_code.$telephone,
                        "custom_string" => "this is a test"));
                    } else if($o_info['credit_purchase'] == 1){
                        $messages = array('0'=> array(
                        "source" =>"php",
                        "from" => "sendmobile",
                        "body" => "Congrats! Your Ugo Credit purchase request is under review. Once approved you'll receive a confirmation text! Hang tight, your funds will be added to your E-wallet within 5 minutes.\n\nIf rejected, please call on 205-632-3307.",
                        "to" => $country_code.$telephone,
                        "custom_string" => "this is a test")); 
                    }
                }elseif($o_info['credit_purchase'] == 0 && $order_status_id == 5){
                    $shipping_address = $o_info['shipping_address_1'].", ".$o_info['shipping_address_2'].", ".$o_info['shipping_city'].", ".$o_info['shipping_postcode'].", ".$o_info['shipping_zone'];
                    $messages = array('0'=> array(
                        "source" =>"php",
                        "from" => "sendmobile",
                        "body" => $firstname.",\nYour UGO order ".$order_id." has been processed for immediate delivery to (".$shipping_address.")\n You'll be connected with your driver shortly!\n Please refer your friends to Ugo. You will earn $5 off for every friend you refer.\n\n We go where UGO!\n(205)632-3307",
                        "to" => $country_code.$telephone,
                        "custom_string" => "this is a test"));
                }
            }else{
                // On new customer account
                $messages = array('0'=> array(
		    "source" =>"php",
		    "from" => "sendmobile",
		    "body" => $firstname.", welcome to UGo!\nWe are very excited to have you join the growing UGo community.\n Welcome aboard!",
                    "to" => $country_code.$telephone,
		    "custom_string" => "this is a test"));
            }        
	    // Send SMS.
            if($messages != '') {
                $response = $sms->sendSms(array('messages' => $messages));
            }
	} catch(\ClickSendLib\APIException $e) {
	    //print_r($e->getResponseBody());
	}
    }
    
     public function send_referral_sms($telephone,$country_code,$body){
	try {
	    // Prepare ClickSend client.
              $client = new \ClickSendLib\ClickSendClient('seanz', 'B54FCAB3-337B-F483-D092-449E171071AE');
	    
            // Get SMS instance.
	    $sms = $client->getSMS();
	    // The payload.
            $messages = array('0'=> array(
                "source" =>"php",
                "from" => "sendmobile",
                "body" => $body,
                "to" => $country_code.$telephone,
                "custom_string" => "this is a test"));
	    // Send SMS.
	    $response = $sms->sendSms(array('messages' => $messages));
	} catch(\ClickSendLib\APIException $e) {
	    //print_r($e->getResponseBody());
	}
        
        
    }
    
    public function send_store_credit_purchase_sms($telephone,$country_code,$firstname,$amount,$total){
	try {
	    // Prepare ClickSend client.
              $client = new \ClickSendLib\ClickSendClient('seanz', 'B54FCAB3-337B-F483-D092-449E171071AE');
            // Get SMS instance.
	    $sms = $client->getSMS();
            $messages = array('0'=> array(
                "source" =>"php",
                "from" => "sendmobile",
                "body" => $firstname.", thank you for purchasing ".$amount." in UGO Credit!\n\nYour current UGO Credit balance is now ".$total."\n\n *UGO Credit will be used for your future orders until your balance reaches $0.00*",
                "to" => $country_code.$telephone,
                "custom_string" => "this is a test"));
	    // Send SMS.
	    $response = $sms->sendSms(array('messages' => $messages));
	} catch(\ClickSendLib\APIException $e) {
	    //print_r($e->getResponseBody());
	}
    }
    
    //To send Sms for adding credit back
    public function send_add_transaction_sms($telephone,$country_code,$firstname,$order_id,$amount){
	try {
	    // Prepare ClickSend client.
              $client = new \ClickSendLib\ClickSendClient('seanz', 'B54FCAB3-337B-F483-D092-449E171071AE');
            // Get SMS instance.
	    $sms = $client->getSMS();
            $messages = array('0'=> array(
                "source" =>"php",
                "from" => "sendmobile",
                "body" => $firstname.",\nYour spent amount $".round($amount)." for order id ".$order_id." is credited back to your UGO Credit Wallet",
                "to" => $country_code.$telephone,
                "custom_string" => "this is a test"));
	    // Send SMS.
	    $response = $sms->sendSms(array('messages' => $messages));
	} catch(\ClickSendLib\APIException $e) {
	    //print_r($e->getResponseBody());
	}
    }
}
#END OF PHP FILE