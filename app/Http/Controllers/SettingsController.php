<?php
// app/Http/Controllers/SettingsController.php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Integration;
use App\Models\IntegrationField;
use Bacon\BaconQrCode\Renderer\Image\SvgImageBackEnd;
use Bacon\BaconQrCode\Renderer\ImageRenderer;
use Bacon\BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $bot = $user->bot;

        $integration = $bot ? Integration::where('bot_id', $bot->id)
            ->where('type', 'moisklad')
            ->first() : null;

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
                'bot_id' => $bot->id,
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

    public function generateTelegramLinkQrCode()
    {
        $user = auth()->user();

        // If already linked, don't generate new QR code
        if ($user->tg_chat_id) {
            return response()->json([
                'already_linked' => true,
                'tg_chat_id' => $user->tg_chat_id,
            ]);
        }

        // Generate unique link token
        $linkToken = Str::random(32);

        // Store token in cache (expires in 10 minutes)
        Cache::put("telegram_link_token:{$linkToken}", [
            'user_id' => $user->id,
            'bot_id' => $user->bot->id,
            'created_at' => now(),
        ], now()->addMinutes(10));

        // Generate bot link with token
        $botUsername = $user->bot->tg_username;
        $deepLink = "https://t.me/{$botUsername}?start=link_{$linkToken}";

        // Generate QR code SVG
        $renderer = new ImageRenderer(
            new SvgImageBackEnd(),
            new \Bacon\BaconQrCode\Renderer\RendererStyle\RendererStyle(300)
        );
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($deepLink);

        return response()->json([
            'already_linked' => false,
            'qr_code' => $qrCodeSvg,
            'link_token' => $linkToken,
            'expires_in' => 600, // 10 minutes
            'deep_link' => $deepLink,
        ]);
    }

    public function checkLinkStatus(string $linkToken)
    {
        $user = auth()->user();
        $isLinked = Cache::get("telegram_link_status:{$linkToken}", false);

        // Also check if user was linked directly
        if (!$isLinked && $user->tg_chat_id) {
            $isLinked = true;
        }

        return response()->json([
            'linked' => $isLinked,
            'tg_chat_id' => $user->tg_chat_id,
        ]);
    }

    public function unlinkTelegram()
    {
        $user = auth()->user();

        $user->update([
            'tg_chat_id' => null,
            'tg_linked_at' => null,
        ]);

        Log::info('User unlinked Telegram account', [
            'user_id' => $user->id,
        ]);

        return response()->json(['success' => true]);
    }
}
