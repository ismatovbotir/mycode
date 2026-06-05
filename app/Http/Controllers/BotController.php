<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\BotClient;
use Illuminate\View\View;

class BotController extends Controller
{
    public function index()
    {
        $bot = auth()->user()->bot;
        return view('bots.index', ['bot' => $bot]);
    }

    public function toggleActive(Bot $bot)
    {
        $this->authorize('update', $bot);
        $bot->update(['is_active' => !$bot->is_active]);
        return redirect()->back()->with('success', 'Bot status updated!');
    }

    public function clients(Bot $bot): View
    {
        $this->authorize('view', $bot);
        $clients = $bot->clients()
            ->with('tgUser')
            ->paginate(20);
        return view('bots.clients', compact('bot', 'clients'));
    }

    public function approveClient(Bot $bot, BotClient $client)
    {
        $this->authorize('update', $bot);

        $client->update([
            'approved' => true,
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Client approved!');
    }

    public function rejectClient(Bot $bot, BotClient $client)
    {
        $this->authorize('update', $bot);
        $client->delete();

        return redirect()->back()->with('success', 'Client rejected and removed!');
    }
}
