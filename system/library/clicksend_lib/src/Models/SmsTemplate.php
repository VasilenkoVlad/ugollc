<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 *SmsTemplate model
 */
class SmsTemplate implements JsonSerializable
{
    /**
     * Name of template
     * @required
     * @maps template_name
     * @var string $templateName public property
     */
    public $templateName;

    /**
     * Body of template
     * @required
     * @var string $body public property
     */
    public $body;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $templateName Initialization value for $this->templateName
     * @param string $body         Initialization value for $this->body
     */
    public function __construct()
    {
        if (2 == func_num_args()) {
            $this->templateName = func_get_arg(0);
            $this->body         = func_get_arg(1);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['template_name'] = $this->templateName;
        $json['body']          = $this->body;

        return $json;
    }
}
