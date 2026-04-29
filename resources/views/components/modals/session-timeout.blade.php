<div x-data="sessionTracker" 
     x-show="isWarningShown" 
     style="display: none;"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
     x-transition.opacity>
    
    <div class="bg-white rounded-[20px] w-full max-w-[400px] p-8 shadow-2xl relative text-center"
         @click.away="stayLoggedIn()"
         x-show="isWarningShown"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <div class="mx-auto w-12 h-12 bg-[#FFFBEB] rounded-full flex items-center justify-center mb-5 border border-[#FDE68A]">
            <svg class="w-6 h-6 text-[#B07B10]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>

        <h2 class="font-display font-bold text-[22px] text-[#0F4C5C] mb-2">Session Expiring</h2>
        <p class="text-[13px] text-slate-500 mb-6">You'll be automatically logged out in</p>

        <div class="flex items-end justify-center gap-2 mb-6">
            <div class="text-[48px] font-display font-bold text-[#0F4C5C] leading-none" x-text="countdownText">00:00</div>
            <div class="text-[12px] text-slate-400 font-bold uppercase tracking-wide mb-1 flex gap-4">
                <span>min</span>
                <span>sec</span>
            </div>
        </div>

        <p class="text-[12px] text-slate-400 mb-8">Any unsaved changes will be lost</p>

        <div class="flex items-center justify-center gap-3">
            <button type="button" @click="logout()" 
                    class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-colors border border-slate-200 bg-white min-w-[120px]">
                Log out now
            </button>
            <button type="button" @click="stayLoggedIn()" 
                    class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-white bg-[#0F4C5C] hover:bg-[#1a6878] shadow-md transition-colors border border-[#0F4C5C] min-w-[120px]">
                Stay logged in
            </button>
        </div>
        
        <form id="global-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('sessionTracker', () => ({
        idleSeconds: 0,
        warningLimit: 13 * 60, // Show warning after 13 minutes
        timeoutLimit: 15 * 60, // Logout after 15 minutes
        interval: null,
        isWarningShown: false,
        countdownText: '02:00',

        init() {
            this.resetIdleTime();
            const events = ['mousemove', 'keydown', 'scroll', 'click', 'touchstart'];
            events.forEach(event => {
                window.addEventListener(event, () => this.resetIdleTime(), true);
            });

            this.interval = setInterval(() => {
                this.idleSeconds++;
                
                if (this.idleSeconds >= this.warningLimit) {
                    if (!this.isWarningShown) this.isWarningShown = true;
                    this.updateCountdown();
                }
                
                if (this.idleSeconds >= this.timeoutLimit) {
                    this.logout();
                }
            }, 1000);
        },

        updateCountdown() {
            let remaining = this.timeoutLimit - this.idleSeconds;
            if (remaining < 0) remaining = 0;
            const m = Math.floor(remaining / 60);
            const s = remaining % 60;
            this.countdownText = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        },

        resetIdleTime() {
            this.idleSeconds = 0;
            this.isWarningShown = false;
        },

        logout() {
            clearInterval(this.interval);
            document.getElementById('global-logout-form').submit();
        },

        stayLoggedIn() {
            this.resetIdleTime();
        }
    }));
});
</script>
