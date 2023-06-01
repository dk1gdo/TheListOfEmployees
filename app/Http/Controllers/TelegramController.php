<?php

namespace App\Http\Controllers;

use App\Classes\Telegram;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Repositories\EmployeeRepository;
use App\Services\TelegramBotService;
use http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TelegramController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function __invoke(Request $request)
    {
        $tg = new Telegram();
        $h = json_decode($request->getContent());
        Storage::append("test.log", date('D, d M Y H:i:s') . " => " . print_r($request->getContent(), true));
        $bot = new TelegramBotService($h);
        $bot->action();

        //$tg->sendMessage($h->callback_data->message->chat->id, print_r($h, true));


       /* if (is_null($h)) return false;

        if(!isset($h->message->text)) return  $tg->sendMessage($h->message->chat->id, "Я не знаю что с этим делать:-(");
        $r = $tg->sendMessage($h->message->chat->id, $h->message->text);*/

        /*$r = Http::post('https://api.telegram.org/bot' . $this->token . '/sendMessage', [
            'chat_id' => $h->message->chat->id,
            'parse_mode' => 'HTML',
            'text' => 'You message => [' . $h->message->text . "]",
        ]);*/

        /*return $r;*/
    }
}
