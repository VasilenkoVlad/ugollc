<?php

class ControllerAccountBuyCreditBaseAPI extends ApiController {
	
	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}elseif($this->request->isPostRequest()) {
			$this->post();
		}
                
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}
	/**
	 * Resource methods
	 */
	public function get() {
		$data = parent::getInternalRouteData('api/buy_credit/api_credits_validation');

		ApiException::evaluateErrors($data,false);
                
                $this->response->setOutput($data);
	}
        
        public function post() {
		$data = parent::getInternalRouteData('account/buy_credit');

		ApiException::evaluateErrors($data,false);
                
                $this->response->setOutput($data);
	}
 
}

?>