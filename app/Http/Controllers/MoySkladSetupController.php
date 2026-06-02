<?php
// app/Http/Controllers/MoySkladSetupController.php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

class MoySkladSetupController extends Controller
{
    public function index(): View
    {
        return view('moysklad-setup.index');
    }
}
