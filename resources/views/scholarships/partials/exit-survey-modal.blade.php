<div x-show="showExitSurvey" 
     style="display: none;"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
     x-transition.opacity>
    
    <div class="bg-white rounded-[20px] w-full max-w-[420px] shadow-2xl relative flex flex-col"
         @click.away="showExitSurvey = false"
         x-show="showExitSurvey"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <button @click="showExitSurvey = false" class="absolute top-5 right-5 text-[#B07B10] hover:text-[#8c600c] transition-colors bg-[#FDE68A]/50 w-6 h-6 rounded-full flex items-center justify-center">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <!-- Header -->
        <div class="bg-[#FFFBEB] p-6 pb-5 rounded-t-[20px] border-b border-[#FDE68A] shrink-0 text-center">
            <h2 class="font-display font-bold text-[20px] text-[#B07B10] mb-1">Having trouble applying? 🤔</h2>
            <p class="text-[11px] text-[#B07B10]/80">Quick 2-question check-in — takes 30 seconds</p>
        </div>

        <form action="#" method="POST" class="p-6">
            @csrf
            
            <div class="mb-6">
                <p class="text-[13px] font-bold text-[#0F4C5C] mb-3">1. What's stopping you from applying?</p>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="reason" value="missing_doc" class="w-4 h-4 text-[#0F4C5C] border-slate-300 focus:ring-[#0F4C5C]">
                        <span class="text-[13px] text-slate-600">I'm missing a required document</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="reason" value="requirements" class="w-4 h-4 text-[#0F4C5C] border-slate-300 focus:ring-[#0F4C5C]" checked>
                        <span class="text-[13px] text-slate-600">I don't think I meet the requirements</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="reason" value="deadline" class="w-4 h-4 text-[#0F4C5C] border-slate-300 focus:ring-[#0F4C5C]">
                        <span class="text-[13px] text-slate-600">The deadline is too close</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="reason" value="browsing" class="w-4 h-4 text-[#0F4C5C] border-slate-300 focus:ring-[#0F4C5C]">
                        <span class="text-[13px] text-slate-600">Just browsing / not ready yet</span>
                    </label>
                </div>
            </div>

            <div class="mb-8">
                <p class="text-[13px] font-bold text-[#0F4C5C] mb-3">2. Would you like help completing your application?</p>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="help" value="yes" class="w-4 h-4 text-[#0F4C5C] border-slate-300 focus:ring-[#0F4C5C]">
                        <span class="text-[13px] text-slate-600">Yes, show me what's missing</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="help" value="no" class="w-4 h-4 text-[#0F4C5C] border-slate-300 focus:ring-[#0F4C5C]" checked>
                        <span class="text-[13px] text-slate-600">No, I'll figure it out myself</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-between mt-2">
                <button type="button" @click="showExitSurvey = false" 
                        class="px-5 py-2 rounded-xl text-[12px] font-semibold text-slate-500 hover:bg-slate-50 transition-colors border border-slate-200">
                    Dismiss
                </button>
                <button type="submit" 
                        class="px-5 py-2 rounded-xl text-[12px] font-semibold text-[#0F4C5C] bg-[#F9D679] hover:bg-[#f5c754] transition-colors shadow-sm">
                    Submit Feedback
                </button>
            </div>
        </form>
    </div>
</div>
