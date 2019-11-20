<?php

class ControllerCommonStoreBoundaryRadiusBaseAPI extends ApiController {

	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}else {
                    throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
                }
	}

	
	public function get() {
		$store_boundary = parent::getInternalRouteData('api/boundary');
                
                $data = $store_boundary['boundry_details'];
                
                ApiException::evaluateErrors($data);
            
                $this->response->setOutput($data);
	}
    }
?>	