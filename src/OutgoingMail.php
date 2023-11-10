<?php

namespace Lack\MailSpool;

use Lack\MailSpool\Helper\MailIdGenerator;

class OutgoingMail
{

    /**
     * @var array
     */
    public $headers = [
        "To" => null,
        "Subject" => null,
        "From" => null,
        "Reply-To" => null,
        "Cc" => null,
        "Bcc" => null,
        "Date" => null,
        "Message-ID" => null,
        "MIME-Version" => "1.0",
        "Content-Type" => "multipart/mixed",
        "Content-Transfer-Encoding" => "8bit",
        "X-Mailer" => "Mailer/1.1"
    ];

    /**
     * Metadata keys must start with _
     * 
     * @var array
     */
    public $metadata = [];

    
    public function setSubject(string $subject) : self {
        $this->headers["Subject"] = $subject;
        return $this;
    }
    
    public function setTo(string $to) : self {
        $this->headers["To"] = $to;
        return $this;
    }
    
    
    /**
     * @var OutgoingMailPart[]
     */
    public string|null $textBody = null;

    public string|null $htmlBody = null;

    /**
     * @var OutgoingMailAttachment[]
     */
    public $attachments = [];


    public function getMailSpoolId() : string {
        return MailIdGenerator::generateId($mail->headers["To"], $mail->headers["Subject"]) . "-" . addslashes($mail->headers["To"]);

    }
    

}
