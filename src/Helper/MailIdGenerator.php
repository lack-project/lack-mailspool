<?php

namespace Lack\MailSpool\Helper;

class MailIdGenerator
{

    /**
     * Return a 6 character hash string with letters [a-zA-Z0-9] generated from sha
     *
     * @param $subject
     * @param $to
     * @return string
     */
    public static function generateId($subject, $to): string
    {
        $hash = hash("sha256", $subject . $to);
        $hash = substr($hash, 0, 5);
        return $hash;

    }
}
