<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 * @todo Write general description for this model
 */
class AccountForgotPasswordVerify implements JsonSerializable
{
    /**
     * ID of subaccount
     * @required
     * @maps subaccount_id
     * @var integer $subaccountId public property
     */
    public $subaccountId;

    /**
     * Activation token
     * @required
     * @maps activation_token
     * @var string $activationToken public property
     */
    public $activationToken;

    /**
     * Password
     * @required
     * @var string $password public property
     */
    public $password;

    /**
     * Constructor to set initial or default values of member properties
     * @param integer $subaccountId    Initialization value for $this->subaccountId
     * @param string  $activationToken Initialization value for $this->activationToken
     * @param string  $password        Initialization value for $this->password
     */
    public function __construct()
    {
        if (3 == func_num_args()) {
            $this->subaccountId    = func_get_arg(0);
            $this->activationToken = func_get_arg(1);
            $this->password        = func_get_arg(2);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['subaccount_id']    = $this->subaccountId;
        $json['activation_token'] = $this->activationToken;
        $json['password']         = $this->password;

        return $json;
    }
}
