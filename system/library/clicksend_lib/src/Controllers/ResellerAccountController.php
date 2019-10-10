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
class ResellerAccountController extends BaseController
{
    /**
     * @var ResellerAccountController The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     * @return ResellerAccountController The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }

    /**
     * Get list of reseller accounts
     *
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function getResellerAccounts()
    {

        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/reseller/accounts';

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
     * Create reseller account
     *
     * @param Models\ResellerAccount $resellerAccount ResellerAccount model
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function createResellerAccount(
        $resellerAccount
    ) {
        //check that all required arguments are provided
        if (!isset($resellerAccount)) {
            throw new \InvalidArgumentException("One or more required arguments were NULL.");
        }


        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/reseller/accounts';

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
        $response = Request::post($_queryUrl, $_headers, Request\Body::Json($resellerAccount));

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
     * Get Reseller Account
     *
     * @param integer $clientUserId   User ID of client
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function getResellerAccount(
        $clientUserId
    ) {
        //check that all required arguments are provided
        if (!isset($clientUserId)) {
            throw new \InvalidArgumentException("One or more required arguments were NULL.");
        }


        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/reseller/accounts/{client_user_id}';

        //process optional query parameters
        $_queryBuilder = APIHelper::appendUrlWithTemplateParameters($_queryBuilder, array (
            'client_user_id' => $clientUserId,
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
     * Reseller Account
     *
     * @param integer                $clientUserId    User ID of client
     * @param Models\ResellerAccount $resellerAccount ResellerAccount model
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function updateResellerAccount(
        $clientUserId,
        $resellerAccount
    ) {
        //check that all required arguments are provided
        if (!isset($clientUserId, $resellerAccount)) {
            throw new \InvalidArgumentException("One or more required arguments were NULL.");
        }


        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/reseller/accounts/{client_user_id}';

        //process optional query parameters
        $_queryBuilder = APIHelper::appendUrlWithTemplateParameters($_queryBuilder, array (
            'client_user_id'  => $clientUserId,
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
        $response = Request::put($_queryUrl, $_headers, Request\Body::Json($resellerAccount));

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
