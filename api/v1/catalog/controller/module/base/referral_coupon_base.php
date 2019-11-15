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
            $data = parent::getInternalRouteData('api/referral_coupon');
            ApiException::evaluateErrors($data,false);
            $refferals_data = $this->processReferralCoupon($data);
            $this->response->setOutput($refferals_data);
        }
        
        public function post() {
            $data = parent::getInternalRouteData('api/referral_coupon/sendReferral');
            ApiException::evaluateErrors($data,false);
            $this->response->setOutput($data);
        }
        
        public function processReferralCoupon($coupon) {
            $coupon['coupon_discount'] = str_replace("decimal_point",".",$coupon['coupon_discount']);
            $coupon['order_total'] = str_replace("decimal_point",".",$coupon['order_total']);
            $coupon['customer_login'] = "yes";
            return $coupon;
        }
    }
?>
