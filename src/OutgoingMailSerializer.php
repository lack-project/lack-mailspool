<?php

namespace Lack\MailSpool;

use Http\Client\Exception;
use Lack\MailSpool\Helper\MailIdGenerator;

class OutgoingMailSerializer
{


    public static function LoadFromFile(string $filename) : OutgoingMail {
        try {
            $data = parseFrontMatter(phore_file($filename)->get_contents());
        } catch (\Exception $e) {
            throw new \Exception("Error parsing $filename: " . $e->getMessage());
        }
        $mail = new OutgoingMail();
        foreach($data["metadata"] as $key => $value) {
            if (str_starts_with("_", $key)) {
                $mail->metadata[$key] = $value;
            }  else {
                $mail->headers[$key] = $value;
            }
        }
        $mail->textBody = $data["content"];

        $mailId = basename($filename, ".mail.txt");

        // Check if there are other files in same directory starting with $mailId
        $dir = dirname($filename);
        foreach ($files = phore_dir($dir)->listFiles($mailId . ".*") as $file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);

            if (str_ends_with($file, ".mail.txt"))
                continue;

            // strip mailId from Filename
            $fileName = substr(basename($file), strlen($mailId) + 1);
            if ($ext == "html") {
                $mail->htmlBody = phore_file($file)->get_contents();
            } else {
                $mail->attachments[] = new OutgoingMailAttachment(phore_file($file)->get_contents(), $fileName);
            }
        }

        return $mail;
    }





    public static function SaveToFile(OutgoingMail $mail, string $storePath) : void {


        // Combine assoc array $mail->headers and $mail->metadata into single assoc array
        $headers = array_merge($mail->headers, $mail->metadata);

        $baseName = $storePath . "/" . $mail->getMailSpoolId();

        foreach ($mail->attachments as $attachment) {
            phore_file($baseName . "." . $attachment->filename)->set_contents($attachment->data);
        }
        phore_file($baseName . ".mail.txt")->set_contents(serializeFrontMatter($headers, $mail->textBody));
    }

}
