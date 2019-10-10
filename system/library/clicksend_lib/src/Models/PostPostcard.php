<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 *PostPostcard model
 */
class PostPostcard implements JsonSerializable
{
    /**
     * Postcard file URLs
     * @required
     * @maps file_urls
     * @var array $fileUrls public property
     */
    public $fileUrls;

    /**
     * Array of recipients
     * @required
     * @var \ClickSendLib\Models\PostRecipient[] $recipients public property
     */
    public $recipients;

    /**
     * Constructor to set initial or default values of member properties
     * @param array $fileUrls   Initialization value for $this->fileUrls
     * @param array $recipients Initialization value for $this->recipients
     */
    public function __construct()
    {
        if (2 == func_num_args()) {
            $this->fileUrls   = func_get_arg(0);
            $this->recipients = func_get_arg(1);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['file_urls']  = $this->fileUrls;
        $json['recipients'] = $this->recipients;

        return $json;
    }
}
