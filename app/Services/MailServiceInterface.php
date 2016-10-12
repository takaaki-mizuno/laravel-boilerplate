<?php

namespace App\Services;

interface MailServiceInterface extends BaseServiceInterface
{
    /**
     * @param string $title
     * @param string $from
     * @param string $to
     * @param string $template
     * @param array  $data
     *
     * @return bool
     */
    public function sendMail($title, $from, $to, $template, $data);
}
