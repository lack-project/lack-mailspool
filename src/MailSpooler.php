<?php

namespace Lack\MailSpool;

use Brix\Core\Type\BrixEnv;
use Lack\MailSpool\Driver\PhpmailerDriver;
use Phore\FileSystem\PhoreDirectory;

class MailSpooler
{


    public function __construct(public string|PhoreDirectory $spoolDir, public string|null|PhoreDirectory $historyDir = null)
    {
        $this->spoolDir = phore_dir($spoolDir);
        if ($this->historyDir !== null)
            $this->historyDir = phore_dir($historyDir);
    }


    public function spoolMail(OutgoingMail $mail)
    {
        OutgoingMailSerializer::SaveToFile($mail, $this->spoolDir);
    }


    /**
     * @return OutgoingMail[]
     */
    public function list() {
        $files = $this->spoolDir->listFiles("*.mail.txt");
        $mails = [];
        foreach ($files as $file) {
            $mails[] = OutgoingMailSerializer::LoadFromFile($file);
        }
        return $mails;
    }


    private $driver;

    public function setDriver(PhpmailerDriver $driver) {
        $this->driver = $driver;
    }


    public function delete(OutgoingMail $mail) {
        OutgoingMailSerializer::Delete($mail);
    }

    /**
     * Send the outgoing mail and move it to history (if enabled)
     *
     * @param OutgoingMail $mail
     * @param PhpmailerDriver|null $driver
     * @return void
     */
    public function send(OutgoingMail $mail, PhpmailerDriver $driver = null) {
        if ($driver === null)
            $driver = $this->driver;
        $driver->send($mail);
        if ($this->historyDir !== null) {
            $mail->metadata["sent"] = date("Y-m-d H:i:s");
            OutgoingMailSerializer::SaveToFile($mail, $this->historyDir);
        }
        OutgoingMailSerializer::Delete($mail);
    }


}
