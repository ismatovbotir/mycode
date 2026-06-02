<?php
// app/Handlers/MoySklad/CounterpartyUpdatedHandler.php

declare(strict_types=1);

namespace App\Handlers\MoySklad;

use App\Models\Bot;

class CounterpartyUpdatedHandler
{
    public static function handle(Bot $bot, array $payload): void
    {
        // Reuse the same logic as create
        CounterpartyCreatedHandler::handle($bot, $payload);
    }
}
