<?php
/**
 * Example on how to spool a new mail. (This mail will be put as a file in the ./mail/outbox folder)
 */


$mailSpooler = new \Lack\MailSpool\MailSpooler();

$mail = new \Lack\MailSpool\OutgoingMail(["to"=> "mab@test1234.xy", "subject" => "Test Mail"]);
$mail->parts[] =
