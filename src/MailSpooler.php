<?php

namespace Lack\MailSpool;

class MailSpooler
{



    public function spoolMail(OutgoingMail $mail)
    {
        $this->spoolMailToDb($mail);
    }







}
