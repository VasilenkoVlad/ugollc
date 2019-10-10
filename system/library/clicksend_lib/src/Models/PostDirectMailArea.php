<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 *PostDirectMailArea model
 */
class PostDirectMailArea implements JsonSerializable
{
    /**
     * Location ID to send to
     * @required
     * @maps location_id
     * @var integer $locationId public property
     */
    public $locationId;

    /**
     * Number of items to send
     * @required
     * @var integer $quantity public property
     */
    public $quantity;

    /**
     * Constructor to set initial or default values of member properties
     * @param integer $locationId Initialization value for $this->locationId
     * @param integer $quantity   Initialization value for $this->quantity
     */
    public function __construct()
    {
        if (2 == func_num_args()) {
            $this->locationId = func_get_arg(0);
            $this->quantity   = func_get_arg(1);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['location_id'] = $this->locationId;
        $json['quantity']    = $this->quantity;

        return $json;
    }
}
