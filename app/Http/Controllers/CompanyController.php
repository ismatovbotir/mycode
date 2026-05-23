<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function settings(): View
    {
        $company = auth()->user()->company;
        return view('company.settings', compact('company'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'country_code' => ['nullable', 'string', 'regex:/^\+\d{1,3}$/'],
            'phone' => ['nullable', 'string', 'regex:/^\d{9,}$/', 'max:20'],
            'website' => ['nullable', 'url', 'max:255'],
            'timezone' => ['required', 'timezone'],
        ]);

        if ($validated['country_code'] && $validated['phone']) {
            $validated['phone'] = $validated['country_code'] . $validated['phone'];
        } elseif (!$validated['phone']) {
            $validated['phone'] = null;
        }

        unset($validated['country_code']);

        auth()->user()->company->update($validated);

        return redirect()->back()->with('success', 'Company settings updated!');
    }
}
