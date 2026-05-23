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
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'url', 'max:255'],
            'timezone' => ['required', 'timezone'],
        ]);

        auth()->user()->company->update($validated);

        return redirect()->back()->with('success', 'Company settings updated!');
    }
}
