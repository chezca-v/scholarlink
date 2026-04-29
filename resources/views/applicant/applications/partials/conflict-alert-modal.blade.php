<div x-show="showConflictModal" 
     style="display: none;"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
     x-transition.opacity>
    
    <div class="bg-white rounded-[20px] w-full max-w-[480px] p-8 shadow-2xl relative"
         @click.away="showConflictModal = false"
         x-show="showConflictModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <div class="flex items-start gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-[#FFFBEB] text-[#B07B10] flex items-center justify-center flex-shrink-0 border border-[#FDE68A]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h2 class="font-display font-bold text-[20px] text-[#B07B10] leading-tight">Conflict Detected</h2>
                <p class="text-[12px] text-[#B07B10]/80">You may have a scholarship conflict.</p>
            </div>
        </div>

        <p class="text-[13px] text-slate-600 leading-relaxed mb-6">
            Applying to <strong class="text-[#0F4C5C] font-semibold">{{ $scholarship->name ?? 'TechBridge STEM Scholarship' }}</strong> may conflict with your existing scholarship. Some organizations do not allow students to hold multiple scholarships simultaneously.
        </p>

        <div class="bg-[#fcf8f8] border border-[#f5e6e6] rounded-xl p-4 mb-5">
            <h4 class="text-[10px] font-bold text-[#D94848] uppercase tracking-widest mb-3 flex items-center gap-1.5">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Detected Conflicts
            </h4>
            
            <div class="flex items-center justify-between py-2 border-b border-[#f5e6e6]/60">
                <div class="text-[13px] font-bold text-[#0F4C5C]">Gabay Dunong Scholarship 2026</div>
                <div class="text-[11px] font-bold text-[#7C5CBF]">Under Review</div>
            </div>
            <div class="flex items-center justify-between py-2">
                <div class="text-[13px] font-bold text-[#0F4C5C]">Abot-Kaya Excellence Grant</div>
                <div class="text-[11px] font-bold text-[#1A9E6A]">Approved</div>
            </div>
        </div>

        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 mb-8 flex items-start gap-2">
            <svg class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-[11px] text-slate-500 leading-relaxed">
                <strong class="font-bold text-slate-600">Note:</strong> Proceeding does not guarantee acceptance. Final conflict decisions rest with the scholarship organization.
            </p>
        </div>

        <div class="flex items-center justify-end gap-3">
            <button type="button" @click="showConflictModal = false" 
                    class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-colors border border-slate-200 bg-white">
                Cancel
            </button>
            <button type="button" @click="$refs.submitForm.submit()" 
                    class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-[#D94848] bg-[#FEF2F2] hover:bg-[#FEE2E2] shadow-sm transition-colors border border-[#FECACA]">
                Proceed Anyway
            </button>
        </div>
    </div>
</div>
