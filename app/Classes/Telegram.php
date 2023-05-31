<?php

namespace App\Classes;

use Illuminate\Support\Facades\Http;

class Telegram
{
    protected string    $token = "6158072722:AAEorSAQWz_qgYiKrnrln44ChWSesZgZ3zo";

    public function sendMessage($chat_id, $message) {
        return Http::post('https://api.telegram.org/bot' . $this->token . '/sendMessage', [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => 'You message => [' . $message . "]",
            'reply_markup' => json_encode(array(
                'keyboard' => array(
                    array(
                        array(
                            'text' => 'Тестовая кнопка 1',
                            'url' => 'YOUR BUTTON URL',
                        ),
                        array(
                            'text' => 'Тестовая кнопка 2',
                            'url' => 'YOUR BUTTON URL',
                        ),
                    )
                ),
                'one_time_keyboard' => TRUE,
                'resize_keyboard' => TRUE,
            )),
        ]);
    }


}
