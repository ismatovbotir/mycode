<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\MoySkladWebhook;
use App\Services\MoySkladDocumentService;
use App\Services\PhoneMatchingService;
use App\Services\TelegramService;
use App\Services\DeveloperNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessPendingWebhooks extends Command
{
    protected $signature = 'webhooks:process {--limit=50 : Maximum webhooks to process}';
    protected $description = 'Process pending MoySkład webhooks and send Telegram notifications';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');

        $this->info("Processing pending webhooks (max: {$limit})...");

        try {
            $phoneService = new PhoneMatchingService();
            $notifier = new DeveloperNotificationService();
            $telegramService = new TelegramService('');
            $webhooks = MoySkladWebhook::with(['user', 'bot'])
                ->where('status', 'received')
                ->orderBy('created_at', 'asc')
                ->limit($limit)
                ->get();

            if ($webhooks->isEmpty()) {
                $this->info('No pending webhooks to process');
                return 0;
            }

            $count = $webhooks->count();
            $this->info("Found {$count} webhook(s) to process");

            $processed = 0;
            $failed = 0;

            foreach ($webhooks as $webhook) {
                try {
                    $this->line("Processing webhook: {$webhook->id}");

                    // Get user's MoySkład token
                    if (!$webhook->user) {
                        throw new \Exception('Webhook has no associated user');
                    }

                    $token = $webhook->user->moysklad_token;
                    if (!$token) {
                        throw new \Exception('User has no MoySkład token configured');
                    }

                    $documentService = new MoySkladDocumentService($token);

                    // Fetch document from MoySkład
                    $document = $documentService->fetchDocument($webhook->document_url);
                    if (!$document) {
                        throw new \Exception('Failed to fetch document from MoySkład');
                    }

                    // Extract counterparty phone
                    $phone = $documentService->extractCounterpartyPhone($document);
                    if (!$phone) {
                        // Keep status as 'processing' and notify developer with document details
                        Log::warning('No phone found in document', [
                            'webhook_id' => $webhook->id,
                            'entity_type' => $webhook->entity_type,
                            'document_id' => $webhook->document_id,
                            'document_url' => $webhook->document_url,
                        ]);

                        // Format document details for Telegram
                        $agentName = $document['agent']['name'] ?? 'Unknown';
                        $documentName = $document['name'] ?? $webhook->document_id;
                        $sum = $document['sum'] ?? 0;
                        $moment = $document['moment'] ?? 'N/A';

                        $message = "⚠️ Missing Phone - {$webhook->entity_type}\n\n";
                        $message .= "Document: {$documentName}\n";
                        $message .= "Counterparty: {$agentName}\n";
                        $message .= "Amount: {$sum}\n";
                        $message .= "Date: {$moment}\n\n";
                        $message .= "❗ Please add phone number to counterparty in MoySkład\n";
                        $message .= "Webhook ID: {$webhook->id}";

                        // Notify user via their bot if they linked Telegram
                        if ($webhook->bot->user?->tg_chat_id) {
                            $notifier->notifyUserViaBotAsync(
                                $webhook->bot->tg_bot_token,
                                $webhook->bot->user->tg_chat_id,
                                '⚠️ Missing Phone',
                                $message
                            );
                        }

                        $this->line("  ⚠ No phone found - keeping as processing");
                        continue;
                    }

                    // Find matching bot client
                    $botClient = $phoneService->findBotClientByPhone($phone, $webhook->bot_id);
                    if (!$botClient) {
                        // Keep status as 'processing' and notify developer
                        // Log::warning('No matching bot client found', [
                        //     'webhook_id' => $webhook->id,
                        //     'entity_type' => $webhook->entity_type,
                        //     'phone' => $phone,
                        //     'bot_id' => $webhook->bot_id,
                        // ]);

                        // Format message for Telegram
                        $agentName = $document['agent']['name'] ?? 'Unknown';
                        $documentName = $document['name'] ?? $webhook->document_id;

                        $message = "❌ No Matching Client\n\n";
                        $message .= "Document: {$documentName}\n";
                        $message .= "Entity Type: {$webhook->entity_type}\n";
                        $message .= "Counterparty: {$agentName}\n";
                        $message .= "Phone: {$phone}\n\n";
                        $message .= "❗ Client with this phone not found in bot\n";
                        $message .= "Webhook ID: {$webhook->id}";

                        // Notify user via their bot if they linked Telegram
                        if ($webhook->bot->user?->tg_chat_id) {
                            $notifier->notifyUserViaBotAsync(
                                $webhook->bot->tg_bot_token,
                                $webhook->bot->user->tg_chat_id,
                                '❌ No Matching Client',
                                $message
                            );
                        }

                        $this->line("  ⚠ No matching client - keeping as processing");
                        continue;
                    }

                    // Format document for Telegram
                    $message = $this->formatDocumentMessage($document, $webhook->entity_type);
                    if (!$message) {
                        throw new \Exception('Failed to format document for Telegram');
                    }

                    // Send Telegram message
                    $chatId = (int) $botClient->tg_user_id;
                    $bot = $webhook->bot;
                    $token = decrypt($bot->tg_bot_token);

                    $telegramService->sendMessage($token, $chatId, $message);

                    // Update webhook
                    $webhook->update([
                        'status' => 'processed',
                        'matched_client_id' => $botClient->id,
                    ]);

                    // Notify user via their bot if they linked Telegram
                    if ($webhook->bot->user?->tg_chat_id) {
                        $notifier->notifyUserViaBotAsync(
                            $webhook->bot->tg_bot_token,
                            $webhook->bot->user->tg_chat_id,
                            '✅ Webhook Processed',
                            $message
                        );
                    }

                    $processed++;
                    $this->line("  ✓ Processed successfully");
                } catch (\Exception $e) {
                    // Log::error('Webhook processing failed', [
                    //     'webhook_id' => $webhook->id,
                    //     'error' => $e->getMessage(),
                    //     'trace' => $e->getTraceAsString(),
                    // ]);

                    // Notify developer about error
                    $notifier->notifyDevelopment(
                        '❌ Webhook Error',
                        "webhook_id: {$webhook->id}\nerror: {$e->getMessage()}"
                    );

                    // Keep as processing so it can be retried
                    $webhook->update([
                        'status' => 'processing',
                        'error_message' => $e->getMessage(),
                    ]);

                    $failed++;
                    $this->line("  ✗ Error: {$e->getMessage()}");
                }
            }

            // Log::info("Processed webhooks", [
            //     'total' => $count,
            //     'processed' => $processed,
            //     'failed' => $failed,
            // ]);

            $this->info("✓ Processed {$processed}, Failed {$failed}");
            return 0;
        } catch (\Exception $e) {
            $this->error("Error processing webhooks: {$e->getMessage()}");
            Log::error('ProcessPendingWebhooks command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 1;
        }
    }

    private function formatDocumentMessage(array $document, string $entityType): string
    {
        $documentName = $document['name'] ?? 'Unknown';
        $agentName = $document['agent']['name'] ?? 'Unknown';
        $sum = ($document['sum'] ?? 0) / 100; // Convert from coins to currency
        $moment = $document['moment'] ?? 'N/A';

        // Format header based on entity type
        $emoji = match ($entityType) {
            'demand' => '📦',
            'supply' => '📥',
            default => '📄',
        };

        $message = "{$emoji} {$entityType} #{$documentName}\n\n";
        $message .= "👤 {$agentName}\n";
        $message .= "💰 {$sum} UZS\n";
        $message .= "📅 {$moment}\n\n";

        // Add items if available
        if (isset($document['positions']['rows']) && is_array($document['positions']['rows'])) {
            $message .= "📋 Items:\n";
            foreach ($document['positions']['rows'] as $position) {
                $name = $position['assortment']['name'] ?? 'Product';
                $quantity = $position['quantity'] ?? 0;
                $price = ($position['price'] ?? 0) / 100; // Convert from coins

                $message .= "  • {$name}: {$quantity} × {$price} UZS\n";
            }
        }

        return $message;
    }
}
