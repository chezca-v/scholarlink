<div x-show="showSmsModal" 
     style="display: none;"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
     x-transition.opacity>
    
    <div class="bg-white rounded-[20px] w-full max-w-[420px] shadow-2xl relative flex flex-col overflow-hidden"
         @click.away="showSmsModal = false"
         x-show="showSmsModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <button @click="showSmsModal = false" class="absolute top-5 right-5 text-white/70 hover:text-white transition-colors bg-black/20 w-6 h-6 rounded-full flex items-center justify-center z-10">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div class="bg-[#0F4C5C] p-6 pb-6 shrink-0">
            <h2 class="font-display font-bold text-[20px] text-white mb-1">Enable SMS Notifications?</h2>
            <p class="text-[11px] text-[#e8f4f7]">Via ScholarLink SMS Gateway</p>
        </div>

        <div class="p-6">
            <div class="bg-[#f8fafc] border border-slate-200 rounded-xl p-3 flex items-center gap-4 mb-6 shadow-sm -mt-10 relative z-10 bg-white">
                <div class="w-10 h-10 rounded-lg bg-[#0F4C5C] text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <div class="text-[14px] font-bold text-[#0F4C5C]">+63 917 123 4567</div>
                    <div class="text-[10px] text-slate-400 font-medium">Notifications will be sent to this number</div>
                </div>
            </div>

            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">You will receive SMS for:</p>
            
            <div class="space-y-3 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-5 h-5 rounded flex items-center justify-center bg-[#e8f8ed] text-[#1a9653]">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-[13px] text-slate-600 font-medium">Application status updates</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-5 h-5 rounded flex items-center justify-center bg-[#FFFBEB] text-[#B07B10]">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[13px] text-slate-600 font-medium">Scholarship deadline reminders</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-5 h-5 rounded flex items-center justify-center bg-[#FEF2F2] text-[#DC2626]">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="text-[13px] text-slate-600 font-medium">Document verification results</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-5 h-5 rounded flex items-center justify-center bg-amber-50 text-amber-500">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <span class="text-[13px] text-slate-600 font-medium">Approval and rejection decisions</span>
                </div>
            </div>

            <div class="bg-[#f2f8fa] border border-[#e8f4f7] rounded-xl p-3 mb-6">
                <p class="text-[11px] text-[#1a6878] leading-relaxed flex gap-2 items-start">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Messages are sent via our hardware SMS gateway. Standard carrier rates may apply. You can disable this anytime in Settings.
                </p>
            </div>

            <div class="flex items-center justify-between">
                <button type="button" @click="showSmsModal = false" 
                        class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                    No Thanks
                </button>
                <form action="#" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" 
                            class="px-6 py-2.5 rounded-xl text-[13px] font-semibold text-white bg-[#0F4C5C] hover:bg-[#1a6878] shadow-md transition-colors border border-[#0F4C5C] flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Enable SMS
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
