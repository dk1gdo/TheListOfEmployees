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
                            'text' => 'Добавить сотрудника',
                            'url' => '/add',
                        ),
                        array(
                            'text' => 'Действующие сотрудники',
                            'url' => '/current',
                        ),
                    ),
                    array(
                        array(
                            'text' => 'Уволенные сотрудники',
                            'url' => '/fired',
                        ),
                        array(
                            'text' => 'Excel-файл',
                            'url' => '/excel',
                        ),
                    ),
                ),
                'one_time_keyboard' => TRUE,
                'resize_keyboard' => TRUE,
            )),
        ]);
    }


}
