<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\MoySkladWebhook;
use App\Models\BotClient;
use App\Services\MoySkladDocumentService;
use App\Services\PhoneMatchingService;
use App\Services\TelegramService;
use App\Services\DeveloperNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessMoySkladWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function backoff(): array
    {
        return [10, 60, 300];
    }

    public function __construct(
        private MoySkladWebhook $webhook
    ) {}

    public function handle(
        MoySkladDocumentService $documentService,
        PhoneMatchingService $phoneService,
        TelegramService $telegramService,
        DeveloperNotificationService $notifier
    ): void
    {
        Log::channel('webhook')->info('Processing MoySklad webhook', [
            'webhook_id' => $this->webhook->id,
            'entity_type' => $this->webhook->entity_type,
            'status' => $this->webhook->status,
        ]);

        try {
            // Fetch document from MoySkład
            $document = $documentService->fetchDocument($this->webhook->document_url);

            if (!$document) {
                throw new \Exception('Failed to fetch document from MoySkład');
            }

            Log::channel('webhook')->info('Document fetched', [
                'webhook_id' => $this->webhook->id,
                'document_id' => $this->webhook->document_id,
            ]);

            // Extract counterparty phone
            $phone = $documentService->extractCounterpartyPhone($document);

            if (!$phone) {
                Log::channel('webhook')->warning('No phone found in document', [
                    'webhook_id' => $this->webhook->id,
                ]);
                $this->webhook->update(['status' => 'failed', 'error_message' => 'No phone in document']);
                return;
            }

            Log::channel('webhook')->info('Phone extracted', [
                'webhook_id' => $this->webhook->id,
                'phone' => $phone,
            ]);

            // Find matching bot client by phone
            $botClient = $phoneService->findBotClientByPhone($phone, $this->webhook->bot_id);

            if (!$botClient) {
                Log::channel('webhook')->warning('No matching bot client', [
                    'webhook_id' => $this->webhook->id,
                    'phone' => $phone,
                    'bot_id' => $this->webhook->bot_id,
                ]);
                $this->webhook->update(['status' => 'processed', 'matched_client_id' => null]);
                return;
            }

            Log::channel('webhook')->info('Bot client matched', [
                'webhook_id' => $this->webhook->id,
                'bot_client_id' => $botClient->id,
            ]);

            // Format document for Telegram message
            $message = $documentService->formatDocumentForTelegram($document);

            if (!$message) {
                throw new \Exception('Failed to format document for Telegram');
            }

            Log::channel('webhook')->info('Document formatted', [
                'webhook_id' => $this->webhook->id,
                'message_length' => strlen($message),
            ]);

            // Update webhook with matched client
            $this->webhook->update([
                'status' => 'processed',
                'matched_client_id' => $botClient->id,
            ]);

            // Dispatch Telegram notification job
            SendDocumentNotification::dispatch($this->webhook->bot, $botClient, $message)
                ->onQueue('telegram');

            Log::channel('webhook')->info('✓ Webhook processed successfully', [
                'webhook_id' => $this->webhook->id,
                'bot_client_id' => $botClient->id,
            ]);

            $notifier->notifyDevelopment(
                '✅ Webhook Processed',
                "webhook_id: {$this->webhook->id}"
            );

        } catch (Throwable $e) {
            Log::channel('webhook')->error('Webhook processing failed', [
                'webhook_id' => $this->webhook->id,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts(),
            ]);

            $this->webhook->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function failed(Throwable $exception): void
    {
        Log::channel('webhook')->error('❌ Webhook job failed after retries', [
            'webhook_id' => $this->webhook->id,
            'error' => $exception->getMessage(),
        ]);

        $this->webhook->update([
            'status' => 'failed',
            'error_message' => 'Job failed after retries: ' . $exception->getMessage(),
        ]);
    }
}
