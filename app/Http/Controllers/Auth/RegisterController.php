<?php
// app/Http/Controllers/Auth/RegisterController.php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(Request $request): View
    {
        $locale = $request->route('locale') ?? app()->getLocale();

        return view('auth.register-new', compact('locale'));
    }

    public function store(Request $request): RedirectResponse
    {
        $locale = $request->route('locale') ?? 'uz';

        $validated = $request->validate([
            'brand_name'   => ['required', 'string', 'max:255'],
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
            'lang'         => ['required', 'in:uz,en,ru'],
            'country_code' => ['required', 'string', 'regex:/^\+\d{1,3}$/'],
            'phone'        => ['required', 'string', 'regex:/^\d{9,}$/', 'max:20'],
        ]);

        $phone = $validated['country_code'] . $validated['phone'];

        $user = User::create([
            'name'       => $validated['name'],
            'brand_name' => $validated['brand_name'],
            'email'      => $validated['email'],
            'password'   => $validated['password'],
            'lang'       => $validated['lang'],
            'phone'      => $phone,
            'role'       => 'admin',
        ]);

        event(new Registered($user));

        auth()->login($user);

        return redirect()->route('dashboard')->with('success', 'Account registered successfully!');
    }
}
