<div x-show="showNotifyModal" 
     style="display: none;"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
     x-transition.opacity
     x-data="{ isSubscribed: false, email: '{{ auth()->user()->email ?? '' }}', withSms: true }">
    
    <div class="bg-white rounded-[20px] w-full max-w-[440px] shadow-2xl relative flex flex-col"
         @click.away="showNotifyModal = false; setTimeout(() => isSubscribed = false, 300)"
         x-show="showNotifyModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <button @click="showNotifyModal = false; setTimeout(() => isSubscribed = false, 300)" class="absolute top-5 right-5 text-white/70 hover:text-white transition-colors bg-black/20 w-6 h-6 rounded-full flex items-center justify-center z-10" x-show="!isSubscribed">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <!-- Get Notified Form State -->
        <div x-show="!isSubscribed" x-transition.opacity>
            <div class="bg-[#0F4C5C] p-6 pb-5 rounded-t-[20px] shrink-0">
                <h2 class="font-display font-bold text-[20px] text-white mb-1">Get Notified</h2>
                <p class="text-[11px] text-[#e8f4f7]">{{ $scholarship->name ?? 'Lumina Excellence Fund' }} — Opening Jan 2026</p>
            </div>

            <div class="p-6">
                <p class="text-[13px] text-slate-500 mb-5 leading-relaxed">
                    We'll email you when this scholarship opens for applications. No spam, just one notification.
                </p>

                <div class="mb-4">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-2">Email Address</label>
                    <input type="email" x-model="email" placeholder="juan.delacruz@plm.edu.ph" required
                           class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-[13px] text-slate-700 focus:outline-none focus:border-[#0F4C5C] focus:ring-1 focus:ring-[#0F4C5C] transition-all">
                </div>

                <div class="mb-8">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <div class="relative flex items-center">
                            <input type="checkbox" x-model="withSms" class="w-4 h-4 text-[#0F4C5C] border-slate-300 rounded focus:ring-[#0F4C5C]">
                        </div>
                        <span class="text-[13px] text-slate-600 font-medium select-none">Also notify me via SMS</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="button" @click="showNotifyModal = false" 
                            class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-colors border border-slate-200 bg-white">
                        Cancel
                    </button>
                    <!-- Simulated submit for demo -->
                    <button type="button" @click="isSubscribed = true" 
                            class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-white bg-[#0F4C5C] hover:bg-[#1a6878] shadow-md transition-colors border border-[#0F4C5C] flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        Notify Me
                    </button>
                </div>
            </div>
        </div>

        <!-- Success State -->
        <div x-show="isSubscribed" x-transition.opacity style="display: none;" class="p-8 pb-10 text-center">
            <div class="text-[48px] mb-4">🎉</div>
            <h2 class="font-display font-bold text-[24px] text-[#0F4C5C] mb-3">You're on the list!</h2>
            <p class="text-[13px] text-slate-500 leading-relaxed mb-8 px-4">
                We'll notify you at <strong class="text-[#0F4C5C]" x-text="email"></strong> as soon as <strong class="text-[#0F4C5C]">{{ $scholarship->name ?? 'Lumina Excellence Fund' }}</strong> opens for applications.
            </p>
            <button type="button" @click="showNotifyModal = false; setTimeout(() => isSubscribed = false, 300)" 
                    class="w-full max-w-[240px] mx-auto block text-center px-4 py-3 rounded-xl text-[13px] font-bold text-white bg-[#0F4C5C] hover:bg-[#1a6878] transition-colors shadow-md">
                Browse Other Scholarships
            </button>
        </div>

    </div>
</div>
