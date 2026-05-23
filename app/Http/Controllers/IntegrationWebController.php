<?php

namespace App\Http\Controllers;

use App\Models\Integration;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IntegrationWebController extends Controller
{
    public function index(): View
    {
        $integrations = auth()->user()->company->integrations;
        return view('integrations.index', compact('integrations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:moisklad'],
            'api_token' => ['required', 'string'],
        ]);

        Integration::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'company_id' => auth()->user()->company_id,
            'type' => $validated['type'],
            'credentials' => ['api_token' => encrypt($validated['api_token'])],
            'settings' => [],
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Integration added successfully!');
    }

    public function destroy(Integration $integration)
    {
        $this->authorize('delete', $integration);
        $integration->delete();
        return redirect()->back()->with('success', 'Integration removed!');
    }
}
