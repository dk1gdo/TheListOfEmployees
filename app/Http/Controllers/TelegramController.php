<?php

namespace App\Http\Controllers;

use App\Services\TelegramService;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected $tgService;
    public function __construct()
    {
        $this->tgService = new TelegramService();
    }

    public function __invoke(Request $request)
    {
        return $this->tgService->sendMessage($request->getContent());
    }
}
