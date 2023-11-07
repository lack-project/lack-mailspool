<?php


$mailSpooler = new \Lack\MailSpool\MailSpooler();
$mailSpooler->setDriver(new PhpMailerDriver());


$mails = $mailSpooler->listMails();

foreach ($mails as $mail) {
    echo "\nMail to: " . $mail->to . "\n";
    // Send each mail by mail
    $mailSpooler->sendMail($mail);
}

// Or: Send all spooled mails at once
$mailSpooler->sendAllMails(); // Send all mails in the spool directory
