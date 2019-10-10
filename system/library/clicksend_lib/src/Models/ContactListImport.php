<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 *Contact list import model
 */
class ContactListImport implements JsonSerializable
{
    /**
     * URL of file to process
     * @required
     * @maps file_url
     * @var string $fileUrl public property
     */
    public $fileUrl;

    /**
     * Order of fields in file
     * @required
     * @maps field_order
     * @var array $fieldOrder public property
     */
    public $fieldOrder;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $fileUrl    Initialization value for $this->fileUrl
     * @param array  $fieldOrder Initialization value for $this->fieldOrder
     */
    public function __construct()
    {
        if (2 == func_num_args()) {
            $this->fileUrl    = func_get_arg(0);
            $this->fieldOrder = func_get_arg(1);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['file_url']    = $this->fileUrl;
        $json['field_order'] = $this->fieldOrder;

        return $json;
    }
}
