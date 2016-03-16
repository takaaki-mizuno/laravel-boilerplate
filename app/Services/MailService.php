<?php namespace App\Services;

use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;

use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException;

class MailService
{


    public function __construct()
    {
    }

    public function sendMail($title, $from, $to, $template, $data)
    {
        if (\Config::get('app.offline_mode')) {
            return true;
        }


        if (\App::environment() != 'production') {
            $title = '[' . \App::environment() . '] ' . $title;
            $to = [
                'address' => \Config::get('mail.tester'),
                'name'    => \App::environment() . ' Original: ' . $to['address'],
            ];
        }

        $client = new SesClient([
            'credentials' => [
                'key'    => \Config::get('aws.key'),
                'secret' => \Config::get('aws.secret'),
            ],
            'region'      => \Config::get('aws.ses_region'),
            'version'     => 'latest',
        ]);

        try {
            $body = \View::make($template, $data)->render();
            $sesData = [
                'Source'      => mb_encode_mimeheader($from['name']) . ' <' . $from['address'] . '>',
                'Destination' => [
                    'ToAddresses' => [$to['address']],
                ],
                'Message'     => [
                    'Subject' => [
                        'Data'    => $title,
                        "Charset" => "UTF-8",
                    ],
                    'Body'    => [
                        'Html' => [
                            "Data"    => $body,
                            "Charset" => "UTF-8",
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