<?php
// app/Http/Controllers/SettingsController.php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Integration;
use App\Models\IntegrationField;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $integration = Integration::where('user_id', $user->id)
            ->where('type', 'moisklad')
            ->first();

        $fields = IntegrationField::where('integration_type', 'moisklad')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('settings.index', [
            'integration' => $integration,
            'fields' => $fields,
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [];
        $fields = IntegrationField::where('integration_type', 'moisklad')->get();

        foreach ($fields as $field) {
            $rule = [];
            if ($field->is_required) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }
            $rule[] = 'string';
            $rules["credentials.{$field->field_key}"] = implode('|', $rule);
        }

        $validated = $request->validate(['credentials' => 'required|array'] + $rules);

        $integration = Integration::firstOrCreate(
            [
                'user_id' => $user->id,
                'type' => 'moisklad',
            ],
            [
                'credentials' => $validated['credentials'],
                'is_active' => true,
            ]
        );

        if ($integration->wasRecentlyCreated === false) {
            $integration->update(['credentials' => $validated['credentials']]);
        }

        return redirect()->route('settings.index')->with('success', 'МойСклад credentials saved successfully!');
    }
}
