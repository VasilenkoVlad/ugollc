<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 *Array of MmsMessage items
 */
class MmsMessageCollection implements JsonSerializable
{
    /**
     * Media file you want to send
     * @required
     * @maps media_file
     * @var string $mediaFile public property
     */
    public $mediaFile;

    /**
     * Array of MmsMessage models
     * @required
     * @var \ClickSendLib\Models\MmsMessage[] $messages public property
     */
    public $messages;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $mediaFile Initialization value for $this->mediaFile
     * @param array  $messages  Initialization value for $this->messages
     */
    public function __construct()
    {
        if (2 == func_num_args()) {
            $this->mediaFile = func_get_arg(0);
            $this->messages  = func_get_arg(1);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['media_file'] = $this->mediaFile;
        $json['messages']   = $this->messages;

        return $json;
    }
}
