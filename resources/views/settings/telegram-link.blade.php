@php
    $user = auth()->user();
    $isLinked = (bool) $user->tg_chat_id;
@endphp

<div class="card">
    <div class="card-header">
        <h3 class="mb-0">🔗 Link Telegram Account</h3>
        <p class="text-sm text-gray-500 mt-1">Receive webhook notifications directly in your Telegram bot</p>
    </div>

    <div class="card-body">
        @if ($isLinked)
            <!-- Already Linked -->
            <div class="alert alert-success">
                <div class="flex items-center justify-between">
                    <div>
                        ✅ <strong>Account Linked</strong>
                        <p class="text-sm mt-1">Chat ID: <code>{{ $user->tg_chat_id }}</code></p>
                        <p class="text-sm text-gray-600">Linked at: {{ $user->tg_linked_at?->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <button id="relink-btn" class="btn btn-primary" type="button">
                🔄 Re-link Account
            </button>
            <button id="unlink-btn" class="btn btn-outline-danger" type="button">
                ❌ Unlink
            </button>
        @else
            <!-- Not Linked - Show Generate Button -->
            <p class="text-gray-600 mb-4">
                Scan the QR code with your phone to link your account. You'll receive webhook notifications directly in your bot.
            </p>

            <button id="generate-qr-btn" class="btn btn-primary" type="button">
                📱 Generate QR Code
            </button>
        @endif

        <!-- QR Code Container (hidden by default) -->
        <div id="qr-container" class="mt-6 text-center" style="display: none;">
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <div id="qr-code" class="flex justify-center mb-4">
                    <!-- QR code SVG will be injected here -->
                </div>

                <div class="text-sm text-gray-600 space-y-2">
                    <p>⏱️ QR code expires in <span id="timer">10:00</span></p>
                    <p>
                        💡 <a id="direct-link" href="#" target="_blank" class="text-blue-600 hover:underline">
                            Click here
                        </a> to open in Telegram
                    </p>
                </div>
            </div>

            <div id="status" class="mt-4 text-center text-gray-500">
                ⏳ Waiting for Telegram link...
            </div>

            <button id="cancel-qr-btn" class="btn btn-outline-secondary mt-4" type="button">
                Cancel
            </button>
        </div>
    </div>
</div>

<style>
    #qr-code svg {
        max-width: 300px;
        height: auto;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const generateBtn = document.getElementById('generate-qr-btn');
    const relinkBtn = document.getElementById('relink-btn');
    const unlinkBtn = document.getElementById('unlink-btn');
    const cancelQrBtn = document.getElementById('cancel-qr-btn');
    const qrContainer = document.getElementById('qr-container');

    generateBtn?.addEventListener('click', generateQrCode);
    relinkBtn?.addEventListener('click', relinkAccount);
    cancelQrBtn?.addEventListener('click', cancelQrCode);
    unlinkBtn?.addEventListener('click', unlinkAccount);
});

function generateQrCode() {
    const container = document.getElementById('qr-container');
    container.style.display = 'block';

    // Disable buttons
    document.getElementById('generate-qr-btn')?.setAttribute('disabled', 'disabled');
    document.getElementById('relink-btn')?.setAttribute('disabled', 'disabled');

    fetch('/settings/telegram/generate-qr')
        .then(r => r.json())
        .then(data => {
            if (data.already_linked) {
                alert('Account already linked');
                container.style.display = 'none';
                document.getElementById('generate-qr-btn')?.removeAttribute('disabled');
                return;
            }

            document.getElementById('qr-code').innerHTML = data.qr_code;
            document.getElementById('direct-link').href = data.deep_link;

            // Start timer
            startTimer(data.expires_in, data.link_token);

            // Poll for linking
            pollForLinkStatus(data.link_token);
        })
        .catch(err => {
            alert('Failed to generate QR code');
            console.error(err);
            container.style.display = 'none';
            document.getElementById('generate-qr-btn')?.removeAttribute('disabled');
        });
}

function relinkAccount() {
    if (confirm('Clear your Telegram link and generate a new QR code?')) {
        // Clear tg_chat_id via AJAX
        fetch('/settings/telegram/unlink', { method: 'POST' })
            .then(() => {
                generateQrCode();
            })
            .catch(err => console.error(err));
    }
}

function unlinkAccount() {
    if (confirm('Are you sure? You won\'t receive Telegram notifications.')) {
        fetch('/settings/telegram/unlink', { method: 'POST' })
            .then(() => {
                location.reload();
            })
            .catch(err => console.error(err));
    }
}

function cancelQrCode() {
    document.getElementById('qr-container').style.display = 'none';
    document.getElementById('generate-qr-btn')?.removeAttribute('disabled');
    document.getElementById('relink-btn')?.removeAttribute('disabled');
}

function startTimer(seconds, linkToken) {
    let remaining = seconds;
    const timerEl = document.getElementById('timer');

    const interval = setInterval(() => {
        remaining--;
        const mins = Math.floor(remaining / 60);
        const secs = remaining % 60;
        timerEl.textContent = `${mins}:${secs.toString().padStart(2, '0')}`;

        if (remaining <= 0) {
            clearInterval(interval);
            document.getElementById('qr-container').style.display = 'none';
            alert('QR code expired. Please generate a new one.');
        }
    }, 1000);
}

function pollForLinkStatus(linkToken) {
    const pollInterval = setInterval(() => {
        fetch(`/settings/telegram/check-link/${linkToken}`)
            .then(r => r.json())
            .then(data => {
                if (data.linked) {
                    clearInterval(pollInterval);
                    document.getElementById('status').innerHTML =
                        '✅ <span class="text-green-600">Account linked successfully!</span>';
                    setTimeout(() => location.reload(), 2000);
                }
            })
            .catch(err => console.error(err));
    }, 2000);

    // Stop polling after 10 minutes
    setTimeout(() => clearInterval(pollInterval), 600000);
}
</script>
