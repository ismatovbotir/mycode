<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\Integration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class IntegrationWebController extends Controller
{
    public function index(Bot $bot): View
    {
        $this->authorize('view', $bot);
        $integrations = $bot->integrations;
        return view('integrations.index', compact('bot', 'integrations'));
    }

    public function store(Request $request, Bot $bot)
    {
        $this->authorize('update', $bot);

        $validated = $request->validate([
            'type' => ['required', 'in:moisklad'],
            'api_token' => ['required', 'string'],
        ]);

        Integration::create([
            'bot_id' => $bot->id,
            'type' => $validated['type'],
            'credentials' => ['api_token' => encrypt($validated['api_token'])],
            'settings' => [],
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Integration added successfully!');
    }

    public function destroy(Bot $bot, Integration $integration)
    {
        $this->authorize('update', $bot);
        abort_if($integration->bot_id !== $bot->id, 403);
        $integration->delete();
        return redirect()->back()->with('success', 'Integration removed!');
    }
}
