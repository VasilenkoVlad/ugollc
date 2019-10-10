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
class PostPostcardController extends BaseController
{
    /**
     * @var PostPostcardController The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     * @return PostPostcardController The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }

    /**
     * Send one or more postcards
     *
     * @param Models\PostPostcard $postPostcards PostPostcard model
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function sendPostcard(
        $postPostcards
    ) {
        //check that all required arguments are provided
        if (!isset($postPostcards)) {
            throw new \InvalidArgumentException("One or more required arguments were NULL.");
        }


        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/post/postcards/send';

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
        $response = Request::post($_queryUrl, $_headers, Request\Body::Json($postPostcards));

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
     * Calculate price for sending one or more postcards
     *
     * @param Models\PostPostcard $postPostcards PostPostcard model
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function calculatePrice(
        $postPostcards
    ) {
        //check that all required arguments are provided
        if (!isset($postPostcards)) {
            throw new \InvalidArgumentException("One or more required arguments were NULL.");
        }


        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/post/postcards/price';

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
        $response = Request::post($_queryUrl, $_headers, Request\Body::Json($postPostcards));

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
     * Retrieve the history of postcards sent or scheduled
     *
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function getPostcardHistory()
    {

        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/post/postcards/history';

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
     * Export postcard history to a CSV file
     *
     * @param string $filename Filename to export to
     * @return string response from the API call
     * @throws APIException Thrown if API call fails
     */
    public function exportPostcardHistory(
        $filename
    ) {
        //check that all required arguments are provided
        if (!isset($filename)) {
            throw new \InvalidArgumentException("One or more required arguments were NULL.");
        }


        //the base uri for api requests
        $_queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $_queryBuilder = $_queryBuilder.'/post/postcards/history/export';

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
}
