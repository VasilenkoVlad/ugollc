<?php
    class ControllerCheckoutForbiddenCheckBaseAPI extends ApiController {

        public function index($args = array()) {
            if($this->request->isGetRequest()) {			
                $this->get();
            }
            else {
                throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
            }
        }

        public function get() {
            $data = parent::getInternalRouteData('checkout/payment_method/forbidden_check');
            
            ApiException::evaluateErrors($data,false);
            
            $this->response->setOutput($data);
        }
    }
?>
