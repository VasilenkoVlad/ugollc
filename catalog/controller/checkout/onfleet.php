<?php
class Controllercheckoutonfleet extends Controller {

   
    public function createTask($order_id) {
       
    	$this->load->model('extension/onfleet');
        $this->model_extension_onfleet->createTask($order_id);
      
        
    }

 
}