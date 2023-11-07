<?php

namespace Lack\MailSpool;

class OutgoingMailPart
{

    public function __construct($text = '', $contentType = 'text/plain')
    {
        $this->text = $text;
        $this->contentType = $contentType;
    }

    public $contentType='text/plain';

    public $text = '';

}
