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

    public function handle(
        MoySkladDocumentService $documentService,
        PhoneMatchingService $phoneService,
        TelegramService $telegramService,
        DeveloperNotificationService $notifier
    ): int
    {
        $limit = (int) $this->option('limit');

        $this->info("Processing pending webhooks (max: {$limit})...");

        try {
            $webhooks = MoySkladWebhook::where('status', 'received')
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

                    // Mark as processing
                    $webhook->update(['status' => 'processing']);

                    // Fetch document from MoySkład
                    $document = $documentService->fetchDocument($webhook->document_url);
                    if (!$document) {
                        throw new \Exception('Failed to fetch document from MoySkład');
                    }

                    // Extract counterparty phone
                    $phone = $documentService->extractCounterpartyPhone($document);
                    if (!$phone) {
                        // Keep status as 'processing' and notify developer with document details
                        Log::channel('webhook')->warning('No phone found in document', [
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

                        $notifier->notifyDevelopment(
                            '⚠️ Missing Phone',
                            $message
                        );

                        $this->line("  ⚠ No phone found - keeping as processing");
                        continue;
                    }

                    // Find matching bot client
                    $botClient = $phoneService->findBotClientByPhone($phone, $webhook->bot_id);
                    if (!$botClient) {
                        $webhook->update(['status' => 'processed', 'matched_client_id' => null]);
                        $processed++;
                        $this->line("  ⚠ No matching client");
                        continue;
                    }

                    // Format document for Telegram
                    $message = $documentService->formatDocumentForTelegram($document);
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

                    $notifier->notifyDevelopment(
                        '✅ Webhook Processed',
                        "webhook_id: {$webhook->id}"
                    );

                    $processed++;
                    $this->line("  ✓ Processed successfully");

                } catch (\Exception $e) {
                    Log::channel('webhook')->error('Webhook processing failed', [
                        'webhook_id' => $webhook->id,
                        'error' => $e->getMessage(),
                    ]);

                    $webhook->update([
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);

                    $failed++;
                    $this->line("  ✗ Error: {$e->getMessage()}");
                }
            }

            Log::channel('webhook')->info("Processed webhooks", [
                'total' => $count,
                'processed' => $processed,
                'failed' => $failed,
            ]);

            $this->info("✓ Processed {$processed}, Failed {$failed}");
            return 0;

        } catch (\Exception $e) {
            $this->error("Error processing webhooks: {$e->getMessage()}");
            Log::channel('webhook')->error('ProcessPendingWebhooks command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 1;
        }
    }
}
