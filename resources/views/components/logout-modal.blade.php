@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet" />
<style>
.slo-modal-card {
    font-family: 'DM Sans', sans-serif;
}
.slo-title {
    font-family: 'Fraunces', serif;
    font-size: 22px;
    font-weight: 700;
    color: #0F4C5C;
    line-height: 1.3;
}
.slo-text {
    font-size: 13px;
    color: #0F4C5C;
    line-height: 1.65;
}
.slo-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 40px;
    padding: 16px 28px;
    border-top: 1px solid #C8E8E4;
}
.slo-btn {
    padding: 9px 22px;
    border-radius: 8px;
    font-size: 13.5px;
    font-weight: 500;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    border: 1.5px solid transparent;
    transition: background .25s, box-shadow .25s, transform .15s;
}
.slo-btn:active { transform: scale(0.97); }
.slo-btn-ghost {
    background: #fff;
    border-color: #C8E8E4;
    color: #0F4C5C;
}
.slo-btn-ghost:hover { background: #F4F6FA; border-color: #c8d0db; }
.slo-btn-teal {
    background: linear-gradient(135deg, #0F4C5C, #1A6B7A);
    color: #F9D679;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(15,76,92,0.25);
}
.slo-btn-teal:hover {
    background: #1A6B7A;
    box-shadow: 0 8px 20px rgba(15,76,92,0.35);
}

/* Toast */
.slo-toast {
    position: fixed;
    bottom: 28px;
    left: 50%;
    transform: translateX(-50%) translateY(16px);
    background: #0F4C5C;
    color: #fff;
    font-size: 13.5px;
    font-weight: 500;
    padding: 11px 22px;
    border-radius: 40px;
    box-shadow: 0 4px 16px rgba(15,76,92,0.25);
    opacity: 0;
    pointer-events: none;
    transition: opacity .25s, transform .25s;
    white-space: nowrap;
    z-index: 9999;
    font-family: 'DM Sans', sans-serif;
}
.slo-toast.show {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}
</style>
@endpush

{{-- Uses the same <x-modal> component and Alpine event system as the rest of the app --}}
<x-modal name="confirm-logout" maxWidth="sm">
    <div class="slo-modal-card">

        {{-- Body --}}
        <div style="padding: 28px 28px 22px; text-align: center;">
            <p class="slo-title">Log Out</p>
            <p class="slo-text" style="margin-top: 10px;">
                Are you sure you want to log out?<br>
                You'll need to sign in again to continue.
            </p>
        </div>

        {{-- Footer --}}
        <div class="slo-footer">
            <button
                class="slo-btn slo-btn-ghost"
                x-on:click="
                    $dispatch('close-modal', 'confirm-logout');
                    $nextTick(() => window.sloShowToast('Logout cancelled'))
                "
            >
                Cancel
            </button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="slo-btn slo-btn-teal">
                    Log Out
                </button>
            </form>
        </div>

    </div>
</x-modal>

{{-- Toast (shared, lives here since this component is included once in the layout) --}}
<div class="slo-toast" id="sloToast"></div>

@push('scripts')
<script>
window.sloShowToast = function (msg) {
    const t = document.getElementById('sloToast');
    if (!t) return;
    t.textContent = msg;
    t.classList.add('show');
    clearTimeout(window._sloToastTimer);
    window._sloToastTimer = setTimeout(() => t.classList.remove('show'), 3000);
};
</script>
@endpush
