<?php

namespace App\Http\Controllers;

use App\Models\BotClient;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $company = auth()->user()->company;
        $clients = BotClient::whereHas('bot', fn($q) => $q->where('company_id', $company->id))
            ->with(['tgUser', 'bot'])
            ->paginate(20);

        return view('clients.index', compact('clients'));
    }
}
