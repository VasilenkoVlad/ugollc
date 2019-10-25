<?php

class ControllerCheckoutGuestConfirmBaseAPI extends ApiController {

	public function index($args = array()) {
		if($this->request->isPostRequest()) {
			$this->post();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}

	public function redirect($url, $status = 302) {
		switch ($url) {
			case 'checkout/checkout': // Order process not finished
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_ORDER_PROCESS_NOT_FINISHED, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_ORDER_PROCESS_NOT_FINISHED));
				break;
			
			case 'checkout/cart': // No products in cart, no stock for 1 or more product(s) or minimum quantity requirement of product not met
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_NO_PRODUCTS_STOCK_OR_MIN_QUANTITY, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_NO_PRODUCTS_STOCK_OR_MIN_QUANTITY));
				break;
		}
	}

	/**
	 * Resource methods
	 */

	public function post() {
		$data = parent::getInternalRouteData('checkout/confirm/update_guest_detail');

		if(isset($data['redirect'])) {
			$this->redirect($data['redirect']);
		}

		ApiException::evaluateErrors($data);

		$this->response->setOutput($data);
	}

	
 
}

?>
