<?php

namespace App\Http\Controllers;

use App\Models\BotClient;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $bot = auth()->user()->bot;
        if (!$bot) {
            return view('clients.index', ['clients' => collect()]);
        }

        $clients = $bot->clients()->with('tgUser')->paginate(20);
        return view('clients.index', compact('clients'));
    }
}
