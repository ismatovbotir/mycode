<?php

namespace App\Http\Controllers\Auth;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisterController
{
    public function create(): View
    {
        return view('auth.register-new');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'inn' => ['required', 'string', 'size:9'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'lang' => ['required', 'in:uz,kk,kz,tj,ru'],
            'country_code' => ['required', 'string', 'regex:/^\+\d{1,3}$/'],
            'phone' => ['required', 'string', 'regex:/^\d{9,}$/', 'max:20'],
        ]);

        $validated['phone'] = $validated['country_code'] . $validated['phone'];

        $company = Company::create([
            'uuid' => Str::uuid(),
            'name' => $validated['company_name'],
            'inn' => $validated['inn'],
            'email' => $validated['email'],
            'timezone' => 'Asia/Tashkent',
        ]);

        $user = User::create([
            'company_id' => $company->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'lang' => $validated['lang'] ?? 'uz',
            'phone' => $validated['phone'],
            'role' => 'admin',
        ]);

        event(new Registered($user));

        auth()->login($user);

        return redirect('/dashboard')->with('success', 'Company registered successfully!');
    }
}
