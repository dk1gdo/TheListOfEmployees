<?php

namespace App\Classes;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

class Telegram
{
    protected string    $token = "6158072722:AAEorSAQWz_qgYiKrnrln44ChWSesZgZ3zo";

    public function sendMessage($chat_id, $message, $keyboard = '') {


        return Http::post('https://api.telegram.org/bot' . $this->token . '/sendMessage', [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $message,
            'reply_markup' => json_encode($keyboard),
        ]);
    }

    public function deleteMessage($chat_id, $message_id) {
        return Http::post('https://api.telegram.org/bot' . $this->token . '/deleteMessage', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
        ]);
    }

    public function sendDocument($chat_id) {
        /*$client = new Client();
        return $client->request('POST', 'https://api.telegram.org/bot' . $this->token . '/sendDocument?chat_id=' . $chat_id, [
            'multipart' => [
                'name'     => 'document',
                'contents' => Storage::get('EmployeesExport.xml'),
                'filename' => 'EmployeesExport.xml',
                ],
        ]);*/
        return Http::attach(
            'document',
            file_get_contents(base_path() . "/storage/app/EmployeesExport.xlsx"),

            'EmployeesExport.xlsx')
            ->post('https://api.telegram.org/bot' . $this->token . '/sendDocument?chat_id=' . $chat_id);
    }

}
