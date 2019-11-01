<?php
    class ControllerModuleReferralCouponBaseAPI extends ApiController {

        public function index($args = array()) {
            if($this->request->isGetRequest()) {
                $this->get();
            }elseif($this->request->isPostRequest()){
                $this->post();
            }
            else {
                throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
            }
        }

        public function get() {
            $data = parent::getInternalRouteData('module/api_referral_coupon');
            ApiException::evaluateErrors($data,false);
            $this->response->setOutput($data);
        }
        
        public function post() {
            $data = parent::getInternalRouteData('module/api_referral_coupon/sendReferral');
            ApiException::evaluateErrors($data,false);
            $this->response->setOutput($data);
        }
    }
?>
