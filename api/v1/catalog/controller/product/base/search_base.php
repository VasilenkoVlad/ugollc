<?php

class ControllerProductSearchBaseAPI extends ApiController {

	private static $allowedOrder = array('ASC', 'DESC');
	private static $allowedSort = array('price' => 'p.price',
										'name' => 'pd.name',
										'rating' => 'rating',
										'model' => 'p.model',
										'sort_order' => 'p.sort_order',
										'quantity' => 'p.quantity',
										'date_added' => 'p.date_added');

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
	
	public function get($id = NULL) {
		$this->setRequestParams();
			
		$data = parent::getInternalRouteData('product/search');

		$products = array('products' => $this->getProducts($data));
		$this->response->setOutput($products);
	}
	
	/**
	 * Helper methods
	 */
	
	protected function setRequestParams() {
		// sort
		if(isset($this->request->get['sort'])) {
			if(in_array($this->request->get['sort'], array_keys(ControllerProductSearchBaseAPI::$allowedSort))) {
				$this->request->get['sort'] = ControllerProductSearchBaseAPI::$allowedSort[$this->request->get['sort']];
			}
			else {
				$message = sprintf(ErrorCodes::getMessage(ErrorCodes::ERRORCODE_SORT_NOT_ALLOWED), implode(', ', array_keys(self::$allowedSort)));
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_SORT_NOT_ALLOWED, $message);
			}
		}

		// order
		if(isset($this->request->get['order'])) {
			if(!in_array($this->request->get['order'], ControllerProductSearchBaseAPI::$allowedOrder)) {
				$message = sprintf(self::getMessage(ErrorCodes::ERRORCODE_ORDER_NOT_ALLOWED), implode(', ', array_keys(self::$allowedOrder)));
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_ORDER_NOT_ALLOWED, $message);
			}
		}

		// Empty this parameter because 'false' will give the same results as true.
		$this->request->convertBoolToCheckbox('description');
		$this->request->convertBoolToCheckbox('sub_category');
	}

	protected function getProducts($data) {
		$products = $data['products'];

		return $this->processProducts($products);
	}

	protected function processProducts($products) {
		foreach($products as &$product) {
			$product = $this->processProduct($product);
		}

		return $products;
	}

	protected function processProduct($product) {
		$product['product_id'] = (int)$product['product_id'];
		
		// Remove href
		unset($product['href']);
		if($product['price'] === false) {
			$product['price'] = null;
		}
		if($product['tax'] === false) {
			$product['tax'] = null;
		}
		if($product['special'] === false) {
			$product['special'] = null;
		}
		$product['thumb_image'] = $product['thumb'];
                $product['price'] = str_replace("decimal_point",".",$product['price']);
		unset($product['thumb']);

		return $product;
	}
 
}

?>