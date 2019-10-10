<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 *PostRecipient model
 */
class PostRecipient implements JsonSerializable
{
    /**
     * Name of address
     * @required
     * @maps address_name
     * @var string $addressName public property
     */
    public $addressName;

    /**
     * First line of address
     * @required
     * @maps address_line_1
     * @var string $addressLine1 public property
     */
    public $addressLine1;

    /**
     * Second line of address
     * @required
     * @maps address_line_2
     * @var string $addressLine2 public property
     */
    public $addressLine2;

    /**
     * City
     * @required
     * @maps address_city
     * @var string $addressCity public property
     */
    public $addressCity;

    /**
     * State
     * @required
     * @maps address_state
     * @var string $addressState public property
     */
    public $addressState;

    /**
     * Postal code
     * @required
     * @maps address_postal_code
     * @var string $addressPostalCode public property
     */
    public $addressPostalCode;

    /**
     * Country
     * @required
     * @maps address_country
     * @var string $addressCountry public property
     */
    public $addressCountry;

    /**
     * ID of return address to use
     * @required
     * @maps return_address_id
     * @var integer $returnAddressId public property
     */
    public $returnAddressId;

    /**
     * When to send letter (0/null=now)
     * @var integer|null $schedule public property
     */
    public $schedule;

    /**
     * Constructor to set initial or default values of member properties
     * @param string  $addressName       Initialization value for $this->addressName
     * @param string  $addressLine1      Initialization value for $this->addressLine1
     * @param string  $addressLine2      Initialization value for $this->addressLine2
     * @param string  $addressCity       Initialization value for $this->addressCity
     * @param string  $addressState      Initialization value for $this->addressState
     * @param string  $addressPostalCode Initialization value for $this->addressPostalCode
     * @param string  $addressCountry    Initialization value for $this->addressCountry
     * @param integer $returnAddressId   Initialization value for $this->returnAddressId
     * @param integer $schedule          Initialization value for $this->schedule
     */
    public function __construct()
    {
        switch (func_num_args()) {
            case 9:
                $this->addressName       = func_get_arg(0);
                $this->addressLine1      = func_get_arg(1);
                $this->addressLine2      = func_get_arg(2);
                $this->addressCity       = func_get_arg(3);
                $this->addressState      = func_get_arg(4);
                $this->addressPostalCode = func_get_arg(5);
                $this->addressCountry    = func_get_arg(6);
                $this->returnAddressId   = func_get_arg(7);
                $this->schedule          = func_get_arg(8);
                break;

            default:
                $this->schedule = 0;
                break;
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
        $json['return_address_id']   = $this->returnAddressId;
        $json['schedule']            = $this->schedule;

        return $json;
    }
}
