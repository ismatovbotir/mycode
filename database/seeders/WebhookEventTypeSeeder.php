<?php
// database/seeders/WebhookEventTypeSeeder.php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\WebhookEventType;
use Illuminate\Database\Seeder;

class WebhookEventTypeSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'event_type' => 'entity.counterparty.create',
                'name' => 'Counterparty Created',
                'description' => 'Triggered when a new counterparty (customer) is created in МойСклад',
                'icon' => '👤',
                'is_active' => true,
            ],
            [
                'event_type' => 'entity.counterparty.update',
                'name' => 'Counterparty Updated',
                'description' => 'Triggered when a counterparty is updated in МойСклад',
                'icon' => '✏️',
                'is_active' => true,
            ],
            [
                'event_type' => 'entity.product.create',
                'name' => 'Product Created',
                'description' => 'Triggered when a new product is created in МойСклад',
                'icon' => '📦',
                'is_active' => true,
            ],
            [
                'event_type' => 'entity.product.update',
                'name' => 'Product Updated',
                'description' => 'Triggered when a product is updated in МойСклад',
                'icon' => '🔄',
                'is_active' => true,
            ],
            [
                'event_type' => 'entity.demand.create',
                'name' => 'Order Created',
                'description' => 'Triggered when a new order (demand) is created in МойСклад',
                'icon' => '📋',
                'is_active' => true,
            ],
            [
                'event_type' => 'entity.demand.update',
                'name' => 'Order Updated',
                'description' => 'Triggered when an order is updated in МойСклад',
                'icon' => '📝',
                'is_active' => true,
            ],
            [
                'event_type' => 'entity.supply.create',
                'name' => 'Supply Created',
                'description' => 'Triggered when a new supply (shipment) is created in МойСклад',
                'icon' => '📥',
                'is_active' => true,
            ],
            [
                'event_type' => 'entity.supply.update',
                'name' => 'Supply Updated',
                'description' => 'Triggered when a supply is updated in МойСклад',
                'icon' => '📤',
                'is_active' => true,
            ],
            [
                'event_type' => 'entity.paymentin.create',
                'name' => 'Payment Received',
                'description' => 'Triggered when a payment is received in МойСклад',
                'icon' => '💰',
                'is_active' => true,
            ],
            [
                'event_type' => 'entity.paymentout.create',
                'name' => 'Payment Sent',
                'description' => 'Triggered when a payment is sent in МойСклад',
                'icon' => '💸',
                'is_active' => true,
            ],
            [
                'event_type' => 'entity.salesreturn.create',
                'name' => 'Sales Return',
                'description' => 'Triggered when a sales return is created in МойСклад',
                'icon' => '↩️',
                'is_active' => true,
            ],
            [
                'event_type' => 'entity.purchasereturn.create',
                'name' => 'Purchase Return',
                'description' => 'Triggered when a purchase return is created in МойСклад',
                'icon' => '↪️',
                'is_active' => true,
            ],
        ];

        foreach ($events as $event) {
            WebhookEventType::updateOrCreate(
                ['event_type' => $event['event_type']],
                $event
            );
        }
    }
}
