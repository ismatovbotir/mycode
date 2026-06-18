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
            // Add expand parameters to fetch agent and positions details
            $separator = str_contains($documentUrl, '?') ? '&' : '?';
            $urlWithExpand = $documentUrl . $separator . 'expand=agent,positions.assortment';

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->token}",
                'Content-Type' => 'application/json',
                'Accept-Encoding' => 'gzip',
            ])->timeout(20)->get($urlWithExpand);

            if (!$response->successful()) {
                Log::warning('Failed to fetch МойСклад document', [
                    'url' => $urlWithExpand,
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

        if (isset($document['agent']['phone'])) {
            return (string) $document['agent']['phone'];
        }

        return null;
    }

    public function formatDocumentForTelegram(array $document, ?string $entityType = null, ?string $lang = 'uz'): ?string
    {
        $documentNumber = $document['name'] ?? 'N/A';
        $documentDate = $document['moment'] ?? 'N/A';
        $counterpartyName = $document['agent']['name'] ?? 'Unknown';
        $total = $document['sum'] ?? 0;

        $totalFormatted = number_format($total / 100, 2, '.', ' ');

        // Translate document type
        $docTypeLabels = [
            'uz' => ['demand' => 'Sotib olish', 'supply' => 'Sotish'],
            'en' => ['demand' => 'Demand', 'supply' => 'Supply'],
            'ru' => ['demand' => 'Спрос', 'supply' => 'Приход'],
        ];

        $docType = $docTypeLabels[$lang][$entityType] ?? $entityType ?? 'Document';

        // Translate labels
        $labels = [
            'uz' => ['document' => 'Hujjat', 'date' => 'Sana', 'customer' => 'Xaridor', 'total' => 'Jami', 'items' => 'Maxsulotlar'],
            'en' => ['document' => 'Document', 'date' => 'Date', 'customer' => 'Customer', 'total' => 'Total', 'items' => 'Items'],
            'ru' => ['document' => 'Документ', 'date' => 'Дата', 'customer' => 'Клиент', 'total' => 'Итого', 'items' => 'Товары'],
        ];

        $l = $labels[$lang] ?? $labels['uz'];

        $message = "📋 <b>{$l['document']}: {$documentNumber}</b>\n";
        $message .= "📅 {$l['date']}: {$documentDate}\n";
        $message .= "👤 {$l['customer']}: {$counterpartyName}\n";
        $message .= "💰 {$l['total']}: {$totalFormatted}\n\n";

        if (isset($document['lines']) && is_array($document['lines'])) {
            $message .= "<b>{$l['items']}:</b>\n";
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
