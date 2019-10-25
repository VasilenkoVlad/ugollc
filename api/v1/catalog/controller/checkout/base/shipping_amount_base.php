<?php
    class ControllerCheckoutShippingAmountBaseAPI extends ApiController {

        public function index($args = array()) {
            if($this->request->isPostRequest()) {			
                $this->post();
            } elseif($this->request->isGetRequest()) {
                 $this->get();
            }
            else {
                throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
            }
        }

        public function post() {
            $data = parent::getInternalRouteData('checkout/payment_method/get_delivery_fee');
            
            ApiException::evaluateErrors($data,false);
            
            $this->response->setOutput($data);
        }
        
        public function get() {
            $data = parent::getInternalRouteData('checkout/payment_method/get_distance_delivery_fee');
            
            ApiException::evaluateErrors($data,false);
            
            $this->response->setOutput($data);
        }
    }
?>
