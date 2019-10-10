<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 *Base64-encoded file contents
 */
class FileContent implements JsonSerializable
{
    /**
     * Base64-encoded file contents
     * @required
     * @var string $content public property
     */
    public $content;

    /**
     * Constructor to set initial or default values of member properties
     * @param string $content Initialization value for $this->content
     */
    public function __construct()
    {
        if (1 == func_num_args()) {
            $this->content = func_get_arg(0);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['content'] = $this->content;

        return $json;
    }
}
