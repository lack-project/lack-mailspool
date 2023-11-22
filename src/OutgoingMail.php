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
        return MailIdGenerator::generateId($this->headers["To"], $this->headers["Subject"]) . "-" . addslashes((string)$this->headers["To"]);

    }

    /**
     * Load mail from File and replace {{name}} with $data[name]
     *
     * @param string $templateFile
     * @param array $data
     * @return $this
     * @throws \Exception
     */
    public static function FromTemplate(string $templateFile, array $data = [], \Closure $dataLoader = null) : self {
        $mail = OutgoingMailSerializer::LoadFromFile($templateFile);

        $parser = function ($input, bool $sanitizeheader = false) use ($data, $templateFile, $dataLoader) {
            if ($input === null)
                return null;


            if ($sanitizeheader) {
                $ret = str_replace("\r\n", "\n", $input);
                $ret = str_replace("\r", "\n", $ret);
                $input = str_replace("\n", "\r\n", $ret);
            }


            return preg_replace_callback("/{{\s*([a-zA-Z0-9_.]+)\s*}}/", function ($matches) use ($data, $templateFile, $dataLoader, $sanitizeheader) {
                $key = $matches[1];
                $ret = phore_pluck($key, $data);
                if (! $ret) {
                    if ($dataLoader !== null) {
                        $data[$key] = $ret = $dataLoader($key);
                    } else {
                        throw new \InvalidArgumentException("Template variable '{$matches[1]}' not found in data (template: $templateFile)");
                    }

                }

                if ($sanitizeheader) {
                    $ret = str_replace("\r\n", "\n", $ret);
                    $ret = str_replace("\r", "\n", $ret);
                    $ret = str_replace("\n", "\r\n", $ret);
                }
                return $ret;
            }, $input);
        };


        foreach ($mail->headers as $key => $val) {
            $mail->headers[$key] = $parser($val);
        }
        $mail->textBody = $parser($mail->textBody);

        return $mail;
    }


}
