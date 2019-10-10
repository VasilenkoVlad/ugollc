<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 *Credit card model
 */
class CreditCard implements JsonSerializable
{
    /**
     * Credit card number
     * @required
     * @var string $number public property
     */
    public $number;

    /**
     * Expiry month of credit card
     * @required
     * @maps expiry_month
     * @var integer $expiryMonth public property
     */
    public $expiryMonth;

    /**
     * Expiry year of credit card
     * @required
     * @maps expiry_year
     * @var integer $expiryYear public property
     */
    public $expiryYear;

    /**
     * CVC number of credit card
     * @required
     * @var integer $cvc public property
     */
    public $cvc;

    /**
     * Name printed on credit card
     * @required
     * @var string $name public property
     */
    public $name;

    /**
     * Name of bank that credit card belongs to
     * @required
     * @maps bank_name
     * @var string $bankName public property
     */
    public $bankName;

    /**
     * Constructor to set initial or default values of member properties
     * @param string  $number      Initialization value for $this->number
     * @param integer $expiryMonth Initialization value for $this->expiryMonth
     * @param integer $expiryYear  Initialization value for $this->expiryYear
     * @param integer $cvc         Initialization value for $this->cvc
     * @param string  $name        Initialization value for $this->name
     * @param string  $bankName    Initialization value for $this->bankName
     */
    public function __construct()
    {
        if (6 == func_num_args()) {
            $this->number      = func_get_arg(0);
            $this->expiryMonth = func_get_arg(1);
            $this->expiryYear  = func_get_arg(2);
            $this->cvc         = func_get_arg(3);
            $this->name        = func_get_arg(4);
            $this->bankName    = func_get_arg(5);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['number']       = $this->number;
        $json['expiry_month'] = $this->expiryMonth;
        $json['expiry_year']  = $this->expiryYear;
        $json['cvc']          = $this->cvc;
        $json['name']         = $this->name;
        $json['bank_name']    = $this->bankName;

        return $json;
    }
}
