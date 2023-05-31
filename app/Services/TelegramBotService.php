<?php

namespace App\Services;

use http\Client\Request;

class TelegramBotService
{

    public function __construct(Request $request)
    {
        $tgRequest = json_decode($request->getContent());

        if (!is_null()) {
            
        }


    }



}
