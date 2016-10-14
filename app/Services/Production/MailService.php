<?php

namespace App\Services\Production;

use App\Services\MailServiceInterface;
use Aws\Ses\SesClient;

class MailService extends BaseService implements MailServiceInterface
{
    public function __construct()
    {
    }

    public function sendMail($title, $from, $to, $template, $data)
    {
        if (config('app.offline_mode')) {
            return true;
        }

        if (\App::environment() != 'production') {
            $title = '['.\App::environment().'] '.$title;
            $to = [
                'address' => config('mail.tester'),
                'name' => \App::environment().' Original: '.$to['address'],
            ];
        }

        $client = new SesClient([
            'credentials' => [
                'key' => config('aws.key'),
                'secret' => config('aws.secret'),
            ],
            'region' => config('aws.ses_region'),
            'version' => 'latest',
        ]);

        try {
            $body = \View::make($template, $data)->render();
            $sesData = [
                'Source' => mb_encode_mimeheader($from['name']).' <'.$from['address'].'>',
                'Destination' => [
                    'ToAddresses' => [$to['address']],
                ],
                'Message' => [
                    'Subject' => [
                        'Data' => $title,
                        'Charset' => 'UTF-8',
                    ],
                    'Body' => [
                        'Html' => [
                            'Data' => $body,
                            'Charset' => 'UTF-8',
                        ],
                    ],
                ],
            ];
            $client->sendEmail($sesData);
        } catch (\Exception $e) {
            echo $e->getMessage(), "\n";
        }

        return true;
    }
}
