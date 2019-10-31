<?php

class ControllerCheckoutEmptyCartBaseAPI extends ApiController {

	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}

	/**
	 * Resource methods
	 */

	public function get() {
		$data = parent::getInternalRouteData('checkout/checkout/empty_cart');

		ApiException::evaluateErrors($data,false);
                
                $this->response->setOutput($data);
	}
 
}

?>