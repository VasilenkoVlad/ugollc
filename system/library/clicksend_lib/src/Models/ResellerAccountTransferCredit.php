<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 *ResellerAccountTransferCredit model
 */
class ResellerAccountTransferCredit implements JsonSerializable
{
    /**
     * User ID of client
     * @required
     * @maps client_user_id
     * @var integer $clientUserId public property
     */
    public $clientUserId;

    /**
     * Balance to transfer
     * @required
     * @var integer $balance public property
     */
    public $balance;

    /**
     * Currency of balance to transfer
     * @required
     * @var string $currency public property
     */
    public $currency;

    /**
     * Constructor to set initial or default values of member properties
     * @param integer $clientUserId Initialization value for $this->clientUserId
     * @param integer $balance      Initialization value for $this->balance
     * @param string  $currency     Initialization value for $this->currency
     */
    public function __construct()
    {
        if (3 == func_num_args()) {
            $this->clientUserId = func_get_arg(0);
            $this->balance      = func_get_arg(1);
            $this->currency     = func_get_arg(2);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['client_user_id'] = $this->clientUserId;
        $json['balance']        = $this->balance;
        $json['currency']       = $this->currency;

        return $json;
    }
}
