<button
    wire:click="toggle"
    class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium transition-colors {{ $isOwner ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
    title="{{ $isOwner ? 'Click to remove owner status' : 'Click to make report owner' }}"
>
    @if($isOwner)
        <span>👑</span>
        <span>Owner</span>
    @else
        <span>👤</span>
        <span>Make Owner</span>
    @endif
</button>
