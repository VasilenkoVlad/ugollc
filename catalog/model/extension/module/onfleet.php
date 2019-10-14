<?php
include('Onfleetlib.php');

class ModelExtensionModuleOnfleet extends Model {

    public function createTask( $order_id ){

        $recipant_id        = "";
        $destination_id     = "";

        $onfleetsettings        = $this->getOnfleetSettings(); // check condition

        if( !isset( $order_id ) || empty( $order_id ) ) {
            
           return false;
        }

        if( !isset( $onfleetsettings['api_key'] )  || empty( $onfleetsettings['key_name'] )  ) {

            return false;
        }

        $key_name               = $onfleetsettings['key_name'];
        $apikey                 = $onfleetsettings['api_key'];

        $onfleet_config         = array('key_name'=>$key_name,'apikey'=>$apikey);
        $objonfleet             = new Onfleetlib( $onfleet_config );

        $orderdetails           = array();
        $orderdetails           = $this->getOrderDetails( $order_id );

        $task_status      = false;

        $taskOrder        = $this->getTask( $order_id );

        if( is_array( $taskOrder) && count( $taskOrder ) > 0 ) {

            if( $taskOrder['task_id'] != NULL && $taskOrder['task_status']== true ) {

                return false;
            }
        }

        $destination            = $objonfleet->destination_create( $orderdetails  );
        $destdetail             = json_decode( $destination,true );

        if( isset( $destdetail['id'] ) && !empty( $destdetail['id'] ) ) {

                $destination_id     = $destdetail['id'];
                $timeCreated        = $destdetail['timeCreated'];
                $timeLastModified   = $destdetail['timeLastModified'];
                $location           = implode(" ",$destdetail['location']);
                $address            = implode(" ",$destdetail['address']);
                $notes              = $destdetail['notes'];
                $tasks		    = "";
		// tasks didn't appear useful, and were problematic when not included in $destdetail, so commented out and passing empty value - tsmith (MM)
		// $tasks              = implode(" ",$destdetail['tasks']);

                $this->db->query("INSERT INTO " . DB_PREFIX . "destination  (destination_id,timeCreated,timeLastModified,location,address,notes,tasks) VALUES ('$destination_id','$timeCreated','$timeLastModified','$location','$address','$notes','$tasks')");
        
        }

        if( !isset( $destdetail['id'] ) && empty( $destdetail['id'] ) ) {

            $destination_id                 = 'Kwz3HQR6UPVuPZeIQ*h6589c';  //Here is the ugo Address of destination

        }

        $recipientdetails                   = $this->getrecipientDetails( $order_id );
        $recipientPhone                     =  '1234567890'; //also take from the onfleet

        if( isset( $recipientdetails['phone'] )  && !empty( $recipientdetails['phone'] ) ) {

            $recipient_telephone            = $recipientdetails['phone'];


            $regexTelephone                 = substr( preg_replace('/[{}()-\/+\s]/','',$recipient_telephone),-10);

            $regexTelephone                 = "+1".$regexTelephone; 
            $recipientdetails['phone']      = $regexTelephone;

            $recipientQuery                 = $this->db->query("SELECT * FROM oc_recipients WHERE phone = '$regexTelephone'");

            $checkRecipientId               = $recipientQuery->row;
           
            $onfleetRecipientId		    = "";
	    if ( !empty( $checkRecipientId)) {
            	$onfleetRecipientId             = $checkRecipientId['recipient_id'];
	    }

            $recipientdetails['notes']      = "";

        }

        $recipient_id         = '1BPWbwgTbz2sG2yzls7nRlg4'; // sadiq recipient id

            if( empty( $onfleetRecipientId ) || !isset( $onfleetRecipientId ) ) {

                $recipient              = $objonfleet->recipient_create( json_encode( $recipientdetails ) );

                $recipientDetail        = array();
                $recipientDetail        = json_decode( $recipient,true );

                if( isset( $recipientDetail['id'] ) && !empty(  $recipientDetail['id'] ) ) {

                    $recipient_id           = $recipientDetail['id'];
                    $rcpttimeCreated        = $recipientDetail['timeCreated'];
                    $rcpttimeLastModified   = $recipientDetail['timeLastModified'];
                    $rcptname               = $recipientDetail['name'];
                    $rcprphone              = $recipientDetail['phone'];
                    $rcptnotes              = $recipientDetail['notes'];
                    $rcptsms                = $recipientDetail['skipSMSNotifications'];
                    $this->db->query("INSERT INTO " . DB_PREFIX . "recipients (recipient_id,timeCreated,timeLastModified,name,phone,notes,skipSMSNotifications) VALUES ('$recipient_id','$rcpttimeCreated','$rcpttimeLastModified','$rcptname','$rcprphone','$rcptnotes','$rcptsms')");

                }


            }else {

                $recipient_id                   = $onfleetRecipientId;

            }

            $organizationApiDetail              = $objonfleet->organizations();

            $organizationDetail                 = json_decode( $organizationApiDetail,true );
            $merchant_id                        = $organizationDetail['id'];
            
            $recID                              = json_encode( array( $recipient_id ) );
            $order_notes                        = $this->getordernotes( $order_id );

            $task_array                         = array('merchant'=>$merchant_id,'executor'=>$merchant_id,'destination'=>$destination_id,'recipients'=> array( $recipient_id ),'notes'=>$order_notes);

            //data type of one of them is invalid

            $task_create        = $objonfleet->task_create( $task_array );

            $taskDetail         = array();   //here is also the  problem for creating the task hardcode variable
            $taskDetail         = json_decode( $task_create,true );

            if( isset( $taskDetail['id'] ) && !empty( $taskDetail['id'] ) ) {

                $task_id                    = $taskDetail['id'];
                $tasktimeCreated            = $taskDetail['timeCreated'];

                $tasktimeLastModified       = $taskDetail['timeLastModified'];
                $taskorgnisation            = $taskDetail['organization'];

                $taskshortid                = $taskDetail['shortId'];
                $tasktrackurl               = $taskDetail['trackingURL'];

                $taskworker                 = $taskDetail['worker'];
                $taskmerchant               = $taskDetail['merchant'];

                $taskexecutor               = $taskDetail['executor'];
                $tascreator                 = $taskDetail['creator'];

                $taskdependency             = json_encode( $taskDetail['dependencies'] );
                $taskstate                  = $taskDetail['state'];

                $taskcompleteafter          = $taskDetail['completeAfter'];
                $taskcompletebefore         = $taskDetail['completeBefore'];

                $taskpickup                 = $taskDetail['pickupTask'];
                $taskcompletedetail         = json_encode( $taskDetail['completionDetails'],true );

                $taskfeedback               = implode(" ",$taskDetail['feedback']);
                $taskmatadata               = implode(" ",$taskDetail['metadata']);
                $taskoverides               = implode(" ",$taskDetail['overrides']);
              
                $taskrecipiants             = json_encode( $taskDetail['recipients'],TRUE );
                
                $taskdestiantion            = json_encode( $taskDetail['destination'],TRUE );
                $task_status                = true;

                if( count( $taskOrder ) > 0 ) {

                    $this->db->query("UPDATE `" . DB_PREFIX . "tasks` SET `timeLastModified` = '$tasktimeLastModified',`organization` = '$taskorgnisation',`shortId` = '$taskshortid', `merchant` = '$taskmerchant',`executor` = '$taskexecutor',`executor` = '$taskexecutor'  WHERE `order_id` = '$order_id'");

                    }else{
                    $this->db->query("INSERT INTO " . DB_PREFIX . "tasks
                    (task_id,order_id,timeCreated,timeLastModified,organization,shortId,trackingURL,worker,merchant,executor,creator,
                     dependencies,state,completeAfter,completeBefore,pickupTask,completionDetails,feedback,metadata,overrides,recipients,destination,task_status      
                    )VALUES
                    ('$task_id','$order_id','$tasktimeCreated','$tasktimeLastModified','$taskorgnisation','$taskshortid','$tasktrackurl','$taskworker','$taskmerchant','$taskexecutor','$tascreator','$taskdependency','$taskstate','$taskcompleteafter','$taskcompletebefore','$taskpickup','$taskcompletedetail','$taskfeedback','$taskmatadata','$taskoverides','$taskrecipiants','$taskdestiantion','$task_status')");
                }

            }else{

                if( count( $taskOrder ) > 0 ) {

                        $this->db->query("UPDATE `" . DB_PREFIX . "tasks` SET `timeLastModified` = '$tasktimeLastModified' WHERE `order_id` = '$order_id'");

                }else{

                    $this->db->query("INSERT INTO " . DB_PREFIX . "tasks
                    (task_id,order_id,timeCreated,timeLastModified,organization,shortId,trackingURL,worker,merchant,executor,creator,
                     dependencies,state,completeAfter,completeBefore,pickupTask,completionDetails,feedback,metadata,overrides,recipients,destination,task_status      
                    )VALUES
                    ('$task_id','$order_id','$tasktimeCreated','$tasktimeLastModified','$taskorgnisation','$taskshortid','$tasktrackurl','$taskworker','$taskmerchant','$taskexecutor','$tascreator','$taskdependency','$taskstate','$taskcompleteafter','$taskcompletebefore','$taskpickup','$taskcompletedetail','$taskfeedback','$taskmatadata','$taskoverides','$taskrecipiants','$taskdestiantion','$task_status')");
                }
            }
    }


    public function getTask( $order_id ) {

        $taskOrder      = array();
        // commenting this out, as this value isn't useful in the Ugo implementation - tsmith (MM) 
        //$query          = $this->db->query("SELECT * FROM " . DB_PREFIX . "tasks WHERE order_id=$order_id");

        //$taskOrder      = $query->row;

        return $taskOrder;

    }
          
        
    public function getOnfleetSettings() {
        $onfleetdetail= $this->db->query("SELECT * FROM " . DB_PREFIX . "onfleet");
        $onfleet= $onfleetdetail->row;
        return $onfleet;
    }
    
    public function getOrderDetails( $order_id ) {

        $query= $this->db->query("SELECT * FROM " . DB_PREFIX . "order WHERE order_id=$order_id");
        $order_detail= $query->row;
   
        $address    = $order_detail['shipping_address_1']." ".$order_detail['shipping_address_2'];
        $city       = $order_detail['shipping_city'];

        $state      = $order_detail['shipping_zone'];
        $country    = $order_detail['shipping_country'];
         
        $streetparameter = $address;         
      // echo "PARAMETER".$streetparameter;
       // $unparsed=$address;
        $number="3045";
       // $street=$order_detail['shipping_address_2'];
        
        $destinationarray=array('number'=>$number,'street'=>$streetparameter,'city'=>$city,'state'=>$state,'country'=>$country);
        $order_detail1 = array('address' => $destinationarray);
        
        //print_r( $order_detail1 );die;
        return $order_detail1;
    }

    public function getrecipientDetails($order_id) {
        
        $query              = $this->db->query("SELECT customer_id,shipping_firstname,shipping_lastname,telephone FROM " . DB_PREFIX . "order WHERE order_id=$order_id");
        $recipient_detail   = $query->row;
        $name               = $recipient_detail['shipping_firstname']." ".$recipient_detail['shipping_lastname'] ;
        $telephone          = $recipient_detail['telephone'];
        // $telephone="6675494605";
        $rescipantarr=array('name'=>$name,'phone'=>$telephone);
        return $rescipantarr;
    }

    public function getordernotes($order_id) {

        $query= $this->db->query("SELECT shipping_firstname,shipping_lastname,payment_code,comment,total,telephone,shipping_address_1,shipping_address_2,shipping_city,shipping_country FROM " . DB_PREFIX . "order WHERE order_id=$order_id");

        //Also include User address and User Phone no.
        $order_detail= $query->row;
        $shippingName = $order_detail['shipping_firstname']." ".$order_detail['shipping_lastname'] ;

        //shippingAddress 
        //user_phone

        $paymentTotal           = $order_detail['total'];
        $paymentMethod          = $order_detail['payment_code'];

        $userComment            = $order_detail['comment'];
        $shipping_city          = $order_detail['shipping_city'];
        $shipping_country       = $order_detail['shipping_country'];

        $shippingAddress        = $order_detail['shipping_address_1']." ".$order_detail['shipping_address_2'];

        $telePhone              = $order_detail['telephone']; 
        $order_details_notes    = "Shipping Name: ".$shippingName.", "."Payment Amount: ".$paymentTotal.", "."Payment: ".$paymentMethod.", "."Customer Notes: ".$userComment.", "."Shipping Address: ".$shippingAddress.", "."Telephone: ".$telePhone.", "."Shipping City: ".$shipping_city.", "."Shipping City: ".$shipping_country;

        return $order_details_notes;

    }
}
