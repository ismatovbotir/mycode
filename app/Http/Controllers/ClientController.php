<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $company = auth()->user()->company;
        $clients = $company->bots()
            ->with(['clients.tgUser'])
            ->get()
            ->flatMap(fn($bot) => $bot->clients)
            ->paginate(20);

        return view('clients.index', compact('clients'));
    }
}
