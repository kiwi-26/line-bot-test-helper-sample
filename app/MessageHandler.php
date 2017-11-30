<?php

namespace App;

use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient;
use LINE\LINEBot\Event\MessageEvent\TextMessage;

class MessageHandler
{
    private $bot;
    private $events;

    function __construct(string $body, string $signature, HTTPClient $client) {
        $channelSecret = env('SAMPLE_LINE_CHANNEL_SECRET', 'sample-secret');
        $this->bot = new LINEBot($client, ['channelSecret' => $channelSecret]);
        $this->events = $this->bot->parseEventRequest($body, $signature);
    }

    function handle() {
        foreach ($this->events as $event) {
            if ($event instanceof TextMessage) {
                switch ($event) {
                    case 'hello':
                        $this->bot->replyText($event->getReplyToken(), 'Hello, my friend!');
                        break;
                    default:
                        $this->bot->replyText($event->getReplyToken(), 'reply text');
                        break;
                }
            }
        }
    }
}
