<?php
// database/seeders/IntegrationFieldSeeder.php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\IntegrationField;
use Illuminate\Database\Seeder;

class IntegrationFieldSeeder extends Seeder
{
    public function run(): void
    {
        $fields = [
            // МойСклад fields
            [
                'integration_type' => 'moysklad',
                'field_key' => 'api_token',
                'label' => 'API Token',
                'type' => 'password',
                'placeholder' => 'Enter your МойСклад API token',
                'help_text' => 'Get your API token from МойСклад settings: https://moysklad.ru → Settings → API',
                'is_required' => true,
                'sort_order' => 0,
                'is_active' => true,
            ],
            [
                'integration_type' => 'moysklad',
                'field_key' => 'base_url',
                'label' => 'Base URL',
                'type' => 'url',
                'placeholder' => 'https://api.moysklad.ru/api/remap/1.2/',
                'help_text' => 'Default МойСклад API URL. Leave blank to use default.',
                'is_required' => false,
                'sort_order' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($fields as $field) {
            IntegrationField::updateOrCreate(
                [
                    'integration_type' => $field['integration_type'],
                    'field_key' => $field['field_key'],
                ],
                $field
            );
        }
    }
}
