<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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
