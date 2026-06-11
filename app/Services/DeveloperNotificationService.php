<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Log;

class DeveloperNotificationService
{
    private string $botToken;
    private string $developerId;
    private string $apiUrl = 'https://api.telegram.org/bot';

    public function __construct()
    {
        $this->botToken = config('app.bot_token') ?? env('bot_token', '');
        $this->developerId = config('app.bot_chat_id') ?? env('bot_chat_id', '');
    }

    public function notifyWebhookReceived(string $botName, string $eventType, ?array $payload = null): void
    {
        $message = sprintf(
            "📡 *Webhook Event*\n\n" .
            "🤖 Bot: `%s`\n" .
            "📌 Event: `%s`\n" .
            "⏰ Time: `%s`\n" .
            "📦 Payload: ```json\n%s\n```",
            $botName,
            $eventType,
            now()->format('Y-m-d H:i:s'),
            json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        $this->send($message);
    }

    public function notifyUserRegistered(string $botName, string $firstName, string $lastName, ?string $phone = null, string $lang = 'uz'): void
    {
        $message = sprintf(
            "👤 *New User Registration*\n\n" .
            "🤖 Bot: `%s`\n" .
            "👤 Name: `%s %s`\n" .
            "📱 Phone: `%s`\n" .
            "🗣️ Language: `%s`\n" .
            "⏰ Time: `%s`",
            $botName,
            $firstName,
            $lastName,
            $phone ?? 'not provided',
            $lang,
            now()->format('Y-m-d H:i:s')
        );

        $this->send($message);
    }

    public function notifyUserMatched(string $botName, string $firstName, string $lastName, string $mySkladId, ?string $phone = null): void
    {
        $message = sprintf(
            "✅ *User Matched in MoySkład*\n\n" .
            "🤖 Bot: `%s`\n" .
            "👤 Name: `%s %s`\n" .
            "📱 Phone: `%s`\n" .
            "🔗 MoySkład ID: `%s`\n" .
            "⏰ Time: `%s`",
            $botName,
            $firstName,
            $lastName,
            $phone ?? 'unknown',
            $mySkladId,
            now()->format('Y-m-d H:i:s')
        );

        $this->send($message);
    }

    public function notifyMessageSent(string $botName, string $userName, string $message, bool $success = true): void
    {
        $status = $success ? '✅ Sent' : '❌ Failed';
        $notification = sprintf(
            "%s *Telegram Message*\n\n" .
            "🤖 Bot: `%s`\n" .
            "👤 User: `%s`\n" .
            "📝 Message: ```\n%s\n```\n" .
            "⏰ Time: `%s`",
            $success ? '✅' : '❌',
            $botName,
            $userName,
            substr($message, 0, 200) . (strlen($message) > 200 ? '...' : ''),
            now()->format('Y-m-d H:i:s')
        );

        $this->send($notification);
    }

    public function notifyWebhookError(string $botName, string $errorMessage, ?array $context = null): void
    {
        $message = sprintf(
            "🚨 *Webhook Error*\n\n" .
            "🤖 Bot: `%s`\n" .
            "❌ Error: ```\n%s\n```\n" .
            "📋 Context: ```json\n%s\n```\n" .
            "⏰ Time: `%s`",
            $botName,
            substr($errorMessage, 0, 300),
            json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            now()->format('Y-m-d H:i:s')
        );

        $this->send($message);
    }

    public function notifyMoySkladSync(string $status, string $entityType, int $count, ?string $error = null): void
    {
        if ($error) {
            $message = sprintf(
                "🔄 *MoySkład Sync Failed*\n\n" .
                "📌 Entity: `%s`\n" .
                "❌ Error: ```\n%s\n```\n" .
                "⏰ Time: `%s`",
                $entityType,
                substr($error, 0, 200),
                now()->format('Y-m-d H:i:s')
            );
        } else {
            $message = sprintf(
                "✅ *MoySkład Sync Complete*\n\n" .
                "📌 Entity: `%s`\n" .
                "📊 Count: `%d`\n" .
                "⏰ Time: `%s`",
                $entityType,
                $count,
                now()->format('Y-m-d H:i:s')
            );
        }

        $this->send($message);
    }

    public function notifyJobQueued(string $jobName, string $botName, ?string $details = null): void
    {
        $message = sprintf(
            "⏳ *Job Queued*\n\n" .
            "🤖 Bot: `%s`\n" .
            "📌 Job: `%s`\n" .
            "%s⏰ Time: `%s`",
            $botName,
            $jobName,
            $details ? "📝 Details: `$details`\n" : '',
            now()->format('Y-m-d H:i:s')
        );

        $this->send($message);
    }

    public function notifyJobFailed(string $jobName, string $botName, string $errorMessage): void
    {
        $message = sprintf(
            "❌ *Job Failed*\n\n" .
            "🤖 Bot: `%s`\n" .
            "📌 Job: `%s`\n" .
            "🚨 Error: ```\n%s\n```\n" .
            "⏰ Time: `%s`",
            $botName,
            $jobName,
            substr($errorMessage, 0, 300),
            now()->format('Y-m-d H:i:s')
        );

        $this->send($message);
    }

    public function notifyDevelopment(string $stage, string $description, ?array $data = null): void
    {
        $message = sprintf(
            "🛠️ *Development Stage*\n\n" .
            "📌 Stage: `%s`\n" .
            "📝 Description: `%s`\n" .
            "%s⏰ Time: `%s`",
            $stage,
            $description,
            $data ? "📊 Data: ```json\n" . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n```\n" : '',
            now()->format('Y-m-d H:i:s')
        );

        $this->send($message);
    }

    private function send(string $message): void
    {
        if (!$this->botToken || !$this->developerId) {
            Log::channel('telegram')->warning('Developer notification skipped - credentials not configured');
            return;
        }

        try {
            $url = $this->apiUrl . $this->botToken . '/sendMessage';
            $payload = [
                'chat_id' => $this->developerId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                Log::channel('telegram')->warning('Developer notification failed', [
                    'http_code' => $httpCode,
                    'response' => $response,
                    'message' => substr($message, 0, 100),
                ]);
            }
        } catch (\Exception $e) {
            Log::channel('telegram')->error('Error sending developer notification', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
