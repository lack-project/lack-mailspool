<?php

namespace Lack\MailSpool;

class OutgoingMailAttachment
{

    
    public function __construct(public string $data, public string $contentType = 'application/octet-stream')
    {
    }

   
    
}
