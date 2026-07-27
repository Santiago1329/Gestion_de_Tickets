<div class="modal fade" id="modalChat" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom border-2" style="border-color: var(--color-primary) !important;">
                <h5 class="modal-title fw-bold mb-0">
                    Chat - TIC-{{ str_pad($ticketChat->id ?? 0, 4, '0', STR_PAD_LEFT) }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($ticketChat)
                    @livewire('ticket-chat', ['ticket' => $ticketChat], key('chat-'.$ticketChat->id))
                @endif
            </div>
        </div>
    </div>
</div>