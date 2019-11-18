<?php
    class ControllerModuleReferralHistoryBaseAPI extends ApiController {

        public function index($args = array()) {
            if($this->request->isPostRequest()) {
                $this->post();
            }
            else {
                throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
            }
        }

        public function post() {
            $data = parent::getInternalRouteData('api/referral_coupon/getReferralsHistory');
            ApiException::evaluateErrors($data,false);
            $data['referrals'] = $this->processHistories($data['referrals']);
            
            $this->response->setOutput($data);
        }
        
        public function processHistories($histories) {
            foreach($histories as &$history) {
               $history['date_added'] = date('d/m/Y', strtotime($history['date_added']));
            }
	       return $histories;
	}
    }
?>
