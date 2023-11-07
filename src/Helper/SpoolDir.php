<?php

namespace Lack\MailSpool\Helper;

/**
 * Will spool mails in a directory
 *
 * The headers are stored in <mail_id>.mail.yml.
 * The Body parts are stored as <mail_id>.<ext>
 * Attachments are stored in <mail_id>.<attachment_id>.<ext>
 *    - mail_id is a unique id for the mail
 *    - attachment_id is a unique id for the attachment (e.g. Filename)
 */
class SpoolDir
{

    public function __construct(public string $dir)
    {
    }

    public function spoolMail() {

    }

    /**
     * @return string[]
     */
    public function listMailIds() : array {
        $mailIds = [];
        // find all .mail.yml files
        $files = glob($this->dir . '/*.mail.yml');
        foreach ($files as $file) {
            $mailIds[] = basename($file, '.mail.yml');
        }
        return $mailIds;

    }

}
