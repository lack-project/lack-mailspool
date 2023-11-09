<?php

namespace Lack\MailSpool;

use Brix\Core\Type\BrixEnv;
use Phore\FileSystem\PhoreDirectory;

class MailSpooler
{


    public function __construct(public string|PhoreDirectory $spoolDir)
    {
        $this->spoolDir = phore_dir($spoolDir);
    }


    public function spoolMail(OutgoingMail $mail)
    {
        $this->spoolMailToDb($mail);
    }







}
