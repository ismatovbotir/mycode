<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MoySkladDocumentService
{
    public function __construct(private string $token) {}

    public function fetchDocument(string $documentUrl): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->token}",
                'Content-Type' => 'application/json',
                'Accept-Encoding' => 'gzip',
            ])->timeout(20)->get($documentUrl);

            if (!$response->successful()) {
                Log::warning('Failed to fetch МойСклад document', [
                    'url' => $documentUrl,
                    'status' => $response->status(),
                ]);
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Exception fetching МойСклад document', [
                'url' => $documentUrl,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function extractCounterpartyPhone(?array $document): ?string
    {
        if (!$document) {
            return null;
        }

        if (isset($document['counterparty']['phone'])) {
            return (string) $document['counterparty']['phone'];
        }

        return null;
    }

    public function formatDocumentForTelegram(array $document, ?array $messageTemplate = null): ?string
    {
        $documentNumber = $document['name'] ?? 'N/A';
        $documentDate = $document['moment'] ?? 'N/A';
        $counterpartyName = $document['counterparty']['name'] ?? 'Unknown';
        $total = $document['sum'] ?? 0;

        $totalFormatted = number_format($total / 100, 2, '.', ' ');

        $message = "📋 <b>Document: {$documentNumber}</b>\n";
        $message .= "📅 Date: {$documentDate}\n";
        $message .= "👤 Customer: {$counterpartyName}\n";
        $message .= "💰 Total: {$totalFormatted}\n\n";

        if (isset($document['lines']) && is_array($document['lines'])) {
            $message .= "<b>Items:</b>\n";
            foreach ($document['lines'] as $line) {
                $itemName = $line['name'] ?? 'Item';
                $quantity = $line['quantity'] ?? 0;
                $price = $line['price'] ?? 0;
                $amount = $line['sum'] ?? 0;

                $priceFormatted = number_format($price / 100, 2, '.', ' ');
                $amountFormatted = number_format($amount / 100, 2, '.', ' ');

                $message .= "• {$itemName}\n";
                $message .= "  Qty: {$quantity} × {$priceFormatted} = {$amountFormatted}\n";
            }
        }

        return $message;
    }
}
