<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\MessageHandler;
use Kiwi26\LINEBotTestHelper\WebhookEvent;
use Kiwi26\LINEBotTestHelper\StockHTTPClient\StockHTTPClient;

class MessageHandlerTest extends TestCase
{
    public function testReply()
    {
        $channelToken = env('SAMPLE_LINE_CHANNEL_TOKEN', 'sample-token');
        $channelSecret = env('SAMPLE_LINE_CHANNEL_SECRET', 'sample-secret');
        $source = WebhookEvent\EventSource::user('dummy-user-id');
        $event = new WebhookEvent\TextMessageEvent($source, 'test message', $channelSecret);
        $client = new StockHTTPClient($channelToken);

        $handler = new MessageHandler($event->body(), $event->signature(), $client);
        $handler->handle();
        
        $this->assertEquals(count($client->queue), 1);
        $this->assertEquals($client->queue[0]->body['messages'][0]['type'], 'text');
        $this->assertEquals($client->queue[0]->body['messages'][0]['text'], 'reply text');
    }

    public function testReplyHello()
    {
        $channelToken = env('SAMPLE_LINE_CHANNEL_TOKEN', 'sample-token');
        $channelSecret = env('SAMPLE_LINE_CHANNEL_SECRET', 'sample-secret');
        $source = WebhookEvent\EventSource::user('dummy-user-id');
        $event = new WebhookEvent\TextMessageEvent($source, 'hello', $channelSecret);
        $client = new StockHTTPClient($channelToken);

        $handler = new MessageHandler($event->body(), $event->signature(), $client);
        $handler->handle();
        
        $this->assertEquals(count($client->queue), 1);
        $this->assertEquals($client->queue[0]->body['messages'][0]['type'], 'text');
        $this->assertEquals($client->queue[0]->body['messages'][0]['text'], 'Hello, my friend!');
    }

}
