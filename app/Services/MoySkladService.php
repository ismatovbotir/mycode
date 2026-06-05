<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MoySkladService
{
    private string $apiUrl = 'https://online.moysklad.ru/api/rpc/1.0';

    public function __construct(private string $token) {}

    public function findByPhone(string $phone): ?array
    {
        $payload = [
            'jsonrpc' => '2.0',
            'method' => 'entity.customer.list',
            'params' => [
                'filter' => [
                    ['field' => 'phone', 'value' => $this->normalizePhone($phone)],
                ],
                'limit' => 1,
            ],
            'id' => uniqid(),
        ];

        $response = Http::withToken($this->token)->post($this->apiUrl, $payload);

        if (!$response->ok()) {
            return null;
        }

        $data = $response->json();

        if (isset($data['result']['rows']) && \count($data['result']['rows']) > 0) {
            return $data['result']['rows'][0];
        }

        return null;
    }

    public function getCustomerById(string $customerId): ?array
    {
        $payload = [
            'jsonrpc' => '2.0',
            'method' => 'entity.customer.get',
            'params' => ['id' => $customerId],
            'id' => uniqid(),
        ];

        $response = Http::withToken($this->token)->post($this->apiUrl, $payload);

        if (!$response->ok()) {
            return null;
        }

        $data = $response->json();

        return $data['result'] ?? null;
    }

    public function getAllCounterparties(int $limit = 100): ?array
    {
        $payload = [
            'jsonrpc' => '2.0',
            'method' => 'entity.counterparty.list',
            'params' => ['limit' => $limit],
            'id' => uniqid(),
        ];

        $response = Http::withToken($this->token)->post($this->apiUrl, $payload);

        if (!$response->ok()) {
            return null;
        }

        $data = $response->json();

        if (isset($data['result']['rows'])) {
            return $data['result']['rows'];
        }

        return [];
    }

    public function getCounterpartyById(string $counterpartyId): ?array
    {
        $payload = [
            'jsonrpc' => '2.0',
            'method' => 'entity.counterparty.get',
            'params' => ['id' => $counterpartyId],
            'id' => uniqid(),
        ];

        $response = Http::withToken($this->token)->post($this->apiUrl, $payload);

        if (!$response->ok()) {
            return null;
        }

        $data = $response->json();

        return $data['result'] ?? null;
    }

    public function testConnection(): array
    {
        $testUrl = 'https://api.moysklad.ru/api/remap/1.2/entity/employee';

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->token}",
                'Content-Type' => 'application/json',
                'Accept-Encoding' => 'gzip',

            ])->timeout(20)->get($testUrl);
            //dd($response->body());
            $maskedToken = substr($this->token, 0, 10) . '***' . substr($this->token, -10);

            $body = $response->body();
            if ($response->successful()) {
                try {
                    $body = $response->json();
                } catch (\Exception $e) {
                    // Keep as string if not JSON
                }
            }

            return [
                'method' => 'GET',
                'url' => $testUrl,
                'headers' => [
                    'Authorization' => "Bearer {$maskedToken}",
                    'Accept' => 'application/json',
                    'User-Agent' => 'MyCode/1.0',
                ],
                'status_code' => $response->status(),
                'body' => $body,
                'success' => $response->status() === 200,
            ];
        } catch (\Exception $e) {
            $maskedToken = substr($this->token, 0, 10) . '***' . substr($this->token, -10);

            return [
                'method' => 'GET',
                'url' => $testUrl,
                'headers' => [
                    'Authorization' => "Bearer {$maskedToken}",
                    'Accept' => 'application/json',
                    'User-Agent' => 'MyCode/1.0',
                ],
                'status_code' => 0,
                'body' => 'Connection Error: ' . $e->getMessage(),
                'success' => false,
            ];
        }
    }

    public function createWebhook(string $entityType, string $webhookUrl, string $action = 'CREATE'): ?array
    {
        $url = 'https://api.moysklad.ru/api/remap/1.2/entity/webhook';
        $payload = [
            'url' => $webhookUrl,
            'action' => $action,
            'entityType' => $entityType,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->token}",
                'Content-Type' => 'application/json',
                'Accept-Encoding' => 'gzip',
            ])->timeout(20)->post($url, $payload);

            if (!$response->successful()) {
                Log::error('МойСклад webhook creation failed', [
                    'entity' => $entityType,
                    'action' => $action,
                    'status' => $response->status(),
                    'request_body' => $payload,
                    'response_body' => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();
            return [
                'id' => $data['id'] ?? null,
                'url' => $data['url'] ?? $webhookUrl,
                'action' => $data['action'] ?? $action,
                'entityType' => $data['entityType'] ?? $entityType,
                'request_body' => $payload,
                'response' => $data,
            ];
        } catch (\Exception $e) {
            Log::error('МойСклад webhook creation exception', [
                'entity' => $entityType,
                'action' => $action,
                'request_body' => $payload,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function deleteWebhook(string $webhookId): ?array
    {
        $url = "https://api.moysklad.ru/api/remap/1.2/entity/webhook/{$webhookId}";

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->token}",
                'Content-Type' => 'application/json',
                'Accept-Encoding' => 'gzip',
            ])->timeout(20)->delete($url);

            Log::info('МойСклад webhook deleted', [
                'webhook_id' => $webhookId,
                'status' => $response->status(),
            ]);

            return [
                'success' => $response->status() === 204 || $response->status() === 200,
                'status' => $response->status(),
                'message' => $response->status() === 204 ? 'Webhook deleted successfully' : $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('МойСклад webhook deletion failed', [
                'webhook_id' => $webhookId,
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\D/', '', $phone);
    }
}
