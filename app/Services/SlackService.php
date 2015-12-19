<?php namespace App\Services;

use Maknz\Slack\Client;
use Maknz\Slack\Attachment;

class SlackService
{

    /**
     * @param string $message
     * @param string $type
     * @param array $attachment
     */
    public function post($message, $type, $attachment = [])
    {
        $type = \Config::get('slack.types.' . strtolower($type), \Config::get('slack.default', []));
        $webHookUrl = \Config::get('slack.webHookUrl');
        $client = new Client($webHookUrl, [
            'username'   => array_get($type, 'username', 'FamarryBot'),
            'channel'    => array_get($type, 'channel', '#random'),
            'link_names' => true,
            'icon'       => array_get($type, 'icon', ':smile:'),
        ]);
        $messageObj = $client->createMessage();
        if (!empty($attachment)) {
            $attachment = new Attachment([
                'fallback' => array_get($attachment, 'fallback', ''),
                'text'     => array_get($attachment, 'text', ''),
                'pretext'  => array_get($attachment, 'pretext', ''),
                'color'    => array_get($attachment, 'color', 'good'),
                'fields'   => array_get($attachment, 'fields', []),
            ]);
            $messageObj->attach($attachment);
        }
        $messageObj->setText($message)->send();
    }

}