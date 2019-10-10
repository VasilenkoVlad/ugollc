<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 *Array of FaxMessage items
 */
class FaxMessageCollection implements JsonSerializable
{
    /**
     * Array of FaxMessage items
     * @required
     * @var \ClickSendLib\Models\FaxMessage[] $messages public property
     */
    public $messages;

    /**
     * URL of file to send
     * @required
     * @maps file_url
     * @var string $fileUrl public property
     */
    public $fileUrl;

    /**
     * Constructor to set initial or default values of member properties
     * @param array  $messages Initialization value for $this->messages
     * @param string $fileUrl  Initialization value for $this->fileUrl
     */
    public function __construct()
    {
        if (2 == func_num_args()) {
            $this->messages = func_get_arg(0);
            $this->fileUrl  = func_get_arg(1);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['messages'] = $this->messages;
        $json['file_url'] = $this->fileUrl;

        return $json;
    }
}
