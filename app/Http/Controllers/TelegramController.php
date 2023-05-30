<?php

namespace App\Http\Controllers;

use App\Services\TelegramService;
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
        $service = new TelegramService();
        $service->sendMessage($request->getContent());
        return 2;
    }
}
