<?php

namespace Lack\MailSpool\Driver;

use Lack\Keystore\KeyStore;
use Lack\MailSpool\OutgoingMail;
use PHPMailer\PHPMailer\PHPMailer;

class PhpmailerDriver
{

    public function __construct(public string $smptHost, public string $smptPort, public string $smtpUser, public string $smtpPass, public string $smtpSender, public ?string $smtpSenderName = null)
    {
    }



    public function send(OutgoingMail $mail)
    {
        $mailer = new PHPMailer(true);

        $mailer->addAddress($mail->headers["To"]);
        $mailer->setFrom($this->smtpSender, $this->smtpSenderName);
        $mailer->Subject = $mail->headers["Subject"];
        $mailer->Body = $mail->textBody;

        foreach ($mail->attachments as $attachment) {
            $mailer->addStringAttachment($attachment->data, $attachment->filename);
        }

        $mailer->isSMTP();
        $mailer->Host = $this->smptHost;
        $mailer->Port = $this->smptPort;
        $mailer->Username = $this->smtpUser;
        $mailer->Password = $this->smtpPass;
        $mailer->SMTPAuth = true;
        $mailer->SMTPSecure = "tls";

        $mailer->send();

    }



}
