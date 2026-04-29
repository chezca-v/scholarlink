
@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet" />

<style>
/* ── ScholarLink Log-Out Modal ─────────────────────────────── */
:root {
  --slo-teal:      #0F4C5C;
  --slo-teal-dark: #0a3545;
  --slo-white:     #FFFFFF;
  --slo-bg:        #F4F6FA;
  --slo-border:    #E2E8F0;
  --slo-text:      #1C1C2E;
  --slo-muted:     #8A95A3;
  --slo-radius-sm: 8px;
  --slo-radius-md: 12px;
  --slo-radius-lg: 16px;
}

/* OVERLAY */
.slo-overlay {
  position: fixed;
  inset: 0;
  background: rgba(15, 40, 52, 0.38);
  backdrop-filter: blur(3px);
  -webkit-backdrop-filter: blur(3px);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 24px;
  opacity: 0;
  pointer-events: none;
  transition: opacity .25s ease;
}
.slo-overlay.is-open {
  opacity: 1;
  pointer-events: all;
}

/* MODAL CARD */
.slo-modal {
  background: var(--slo-white);
  border-radius: var(--slo-radius-lg);
  border: 1px solid var(--slo-border);
  width: 100%;
  max-width: 300px;
  overflow: hidden;
  box-shadow:
    0 4px 24px rgba(15,76,92,0.08),
    0 1px 4px  rgba(15,76,92,0.04);
  transform: scale(0.88);
  opacity: 0;
  transition: transform .35s cubic-bezier(0.22, 1, 0.36, 1),
              opacity   .28s ease;
}
.slo-overlay.is-open .slo-modal {
  transform: scale(1);
  opacity: 1;
}

/* BODY */
.slo-body {
  padding: 28px 28px 22px;
  text-align: center;
}
.slo-title {
  font-family: 'Fraunces', serif;
  font-size: 25px;
  font-weight: 700;
  color: var(--slo-teal);
  margin-bottom: 10px;
  line-height: 1.3;
}
.slo-text {
  font-family: 'DM Sans', sans-serif;
  font-size: 13px;
  color: var(--slo-teal);
  line-height: 1.65;
}

/* FOOTER */
.slo-footer {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 40px;
  padding: 16px 28px;
  border-top: 1px solid #C8E8E4;
  background: var(--slo-white);
}

/* BUTTONS */
.slo-btn {
  padding: 9px 22px;
  border-radius: var(--slo-radius-sm);
  font-size: 13.5px;
  font-weight: 500;
  font-family: 'DM Sans', sans-serif;
  cursor: pointer;
  border: 1.5px solid transparent;
  transition: background .25s ease, box-shadow .25s ease, transform .15s ease;
}
.slo-btn:active { transform: scale(0.97); }

.slo-btn-ghost {
  background: var(--slo-white);
  border-color: #C8E8E4;
  color: var(--slo-teal);
}
.slo-btn-ghost:hover { background: var(--slo-bg); border-color: #c8d0db; }

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

/* TOAST */
.slo-toast {
  position: fixed;
  bottom: 28px;
  left: 50%;
  transform: translateX(-50%) translateY(16px);
  background: var(--slo-teal);
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
  z-index: 1100;
  font-family: 'DM Sans', sans-serif;
}
.slo-toast.show {
  opacity: 1;
  transform: translateX(-50%) translateY(0);
}
</style>
@endpush

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>

<!-- OVERLAY + MODAL -->
<div class="slo-overlay" id="sloOverlay" role="alertdialog" aria-modal="true" aria-labelledby="sloTitle">
    <div class="slo-modal" id="sloModal">

        <!-- BODY -->
        <div class="slo-body">
            <p class="slo-title" id="sloTitle">Log Out</p>
            <p class="slo-text">
                Are you sure you want to log out?<br>
                You'll need to sign in again to continue.
            </p>
        </div>

        <!-- FOOTER -->
        <div class="slo-footer">
            <button class="slo-btn slo-btn-ghost" id="sloCancelBtn">Cancel</button>
            <button class="slo-btn slo-btn-teal"  id="sloLogoutBtn">Log Out</button>
        </div>

    </div>
</div>

<!-- TOAST -->
<div class="slo-toast" id="sloToast"></div>


@push('scripts')
<script>
(function () {

    /* ── Internal state ── */
    let _session   = null;
    let _onConfirm = null;
    let _toastTimer;

    /* ── Toast ── */
    function showToast(msg) {
        const t = document.getElementById('sloToast');
        t.textContent = msg;
        t.classList.add('show');
        clearTimeout(_toastTimer);
        _toastTimer = setTimeout(() => t.classList.remove('show'), 3000);
    }

    window.openLogoutModal = function (session, onConfirm) {
        _session   = session   || null;
        _onConfirm = onConfirm || null;
        document.getElementById('sloOverlay').classList.add('is-open');
        document.body.style.overflow = 'hidden';
    };

    /* ── closeLogoutModal ── */
    window.closeLogoutModal = function () {
        document.getElementById('sloOverlay').classList.remove('is-open');
        document.body.style.overflow = '';
    };

    /* ── Confirm logout ── */
    document.getElementById('sloLogoutBtn').addEventListener('click', function () {
        if (typeof _onConfirm === 'function') {
            _onConfirm(_session);
        } else {
            /
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("logout") }}';

            const csrf = document.createElement('input');
            csrf.type  = 'hidden';
            csrf.name  = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            document.body.appendChild(form);
            form.submit();
        }
        closeLogoutModal();
    });

    /* ── Cancel ── */
    document.getElementById('sloCancelBtn').addEventListener('click', function () {
        showToast('Logout cancelled');
        closeLogoutModal();
    });

    /* ── Close on overlay click ── */
    document.getElementById('sloOverlay').addEventListener('click', function (e) {
        if (e.target === this) closeLogoutModal();
    });

    /* ── Close on Escape ── */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeLogoutModal();
    });

})();
</script>
@endpush
