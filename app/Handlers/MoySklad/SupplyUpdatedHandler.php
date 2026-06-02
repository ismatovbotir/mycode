<?php
// app/Handlers/MoySklad/SupplyUpdatedHandler.php

declare(strict_types=1);

namespace App\Handlers\MoySklad;

class SupplyUpdatedHandler
{
    public static function handle(\App\Models\Bot $bot, array $payload): void
    {
        SupplyCreatedHandler::handle($bot, $payload);
    }
}
