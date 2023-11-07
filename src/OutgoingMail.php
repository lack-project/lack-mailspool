<?php

namespace Lack\MailSpool;

class OutgoingMail
{

    /**
     * @var array
     */
    public $headers = [];

    /**
     * @var OutgoingMailPart[]
     */
    public $bodys = [];

    /**
     * @var OutgoingMailAttachment[]
     */
    public $attachments = [];

}
