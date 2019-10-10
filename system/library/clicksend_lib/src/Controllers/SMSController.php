<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Controllers;

use ClickSendLib\APIException;
use ClickSendLib\APIHelper;
use ClickSendLib\Configuration;
use ClickSendLib\Models;
use ClickSendLib\Exceptions;
use ClickSendLib\Http\HttpRequest;
use ClickSendLib\Http\HttpResponse;
use ClickSendLib\Http\HttpMethod;
use ClickSendLib\Http\HttpContext;
use Unirest\Request;

/**
 * @todo Add a general description for this controller.
 */
class SMSController extends BaseController
{
    /**
     * @var SMSController The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     * @return SMSController The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }

    /**
     * Send one or more SMS messages
     *
     * @param Models\SmsMessageCollection $smsMessages SmsMessageCollection model
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function sendSms(
        $smsMessages
    ) {
        //check that all required arguments are provided
        if (!isset($smsMessages)) {
            throw new \InvalidArgumentException("One or more required arguments were NULL.");
        }


        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/sms/send';

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl($_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => 'ClickSendSDK',
            'content-type'  => 'application/json; charset=utf-8'
        );

        //set HTTP basic auth parameters
        Request::auth(Configuration::$username, Configuration::$key);

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::POST, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }        
        //and invoke the API call request to fetch the response
        $response = Request::post($_queryUrl, $_headers, Request\Body::Json($smsMessages));
        
        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //return null on 404
        if ($response->code == 404) {
            return null;
        }
        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        return $response->body;
    }

    /**
     * Calculate sms price
     *
     * @param Models\SmsMessageCollection $smsMessages SmsMessageCollection model
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function calculatePrice(
        $smsMessages
    ) {
        //check that all required arguments are provided
        if (!isset($smsMessages)) {
            throw new \InvalidArgumentException("One or more required arguments were NULL.");
        }


        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/sms/price';

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl($_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => 'ClickSendSDK',
            'content-type'  => 'application/json; charset=utf-8'
        );

        //set HTTP basic auth parameters
        Request::auth(Configuration::$username, Configuration::$key);

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::POST, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::post($_queryUrl, $_headers, Request\Body::Json($smsMessages));

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //return null on 404
        if ($response->code == 404) {
            return null;
        }
        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        return $response->body;
    }

    /**
     * Export all sms history
     *
     * @param string $filename Filename to download history as
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function exportHistory(
        $filename
    ) {
        //check that all required arguments are provided
        if (!isset($filename)) {
            throw new \InvalidArgumentException("One or more required arguments were NULL.");
        }


        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/sms/history/export';

        //process optional query parameters
        APIHelper::appendUrlWithQueryParameters($_queryBuilder, array (
            'filename' => $filename,
        ));

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl($_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => 'ClickSendSDK'
        );

        //set HTTP basic auth parameters
        Request::auth(Configuration::$username, Configuration::$key);

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::GET, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::get($_queryUrl, $_headers);

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //return null on 404
        if ($response->code == 404) {
            return null;
        }
        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        return $response->body;
    }

    /**
     * Add a delivery receipt
     *
     * @param string $url Your url
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function createReceipt(
        $url
    ) {
        //check that all required arguments are provided
        if (!isset($url)) {
            throw new \InvalidArgumentException("One or more required arguments were NULL.");
        }


        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/sms/receipts';

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl($_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => 'ClickSendSDK',
            'content-type'  => 'text/plain; charset=utf-8'
        );

        //set HTTP basic auth parameters
        Request::auth(Configuration::$username, Configuration::$key);

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::POST, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::post($_queryUrl, $_headers, $url);

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //return null on 404
        if ($response->code == 404) {
            return null;
        }
        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        return $response->body;
    }

    /**
     * Marked delivery receipts as read
     *
     * @param string $dateBefore  (optional) Mark all as read before this timestamp
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function markReceiptsAsRead(
        $dateBefore = null
    ) {

        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/sms/receipts-read';

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl($_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => 'ClickSendSDK',
            'content-type'  => 'text/plain; charset=utf-8'
        );

        //set HTTP basic auth parameters
        Request::auth(Configuration::$username, Configuration::$key);

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::PUT, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::put($_queryUrl, $_headers, $dateBefore);

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //return null on 404
        if ($response->code == 404) {
            return null;
        }
        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        return $response->body;
    }

    /**
     * Get all inbound sms
     *
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function getInboundSms()
    {

        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/sms/inbound';

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl($_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => 'ClickSendSDK'
        );

        //set HTTP basic auth parameters
        Request::auth(Configuration::$username, Configuration::$key);

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::GET, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::get($_queryUrl, $_headers);

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //return null on 404
        if ($response->code == 404) {
            return null;
        }
        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        return $response->body;
    }

    /**
     * Create inbound sms
     *
     * @param string $url Your url
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function createInboundSms(
        $url
    ) {
        //check that all required arguments are provided
        if (!isset($url)) {
            throw new \InvalidArgumentException("One or more required arguments were NULL.");
        }


        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/sms/inbound';

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl($_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => 'ClickSendSDK',
            'content-type'  => 'text/plain; charset=utf-8'
        );

        //set HTTP basic auth parameters
        Request::auth(Configuration::$username, Configuration::$key);

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::POST, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::post($_queryUrl, $_headers, $url);

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //return null on 404
        if ($response->code == 404) {
            return null;
        }
        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        return $response->body;
    }

    /**
     * Update scheduled message as cancel
     *
     * @param string $messageId  The message ID you want to cancel
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function cancelScheduledSms(
        $messageId
    ) {
        //check that all required arguments are provided
        if (!isset($messageId)) {
            throw new \InvalidArgumentException("One or more required arguments were NULL.");
        }


        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/sms/{message_id}/cancel';

        //process optional query parameters
        $_queryBuilder = APIHelper::appendUrlWithTemplateParameters($_queryBuilder, array (
            'message_id' => $messageId,
            ));

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl($_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => 'ClickSendSDK'
        );

        //set HTTP basic auth parameters
        Request::auth(Configuration::$username, Configuration::$key);

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::PUT, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::put($_queryUrl, $_headers);

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //return null on 404
        if ($response->code == 404) {
            return null;
        }
        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        return $response->body;
    }

    /**
     * Update all scheduled message as cancelled
     *
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function cancelAllScheduledSms()
    {

        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/sms/cancel-all';

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl($_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => 'ClickSendSDK'
        );

        //set HTTP basic auth parameters
        Request::auth(Configuration::$username, Configuration::$key);

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::PUT, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::put($_queryUrl, $_headers);

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //return null on 404
        if ($response->code == 404) {
            return null;
        }
        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        return $response->body;
    }

    /**
     * Create sms template
     *
     * @param Models\SmsTemplate $smsTemplate SmsTemplate model
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function createSmsTemplate(
        $smsTemplate
    ) {
        //check that all required arguments are provided
        if (!isset($smsTemplate)) {
            throw new \InvalidArgumentException("One or more required arguments were NULL.");
        }


        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/sms/templates';

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl($_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => 'ClickSendSDK',
            'content-type'  => 'application/json; charset=utf-8'
        );

        //set HTTP basic auth parameters
        Request::auth(Configuration::$username, Configuration::$key);

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::POST, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::post($_queryUrl, $_headers, Request\Body::Json($smsTemplate));

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //return null on 404
        if ($response->code == 404) {
            return null;
        }
        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        return $response->body;
    }

    /**
     * Delete sms template
     *
     * @param integer $templateId  Template id
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function deleteSmsTemplate(
        $templateId
    ) {
        //check that all required arguments are provided
        if (!isset($templateId)) {
            throw new \InvalidArgumentException("One or more required arguments were NULL.");
        }


        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/sms/templates/{template_id}';

        //process optional query parameters
        $_queryBuilder = APIHelper::appendUrlWithTemplateParameters($_queryBuilder, array (
            'template_id' => $templateId,
            ));

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl($_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => 'ClickSendSDK'
        );

        //set HTTP basic auth parameters
        Request::auth(Configuration::$username, Configuration::$key);

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::DELETE, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::delete($_queryUrl, $_headers);

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //return null on 404
        if ($response->code == 404) {
            return null;
        }
        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        return $response->body;
    }

    /**
     * Update sms template
     *
     * @param integer            $templateId  Template id
     * @param Models\SmsTemplate $smsTemplate Template item
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function updateSmsTemplate(
        $templateId,
        $smsTemplate
    ) {
        //check that all required arguments are provided
        if (!isset($templateId, $smsTemplate)) {
            throw new \InvalidArgumentException("One or more required arguments were NULL.");
        }


        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/sms/templates/{template_id}';

        //process optional query parameters
        $_queryBuilder = APIHelper::appendUrlWithTemplateParameters($_queryBuilder, array (
            'template_id' => $templateId,
            ));

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl($_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => 'ClickSendSDK',
            'content-type'  => 'application/json; charset=utf-8'
        );

        //set HTTP basic auth parameters
        Request::auth(Configuration::$username, Configuration::$key);

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::PUT, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::put($_queryUrl, $_headers, Request\Body::Json($smsTemplate));

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //return null on 404
        if ($response->code == 404) {
            return null;
        }
        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        return $response->body;
    }

    /**
     * Get all delivery receipts
     *
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function getDeliveryReceipts()
    {

        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/sms/receipts';

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl($_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => 'ClickSendSDK'
        );

        //set HTTP basic auth parameters
        Request::auth(Configuration::$username, Configuration::$key);

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::GET, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::get($_queryUrl, $_headers);

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //return null on 404
        if ($response->code == 404) {
            return null;
        }
        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        return $response->body;
    }

    /**
     * Get lists of all sms templates
     *
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function getSmsTemplates()
    {

        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/sms/templates';

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl($_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => 'ClickSendSDK'
        );

        //set HTTP basic auth parameters
        Request::auth(Configuration::$username, Configuration::$key);

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::GET, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::get($_queryUrl, $_headers);

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //return null on 404
        if ($response->code == 404) {
            return null;
        }
        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        return $response->body;
    }

    /**
     * Mark all inbound SMS as read optionally before a certain date
     *
     * @param string $dateBefore  (optional) An optional timestamp - mark all as read before this timestamp. If not
     *                            given, all messages will be marked as read.
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function markAllInboundSMSAsRead(
        $dateBefore = null
    ) {

        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/sms/inbound-read';

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl($_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => 'ClickSendSDK',
            'content-type'  => 'text/plain; charset=utf-8'
        );

        //set HTTP basic auth parameters
        Request::auth(Configuration::$username, Configuration::$key);

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::PUT, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::put($_queryUrl, $_headers, $dateBefore);

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //return null on 404
        if ($response->code == 404) {
            return null;
        }
        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        return $response->body;
    }

    /**
     * Get a Specific Delivery Receipt
     *
     * @param string $messageId  Message ID
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function getSpecificDeliveryReceipt(
        $messageId
    ) {
        //check that all required arguments are provided
        if (!isset($messageId)) {
            throw new \InvalidArgumentException("One or more required arguments were NULL.");
        }


        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/sms/receipts/{message_id}';

        //process optional query parameters
        $_queryBuilder = APIHelper::appendUrlWithTemplateParameters($_queryBuilder, array (
            'message_id' => $messageId,
            ));

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl($_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => 'ClickSendSDK'
        );

        //set HTTP basic auth parameters
        Request::auth(Configuration::$username, Configuration::$key);

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::GET, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::get($_queryUrl, $_headers);

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //return null on 404
        if ($response->code == 404) {
            return null;
        }
        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        return $response->body;
    }

    /**
     * Get all sms history
     *
     * @param integer $dateFrom  (optional) Start date
     * @param integer $dateTo    (optional) End date
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function getSmsHistory(
        $dateFrom = null,
        $dateTo = null
    ) {

        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/sms/history';

        //process optional query parameters
        APIHelper::appendUrlWithQueryParameters($_queryBuilder, array (
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
        ));

        //validate and preprocess url
        $_queryUrl = APIHelper::cleanUrl($_queryBuilder);

        //prepare headers
        $_headers = array (
            'user-agent'    => 'ClickSendSDK'
        );

        //set HTTP basic auth parameters
        Request::auth(Configuration::$username, Configuration::$key);

        //call on-before Http callback
        $_httpRequest = new HttpRequest(HttpMethod::GET, $_headers, $_queryUrl);
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnBeforeRequest($_httpRequest);
        }

        //and invoke the API call request to fetch the response
        $response = Request::get($_queryUrl, $_headers);

        $_httpResponse = new HttpResponse($response->code, $response->headers, $response->raw_body);
        $_httpContext = new HttpContext($_httpRequest, $_httpResponse);

        //call on-after Http callback
        if ($this->getHttpCallBack() != null) {
            $this->getHttpCallBack()->callOnAfterRequest($_httpContext);
        }

        //return null on 404
        if ($response->code == 404) {
            return null;
        }
        //handle errors defined at the API level
        $this->validateResponse($_httpResponse, $_httpContext);

        return $response->body;
    }
}
