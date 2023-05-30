<?php

namespace App\Http\Controllers;

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
        $tmpdata = json_decode(file_get_contents("php://input"),true);

        $arrdataapi = print_r($tmpdata, true);

        //file_put_contents('apidata.txt', "Данные от бота: $arrdataapi", FILE_APPEND);
        Storage::append("apidata.log", "Данные от бота " . $arrdataapi);
        Storage::append("test.log", time() . " => " . $request->getContent());
        $r = Http::post('https://api.telegram.org/bot' . $this->token . '/sendMessage', [
            'parse_mode' => 'HTML',
            'text' => 'hey!',
        ]);
        $ch = curl_init();
        $ch_post = [
            CURLOPT_URL => 'https://api.telegram.org/bot' . $this->token . '/sendMessage',
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POSTFIELDS => [
                'parse_mode' => 'HTML',
                'text' => $request->getContent(),
            ]
        ];

        curl_setopt_array($ch, $ch_post);
        curl_exec($ch);
        //return 2;
    }
}
