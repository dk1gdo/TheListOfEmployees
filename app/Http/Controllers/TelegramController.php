<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return $request;
    }
}
