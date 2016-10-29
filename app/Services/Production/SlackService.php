<?php

namespace App\Services\Production;

use App\Services\SlackServiceInterface;
use Maknz\Slack\Client;
use Maknz\Slack\Attachment;

class SlackService extends BaseService implements SlackServiceInterface
{
    /**
     * Report an exception to slack.
     *
     * @param \Exception $e
     */
    public function exception(\Exception $e)
    {
        $fields = [];

        $addToField = function ($name, $value, $short = false) use (&$fields) {
            if (!empty($value)) {
                $fields[] = [
                    'title' => $name,
                    'value' => $value,
                    'short' => $short,
                ];
            }
        };

        $addToField('Environment', \App::environment(), true);
        $addToField('Exception', get_class($e), true);
        $addToField('Http code', $e instanceof \Symfony\Component\HttpKernel\Exception\HttpException ? $e->getStatusCode() : 500, true);
        $addToField('Code', $e->getCode(), true);
        $addToField('File', $e->getFile(), true);
        $addToField('Line', $e->getLine(), true);
        $addToField('Request url', \Request::url(), true);
        $addToField('Request method', \Request::method(), true);
        $addToField('Request param', json_encode(\Request::all()), true);

        $message = ':bug: Error Occurs on '.\App::environment();
        $type = 'serious-alert';
        $pretext = 'Error Occurs on '.\App::environment();
        $attachment = [
            'color' => 'danger',
            'title' => $e->getMessage(),
            'fallback' => !empty($e->getMessage()) ? $e->getMessage() : get_class($e),
            'pretext' => $pretext,
            'fields' => $fields,
            'text' => $e->getTraceAsString(),
        ];

        // notify to slack
        $this->post($message, $type, $attachment);
    }

    /**
     * @param string $message
     * @param string $type
     * @param array  $attachment
     */
    public function post($message, $type, $attachment = [])
    {
        $type = config('slack.types.'.strtolower($type), config('slack.default', []));
        $webHookUrl = config('slack.webHookUrl');
        $client = new Client($webHookUrl, [
            'username' => array_get($type, 'username', 'FamarryBot'),
            'channel' => array_get($type, 'channel', '#random'),
            'link_names' => true,
            'icon' => array_get($type, 'icon', ':smile:'),
        ]);
        $messageObj = $client->createMessage();
        if (!empty($attachment)) {
            $attachment = new Attachment([
                'fallback' => array_get($attachment, 'fallback', ''),
                'text' => array_get($attachment, 'text', ''),
                'pretext' => array_get($attachment, 'pretext', ''),
                'color' => array_get($attachment, 'color', 'good'),
                'fields' => array_get($attachment, 'fields', []),
            ]);
            $messageObj->attach($attachment);
        }
        $messageObj->setText($message)->send();
    }
}
