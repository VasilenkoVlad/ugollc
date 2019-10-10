<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 *Verifies an account by token that should have been sent to the user's phone
 */
class AccountVerify implements JsonSerializable
{
    /**
     * Country code
     * @required
     * @var string $country public property
     */
    public $country;

    /**
     * User's phone number
     * @required
     * @maps user_phone
     * @var string $userPhone public property
     */
    public $userPhone;

    /**
     * Type of verification
     * @required
     * @var string $type public property
     */
    public $type;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $country   Initialization value for $this->country
     * @param string $userPhone Initialization value for $this->userPhone
     * @param string $type      Initialization value for $this->type
     */
    public function __construct()
    {
        switch (func_num_args()) {
            case 3:
                $this->country   = func_get_arg(0);
                $this->userPhone = func_get_arg(1);
                $this->type      = func_get_arg(2);
                break;

            default:
                $this->type = 'sms';
                break;
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['country']    = $this->country;
        $json['user_phone'] = $this->userPhone;
        $json['type']       = $this->type;

        return $json;
    }
}
