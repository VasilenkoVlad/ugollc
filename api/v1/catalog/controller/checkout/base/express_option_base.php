<?php
    class ControllerCheckoutExpressOptionBaseAPI extends ApiController {

        public function index($args = array()) {
            if($this->request->isGetRequest()) {			
                $this->get();
            }
            else {
                throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
            }
        }

        public function get() {
            $data = parent::getInternalRouteData('checkout/shipping_method/express_option');         
            
            ApiException::evaluateErrors($data);
            
            $this->response->setOutput($data);
        }
    }
?>
