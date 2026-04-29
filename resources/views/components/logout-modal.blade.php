@push('styles')
<style>
[x-cloak] { display: none !important; }
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
    opacity: 0;
    pointer-events: none;
    transition: opacity .25s, transform .25s;
    z-index: 9999;
    font-family: 'DM Sans', sans-serif;
}
.slo-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
</style>
@endpush

<x-modal name="confirm-logout" maxWidth="sm">
    <div style="font-family:'DM Sans',sans-serif; padding:32px 28px 24px; text-align:center;">
        <div style="font-size:42px; margin-bottom:14px;">👋</div>
        <h2 style="font-family:'Fraunces',serif; font-size:22px; font-weight:700; color:#0F4C5C; margin-bottom:8px;">
            Log out of ScholarLink?
        </h2>
        <p style="font-size:13px; color:#8A95A3; line-height:1.65; margin-bottom:24px;">
            You'll need to sign in again to access your dashboard and applications.
        </p>

        <div style="display:flex; gap:10px;">
            <button
                x-on:click="$dispatch('close')"
                style="flex:1; padding:11px; background:#F4F6FA; border:1.5px solid #E2E8F0; border-radius:10px; font-family:'DM Sans',sans-serif; font-size:13px; font-weight:600; color:#1C1C2E; cursor:pointer;"
            >
                Cancel
            </button>

            <form method="POST" action="{{ route('logout') }}" style="flex:1;">
                @csrf
                <button
                    type="submit"
                    style="width:100%; padding:11px; background:linear-gradient(135deg,#e53e3e,#c53030); border:none; border-radius:10px; font-family:'DM Sans',sans-serif; font-size:13px; font-weight:700; color:#fff; cursor:pointer;"
                >
                    Yes, Log Out
                </button>
            </form>
        </div>
    </div>
</x-modal>

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
