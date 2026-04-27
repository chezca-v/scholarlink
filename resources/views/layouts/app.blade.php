<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ScholarLink') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased" x-data="sessionTracker()">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <x-modal name="confirm-logout" maxWidth="sm">
            <div class="bg-white rounded-2xl overflow-hidden shadow-[0_4px_24px_rgba(15,76,92,0.08)]">

                <div class="px-7 py-7 pb-5 text-center">
                    <h2 class="text-[25px] font-bold text-[#0F4C5C] mb-2.5 leading-tight font-serif">
                        Log Out
                    </h2>
                    <p class="text-[13px] text-[#0F4C5C] leading-relaxed font-sans">
                        Are you sure you want to log out?<br>
                        You'll need to sign in again to continue.
                    </p>
                </div>

                <div class="flex items-center justify-center gap-10 px-7 py-4 border-t border-[#C8E8E4] bg-white">

                    <button x-on:click="$dispatch('close-modal', 'confirm-logout')" type="button" class="px-5 py-2 rounded-lg text-[13.5px] font-medium text-[#0F4C5C] border border-[#C8E8E4] hover:bg-[#F4F6FA] transition-colors font-sans">
                        Cancel
                    </button>

                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="px-5 py-2 rounded-lg text-[13.5px] font-medium text-[#F9D679] bg-gradient-to-br from-[#0F4C5C] to-[#1A6B7A] shadow-[0_4px_12px_rgba(15,76,92,0.25)] hover:-translate-y-0.5 transition-all font-sans">
                            Log Out
                        </button>
                    </form>

                </div>
            </div>
        </x-modal>
        <!-- Session Timeout Modal -->
        @include('components.modals.session-timeout')

        <!-- Auto Logout Form -->
        <form id="auto-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <!-- Session Tracker Script -->
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('sessionTracker', () => ({
                    idleSeconds: 0,
                    warningLimit: 13 * 60, // 13 minutes: Trigger warning modal
                    timeoutLimit: 15 * 60, // 15 minutes: Force logout
                    interval: null,
                    isWarningShown: false,

                    init() {
                        this.resetIdleTime();

                        // Reset timer on user interaction
                        const events = ['mousemove', 'keydown', 'scroll', 'click', 'touchstart'];
                        events.forEach(event => {
                            window.addEventListener(event, () => this.resetIdleTime(), true);
                        });

                        // Start the countdown ticker
                        this.interval = setInterval(() => {
                            this.idleSeconds++;

                            // Trigger session timeout warning modal
                            if (this.idleSeconds === this.warningLimit && !this.isWarningShown) {
                                this.isWarningShown = true;
                                const modal = document.getElementById('session-timeout-modal');
                                if (modal) modal.style.display = 'block';
                            }

                            // Force logout after timeout
                            if (this.idleSeconds >= this.timeoutLimit) {
                                clearInterval(this.interval);
                                document.getElementById('auto-logout-form').submit();
                            }
                        }, 1000);
                    },

                    resetIdleTime() {
                        this.idleSeconds = 0;
                        this.isWarningShown = false;
                        // Close modal if user resumes activity
                        const modal = document.getElementById('session-timeout-modal');
                        if (modal) {
                            modal.style.display = 'none';
                        }
                    },

                    logout() {
                        document.getElementById('auto-logout-form').submit();
                    },

                    stayLoggedIn() {
                        this.resetIdleTime();
                        const modal = document.getElementById('session-timeout-modal');
                        if (modal) {
                            modal.style.display = 'none';
                        }
                    }
                }));
            });
        </script>
    </body>
</html>
