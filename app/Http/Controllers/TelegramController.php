<?php

namespace App\Http\Controllers;

use App\Classes\Telegram;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TelegramController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    private string $token = "6158072722:AAEorSAQWz_qgYiKrnrln44ChWSesZgZ3zo";
    public function __invoke(Request $request)
    {
        $h = json_decode($request->getContent());
        if (is_null($h)) return false;
        Storage::append("test.log", time() . " => " . json_decode($h));

        /*$tg = new Telegram();
        $tg->sendMessage($h->message->chat->id, $h->message->text);*/
        return Http::post('https://api.telegram.org/bot' . $this->token . '/sendMessage', [
            'chat_id' => $h->message->chat->id,
            'parse_mode' => 'HTML',
            'text' => 'You message => [' . $h->message->text . "]",
        ]);
    }
}
