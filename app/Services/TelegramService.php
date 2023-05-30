<?php

namespace App\Services;

class TelegramService
{
    private string $token = "6158072722:AAEorSAQWz_qgYiKrnrln44ChWSesZgZ3zo";
    public function sendMessage($data) {
        $ch = curl_init();
        $ch_post = [
            CURLOPT_URL => 'https://api.telegram.org/bot' . $this->token . '/sendMessage',
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POSTFIELDS => [
                'parse_mode' => 'HTML',
                'text' => $data,
            ]
        ];

        curl_setopt_array($ch, $ch_post);
        curl_exec($ch);
    }

}
