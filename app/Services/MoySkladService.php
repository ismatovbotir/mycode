<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

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

        if (isset($data['result']['rows']) && count($data['result']['rows']) > 0) {
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

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\D/', '', $phone);
    }
}
