<?php

namespace Lack\MailSpool\Driver;

use Lack\Keystore\KeyStore;
use Lack\MailSpool\OutgoingMail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class PhpmailerDriver
{

    public function __construct(public string $smptHost, public string $smptPort, public string $smtpUser, public string $smtpPass, public string $smtpSender, public ?string $smtpSenderName = null)
    {
    }



    public function send(OutgoingMail $mail)
    {
        $mailer = new PHPMailer(true);

        $mailer->isSMTP();
        $mailer->addAddress($mail->headers["To"]);
        $mailer->setFrom($this->smtpSender, $this->smtpSenderName);

        if ($mail->headers["Cc"] !== null)
            $mailer->addCC($mail->headers["Cc"]);
        if ($mail->headers["Bcc"] !== null)
            $mailer->addBCC($mail->headers["Bcc"]);


        $mailer->Subject = $mail->headers["Subject"];
        $mailer->Body = $mail->textBody;

        foreach ($mail->attachments as $attachment) {
            $mailer->addStringAttachment($attachment->data, $attachment->filename);
        }

        $mailer->Host = $this->smptHost;
        $mailer->Port = $this->smptPort;
        $mailer->Username = $this->smtpUser;
        $mailer->Password = $this->smtpPass;

        $mailer->SMTPAuth = true;
        $mailer->AuthType = "PLAIN";
        $mailer->CharSet = "UTF-8";
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mailer->Timeout = 10;

        //$mailer->SMTPDebug = SMTP::DEBUG_SERVER;
        $mailer->send();

    }



}
