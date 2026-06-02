<?php
// app/Handlers/MoySklad/DemandUpdatedHandler.php

declare(strict_types=1);

namespace App\Handlers\MoySklad;

class DemandUpdatedHandler
{
    public static function handle(\App\Models\Bot $bot, array $payload): void
    {
        DemandCreatedHandler::handle($bot, $payload);
    }
}
