<div x-data="toastManager" 
     class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 max-w-[320px] w-full items-end"
     @notify.window="addToast($event.detail)">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.show"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-x-10"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-10"
             class="w-full rounded-xl shadow-lg border border-white/10 overflow-hidden relative"
             :class="{
                 'bg-[#1a9653]': toast.type === 'success',
                 'bg-[#0F4C5C]': toast.type === 'info',
                 'bg-[#B07B10]': toast.type === 'warning',
                 'bg-[#DC2626]': toast.type === 'error',
                 'bg-[#1e293b]': toast.type === 'sms'
             }">
            
            <div class="p-3.5 pr-10 flex items-start gap-3">
                <!-- Icon -->
                <div class="shrink-0 text-white/90 mt-0.5">
                    <template x-if="toast.type === 'success'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </template>
                    <template x-if="toast.type === 'warning'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </template>
                    <template x-if="toast.type === 'sms'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </template>
                </div>

                <!-- Text -->
                <div class="flex-1">
                    <p class="text-[13px] font-bold text-white leading-tight mb-0.5" x-text="toast.title"></p>
                    <p class="text-[11px] text-white/70 leading-snug" x-text="toast.message"></p>
                </div>

                <!-- Action Button -->
                <template x-if="toast.action">
                    <a :href="toast.actionUrl || '#'" class="absolute top-1/2 -translate-y-1/2 right-9 text-[11px] font-bold text-white hover:text-white/80 transition-colors flex items-center gap-1">
                        <span x-text="toast.action"></span>
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </template>
            </div>

            <!-- Close Button -->
            <button @click="removeToast(toast.id)" class="absolute top-3.5 right-3 text-white/50 hover:text-white transition-colors">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Progress Bar -->
            <div class="absolute bottom-0 left-0 h-[3px] bg-black/20 w-full">
                <div class="h-full bg-white/40" 
                     :style="`width: ${toast.progress}%; transition: width 100ms linear;`"></div>
            </div>
        </div>
    </template>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('toastManager', () => ({
        toasts: [],
        
        init() {
            // Uncomment to demo all 5 toasts on load:
            /*
            setTimeout(() => {
                this.addToast({ type: 'success', title: 'Application approved!', message: 'Gabay Dunong Scholarship 2026', action: 'View' });
                setTimeout(() => this.addToast({ type: 'info', title: '3 new scholarship matches found', message: 'Based on your updated profile', action: 'See all' }), 500);
                setTimeout(() => this.addToast({ type: 'warning', title: 'Document expiring in 7 days', message: 'Certificate of Indigency', action: 'Upload' }), 1000);
                setTimeout(() => this.addToast({ type: 'error', title: 'Application submission failed', message: 'Please check your documents', action: 'Retry' }), 1500);
                setTimeout(() => this.addToast({ type: 'sms', title: 'SMS sent to +63 917 123 4567', message: 'Interview schedule notification' }), 2000);
            }, 1000);
            */
        },

        addToast(toast) {
            const id = Date.now() + Math.random().toString(36).substring(2, 9);
            const duration = toast.duration || 5000;
            
            const newToast = {
                id,
                ...toast,
                show: true,
                progress: 100
            };
            
            this.toasts.push(newToast);

            // Animate progress bar
            const step = 100; // ms
            const steps = duration / step;
            let currentStep = 0;

            const progressInterval = setInterval(() => {
                currentStep++;
                const toastIndex = this.toasts.findIndex(t => t.id === id);
                if (toastIndex !== -1) {
                    this.toasts[toastIndex].progress = 100 - ((currentStep / steps) * 100);
                }

                if (currentStep >= steps) {
                    clearInterval(progressInterval);
                    this.removeToast(id);
                }
            }, step);
        },

        removeToast(id) {
            const index = this.toasts.findIndex(t => t.id === id);
            if (index !== -1) {
                this.toasts[index].show = false;
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 300); // Wait for exit animation
            }
        }
    }));
});
</script>
