<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 *Email-to-SMS Allowed Address
 */
class EmailSMSAddress implements JsonSerializable
{
    /**
     * Your email address
     * @required
     * @maps email_address
     * @var string $emailAddress public property
     */
    public $emailAddress;

    /**
     * Your sender id
     * @required
     * @var string $from public property
     */
    public $from;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $emailAddress Initialization value for $this->emailAddress
     * @param string $from         Initialization value for $this->from
     */
    public function __construct()
    {
        if (2 == func_num_args()) {
            $this->emailAddress = func_get_arg(0);
            $this->from         = func_get_arg(1);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['email_address'] = $this->emailAddress;
        $json['from']          = $this->from;

        return $json;
    }
}
