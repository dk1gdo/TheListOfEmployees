<?php

namespace App\Classes;

use Illuminate\Support\Facades\Http;

class Telegram
{
    protected string $token = "6158072722:AAEorSAQWz_qgYiKrnrln44ChWSesZgZ3zo";

    public function sendMessage($chat_id, $message) {
        return Http::post('https://api.telegram.org/bot' . $this->token . '/sendMessage', [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => 'You message => [' . $message . "]",
        ]);
    }


}
