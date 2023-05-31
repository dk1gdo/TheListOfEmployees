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
        $tmpdata = json_decode(file_get_contents("php://input"),true);

        $arrdataapi = print_r($tmpdata, true);
        //file_put_contents('apidata.txt', "Данные от бота: $arrdataapi", FILE_APPEND);
        Storage::append("apidata.log", "Данные от бота " . $arrdataapi);
        Storage::append("test.log", time() . " => " . $request->getContent());

        $tg = new Telegram();
        $r = $tg->sendMessage($h->message->chat->id, $h->message->text);

        /*$r = Http::post('https://api.telegram.org/bot' . $this->token . '/sendMessage', [
            'chat_id' => $h->message->chat->id,
            'parse_mode' => 'HTML',
            'text' => 'You message => [' . $h->message->text . "]",
        ]);*/

        return $r;
    }
}
