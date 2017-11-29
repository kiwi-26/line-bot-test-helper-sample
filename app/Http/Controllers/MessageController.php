<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MessageHandler;
use LINE\LINEBot\Constant\HTTPHeader;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;

class MessageController extends Controller
{
    public function post(Request $request) {
        $body = $request->getContent();
        $signature = $request->header(HTTPHeader::LINE_SIGNATURE);
        $channelToken = env('SAMPLE_LINE_CHANNEL_TOKEN', 'sample-token');
        $handler = new MessageHandler($body, $signature, new CurlHTTPClient($channelToken));
        $handler->handle();
        return response('Success.', 200);
    }
}
