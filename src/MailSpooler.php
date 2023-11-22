<?php

namespace Lack\MailSpool;

use Brix\Core\Type\BrixEnv;
use Lack\MailSpool\Driver\PhpmailerDriver;
use Phore\FileSystem\PhoreDirectory;

class MailSpooler
{


    public function __construct(public string|PhoreDirectory $spoolDir, public null|string|PhoreDirectory $historyDir = null)
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

    public function getMail(string $mailSpoolId) : ?OutgoingMail {
        $mails = $this->list();
        foreach ($mails as $mail) {
            if ($mail->getMailSpoolId() === $mailSpoolId)
                return $mail;
        }
        return null;
    }


    private $driver;

    public function setDriver(PhpmailerDriver $driver) {
        $this->driver = $driver;
    }




    /**
     * Send the outgoing mail and move it to history (if enabled)
     *
     * @param OutgoingMail $mail
     * @param PhpmailerDriver|null $driver
     * @return void
     */
    public function send(OutgoingMail $mail, PhpmailerDriver $driver = null, bool $delete = true) {
        if ($driver === null)
            $driver = $this->driver;
        $driver->send($mail);

        if ($delete === false)
            return;

        if ($this->historyDir !== null) {
            $mail->metadata["sent"] = date("Y-m-d H:i:s");
            OutgoingMailSerializer::SaveToFile($mail, $this->historyDir);
        }
        $this->delete($mail);
    }


    public function delete(OutgoingMail $mail) {

        foreach ($this->spoolDir->listFiles("*.mail.txt") as $file) {
            $mail = OutgoingMailSerializer::LoadFromFile($file);
            if ($mail->getMailSpoolId() !== $mail->getMailSpoolId()) {
                continue;
            }
            $file->unlink();
            // Unlink all files with same mailId
            foreach ($this->spoolDir->listFiles($file->getBasename(".mail.txt") . ".*") as $attFile) {
                $attFile->unlink();
            }

        }


    }


}
