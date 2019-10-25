<?php

class ControllerAccountRegisterfbBaseAPI extends ApiController {

    private $defaultParameters = array(
        'fax' => '',
        'company' => '',
        'address_2' => '',
        'agree' => 'true'
    );

    public function index($args = array()) {
        if($this->request->isPostRequest()) {
            $this->post();
        }
        else {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
        }
        
    }

    public function redirect($url, $status = 302) {
        switch($url) {
            case 'account/account': // Customer is already logged in
                throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_USER_ALREADY_LOGGED_IN, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_USER_ALREADY_LOGGED_IN));
                break;
 
            case 'account/success': // Success
                // Get account data
                $this->response->setInterceptOutput(false);
                $this->request->post = array();
                $this->request->server['REQUEST_METHOD'] = 'GET';
                $action = new ApiAction('account/account');
                $action->execute($this->registry);

                $this->response->setHttpResponseCode(ApiResponse::HTTP_RESPONSE_CODE_CREATED);
                $this->response->output();
                exit();
                break;
        }
    }

    /**
     * Resource methods
     */
    
    public function post() {

        $this->request->setDefaultParameters($this->defaultParameters);
        $this->request->convertBoolToCheckbox('agree');
        $this->request->convertBoolToYesNoRadioValue('newsletter');
            
        $data = parent::getInternalRouteData('account/register');

        if (isset($data['register_custom_field'][1])) {
                $fbid = $data['register_custom_field'][1];

                $this->load->model('account/customer');
                $customer_info = $this->model_account_customer->getCustomerByFbId($fbid);

                if (count($customer_info) > 0) {
                    $output = array();
                    $this->response->setHttpResponseCode(ApiResponse::HTTP_RESPONSE_CODE_CREATED);
                    $this->session->data['customer_id'] =  $customer_info[0]['customer_id'];
                    $output['account'] = $customer_info[0];
                    $this->response->setOutput($output);
                    $this->response->output();
                    exit();
                    break;
                }else{
                    $customer_info = $this->model_account_customer->getCustomerByEmail($data['email']);
                    $this->model_account_customer->editFbId($customer_info['customer_id'],$fbid);
                    $output = array();
                    $this->response->setHttpResponseCode(ApiResponse::HTTP_RESPONSE_CODE_CREATED);
                    $this->session->data['customer_id'] =  $customer_info['customer_id'];
                    $output['account'] = $customer_info;
                    $this->response->setOutput($output);
                    $this->response->output();
                    exit();
                    break;                
                }
        }

        ApiException::evaluateErrors($data);
    }
 
}

?>