<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\BotClient;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BotController extends Controller
{
    public function index(): View
    {
        $bots = auth()->user()->company->bots()->paginate(10);
        return view('bots.index', compact('bots'));
    }

    public function show(Bot $bot): View
    {
        $this->authorize('view', $bot);
        return view('bots.show', compact('bot'));
    }

    public function update(Request $request, Bot $bot)
    {
        $this->authorize('update', $bot);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'requires_admin_approval' => ['boolean'],
            'greeting' => ['required', 'array'],
            'greeting.uz' => ['required', 'string', 'max:500'],
            'greeting.kk' => ['nullable', 'string', 'max:500'],
            'greeting.kz' => ['nullable', 'string', 'max:500'],
            'greeting.tj' => ['nullable', 'string', 'max:500'],
            'greeting.ru' => ['nullable', 'string', 'max:500'],
            'about' => ['required', 'array'],
            'about.uz' => ['required', 'string', 'max:1000'],
            'about.kk' => ['nullable', 'string', 'max:1000'],
            'about.kz' => ['nullable', 'string', 'max:1000'],
            'about.tj' => ['nullable', 'string', 'max:1000'],
            'about.ru' => ['nullable', 'string', 'max:1000'],
        ]);

        foreach (['kk', 'kz', 'tj', 'ru'] as $lang) {
            if (empty($validated['greeting'][$lang])) {
                $validated['greeting'][$lang] = $validated['greeting']['uz'];
            }
            if (empty($validated['about'][$lang])) {
                $validated['about'][$lang] = $validated['about']['uz'];
            }
        }

        $bot->update([
            'name' => $validated['name'],
            'requires_admin_approval' => $validated['requires_admin_approval'] ?? false,
            'content' => [
                'greeting' => $validated['greeting'],
                'about' => $validated['about'],
            ],
        ]);

        return redirect()->back()->with('success', 'Bot updated successfully!');
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
