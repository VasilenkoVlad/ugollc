<?php
/*
 * ClickSend
 *
 * This file was automatically generated for ClickSend by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace ClickSendLib\Models;

use JsonSerializable;

/**
 *PostLetter model
 */
class PostLetter implements JsonSerializable
{
    /**
     * URL of file to send
     * @required
     * @maps file_url
     * @var string $fileUrl public property
     */
    public $fileUrl;

    /**
     * Whether using our template
     * @maps template_used
     * @var integer|null $templateUsed public property
     */
    public $templateUsed;

    /**
     * Whether letter is duplex
     * @var integer|null $duplex public property
     */
    public $duplex;

    /**
     * Whether letter is in colour
     * @var integer|null $colour public property
     */
    public $colour;

    /**
     * Source being sent from
     * @var string|null $source public property
     */
    public $source;

    /**
     * Array of PostRecipient models
     * @required
     * @var \ClickSendLib\Models\PostRecipient[] $recipients public property
     */
    public $recipients;

    /**
     * Constructor to set initial or default values of member properties
     * @param string  $fileUrl      Initialization value for $this->fileUrl
     * @param integer $templateUsed Initialization value for $this->templateUsed
     * @param integer $duplex       Initialization value for $this->duplex
     * @param integer $colour       Initialization value for $this->colour
     * @param string  $source       Initialization value for $this->source
     * @param array   $recipients   Initialization value for $this->recipients
     */
    public function __construct()
    {
        switch (func_num_args()) {
            case 6:
                $this->fileUrl      = func_get_arg(0);
                $this->templateUsed = func_get_arg(1);
                $this->duplex       = func_get_arg(2);
                $this->colour       = func_get_arg(3);
                $this->source       = func_get_arg(4);
                $this->recipients   = func_get_arg(5);
                break;

            default:
                $this->templateUsed = 0;
                $this->duplex = 0;
                $this->colour = 0;
                $this->source = 'sdk';
                break;
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['file_url']      = $this->fileUrl;
        $json['template_used'] = $this->templateUsed;
        $json['duplex']        = $this->duplex;
        $json['colour']        = $this->colour;
        $json['source']        = $this->source;
        $json['recipients']    = $this->recipients;

        return $json;
    }
}
