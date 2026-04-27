<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>
    <!DOCTYPE html>
    
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ScholarLink — Log Out</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet" />
   <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --teal:        #0F4C5C;
      --teal-dark:   #0a3545;
      --white:       #FFFFFF;
      --bg:          #F4F6FA;
      --border:      #E2E8F0;
      --text:        #1C1C2E;
      --muted:       #8A95A3;
      --radius-sm:   8px;
      --radius-md:   12px;
      --radius-lg:   16px;
    }

    body {
      font-family: 'Fraunces', sans-serif;
      background: var(--bg);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 16px;
    }

    .modal-card {
      background: var(--white);
      border-radius: var(--radius-lg);
      border: 1px solid var(--border);
      width: 100%;
      max-width: 300px;
      overflow: hidden;
      box-shadow: 0 4px 24px rgba(15,76,92,0.08), 0 1px 4px rgba(15,76,92,0.04);
      animation: popIn 0.35s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    @keyframes popIn {
      from { opacity: 0; transform: scale(0.8); }
      to   { opacity: 1; transform: scale(1); }
    }

    .modal-body { padding: 28px 28px 22px; }
    .modal-footer {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 40px;
      padding: 16px 28px;
      border-top: 1px solid #C8E8E4;
      background: #ffff;
    }

    .db-badge {
      display: none;
    }

    .modal-title-teal {
      font-size: 25px;
      font-weight: 700;
      color: var(--teal);
      text-align: center;
      margin-bottom: 10px;
      line-height: 1.3;
    }

    .logout-body {
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      color: var(--teal);
      text-align: center;
      line-height: 1.65;
    }
    .session-ref {
      display: inline-block;
      font-family: 'Courier New', monospace;
      font-size: 11px;
      background: #e8f4f8;
      color: #0a3c4f;
      padding: 1px 6px;
      border-radius: 4px;
    }

    .btn {
      padding: 9px 22px;
      border-radius: var(--radius-sm);
      font-size: 13.5px;
      font-weight: 500;
      font-family: 'DM Sans', sans-serif;
      cursor: pointer;
      border: 1.5px solid transparent;
      transition: background 0.25s ease, box-shadow 0.25s ease, transform 0.15s ease, opacity 0.2s ease;
    }
    .btn:active { transform: scale(0.97); box-shadow: 0 4px 10px rgba(15, 76, 92, 0.2);}

    .btn-ghost {
      background: var(--white);
      border-color: #C8E8E4;
      color: var(--teal);
    }
    .btn-ghost:hover { background: var(--bg); border-color: #c8d0db; }

    .btn-teal {
      background: linear-gradient(135deg, #0F4C5C, #1A6B7A);
      color: #F9D679;
      border-color: none;
      box-shadow: 0 4px 12px rgba(15, 76, 92, 0.25);
      transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
    }
    .btn-teal:hover { background: #1A6B7A; box-shadow: 0 8px 20px rgba(15, 76, 92, 0.35);}

    .toast {
      position: fixed;
      bottom: 28px;
      left: 50%;
      transform: translateX(-50%) translateY(16px);
      background: var(--teal);
      color: #fff;
      font-size: 13.5px;
      font-weight: 500;
      padding: 11px 22px;
      border-radius: 40px;
      box-shadow: 0 4px 16px rgba(15,76,92,0.25);
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.25s, transform 0.25s;
      white-space: nowrap;
      z-index: 999;
    }
    .toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
  </style>
</head>
<body>

  <div class="modal-card">
    <div class="modal-body">
      <div style="text-align:center">
        <span class="db-badge">
          <svg width="11" height="11" viewBox="0 0 11 11" fill="none">
            <rect width="11" height="11" rx="2.5" fill="#157a56"/>
            <rect x="2.5" y="3" width="6" height="1" rx="0.5" fill="#fff"/>
            <rect x="2.5" y="5" width="4" height="1" rx="0.5" fill="#fff"/>
            <rect x="2.5" y="7" width="5" height="1" rx="0.5" fill="#fff"/>
          </svg>
          users · session
        </span>
      </div>

      <p class="modal-title-teal">Log Out</p>

      <p class="logout-body">
        Are you sure you want to log out?<br>
        You'll need to sign in again to continue.

      </p>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="showToast('Logout cancelled.')">Cancel</button>
      <button class="btn btn-teal" onclick="handleLogout()">Log Out</button>
    </div>
  </div>

  <div class="toast" id="toast"></div>

  <script>
    const session = {
      user_id: 1,
      email: "admin@scholarlink.ph",
      oauth_provider: null,
      role: "admin"
    };

    function handleLogout() {
      const provider = session.oauth_provider || 'email/password';
      showToast('Logged out via ' + provider + ' · user_id: ' + session.user_id);
    }

    let toastTimer;
    function showToast(msg) {
      const t = document.getElementById('toast');
      t.textContent = msg;
      t.classList.add('show');
      clearTimeout(toastTimer);
      toastTimer = setTimeout(() => t.classList.remove('show'), 3000);
    }
  </script>
</body>
</html>

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
