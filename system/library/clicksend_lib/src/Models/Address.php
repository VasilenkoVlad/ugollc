<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 *Base model for all address-related objects.
 */
class Address implements JsonSerializable
{
    /**
     * Your address name.
     * @required
     * @maps address_name
     * @var string $addressName public property
     */
    public $addressName;

    /**
     * Your address line 1
     * @required
     * @maps address_line_1
     * @var string $addressLine1 public property
     */
    public $addressLine1;

    /**
     * Your address line 2
     * @maps address_line_2
     * @var string|null $addressLine2 public property
     */
    public $addressLine2;

    /**
     * Your city
     * @required
     * @maps address_city
     * @var string $addressCity public property
     */
    public $addressCity;

    /**
     * Your state
     * @maps address_state
     * @var string|null $addressState public property
     */
    public $addressState;

    /**
     * Your postal code
     * @required
     * @maps address_postal_code
     * @var string $addressPostalCode public property
     */
    public $addressPostalCode;

    /**
     * Your country
     * @required
     * @maps address_country
     * @var string $addressCountry public property
     */
    public $addressCountry;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $addressName       Initialization value for $this->addressName
     * @param string $addressLine1      Initialization value for $this->addressLine1
     * @param string $addressLine2      Initialization value for $this->addressLine2
     * @param string $addressCity       Initialization value for $this->addressCity
     * @param string $addressState      Initialization value for $this->addressState
     * @param string $addressPostalCode Initialization value for $this->addressPostalCode
     * @param string $addressCountry    Initialization value for $this->addressCountry
     */
    public function __construct()
    {
        if (7 == func_num_args()) {
            $this->addressName       = func_get_arg(0);
            $this->addressLine1      = func_get_arg(1);
            $this->addressLine2      = func_get_arg(2);
            $this->addressCity       = func_get_arg(3);
            $this->addressState      = func_get_arg(4);
            $this->addressPostalCode = func_get_arg(5);
            $this->addressCountry    = func_get_arg(6);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['address_name']        = $this->addressName;
        $json['address_line_1']      = $this->addressLine1;
        $json['address_line_2']      = $this->addressLine2;
        $json['address_city']        = $this->addressCity;
        $json['address_state']       = $this->addressState;
        $json['address_postal_code'] = $this->addressPostalCode;
        $json['address_country']     = $this->addressCountry;

        return $json;
    }
}
