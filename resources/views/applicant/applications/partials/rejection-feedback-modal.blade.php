<div x-show="showRejectionModal" 
     style="display: none;"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
     x-transition.opacity>
    
    <div class="bg-white rounded-[20px] w-full max-w-[500px] p-8 shadow-2xl relative"
         @click.away="showRejectionModal = false"
         x-show="showRejectionModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <h2 class="font-display font-bold text-[22px] text-[#0F4C5C] mb-1">Application Not Approved</h2>
        <p class="text-[12px] text-slate-500 mb-6">
            {{ $application->scholarship->name ?? 'Gabay Dunong Scholarship' }} • Applied {{ $application->created_at ? $application->created_at->format('F j, Y') : 'January 10, 2026' }}
        </p>

        <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-xl p-4 mb-4">
            <h4 class="text-[10px] font-bold text-[#DC2626] uppercase tracking-widest mb-1.5">Rejection Reason</h4>
            <p class="text-[13px] text-[#991B1B] leading-relaxed">
                {{ $application->rejection_reason ?? 'GPA does not meet the minimum requirement of 1.75 for the current academic year.' }}
            </p>
        </div>

        <div class="bg-[#F0F9FF] border border-[#BAE6FD] rounded-xl p-4 mb-6">
            <h4 class="text-[10px] font-bold text-[#0369A1] uppercase tracking-widest mb-1.5">Evaluator Notes</h4>
            <p class="text-[13px] text-[#075985] leading-relaxed">
                {{ $application->evaluator_notes ?? 'The applicant shows strong financial need. However, the academic record submitted for AY 2024-2025 does not meet the GPA threshold. We recommend re-applying after improving academic standing.' }}
            </p>
        </div>

        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Suggested Alternatives</h4>
        <div class="flex flex-col gap-2 mb-8">
            <div class="flex items-center justify-between p-3 rounded-xl border border-slate-200 hover:border-[#0F4C5C]/30 transition-colors cursor-pointer bg-white">
                <div>
                    <div class="text-[13px] font-bold text-[#0F4C5C]">PLM Opportunity Scholarship</div>
                    <div class="text-[11px] text-slate-500 mt-0.5">₱12,000/semester</div>
                </div>
                <div class="px-2 py-1 rounded-md bg-[#e8f4f7] text-[#0F4C5C] text-[11px] font-bold">67%</div>
            </div>
            <div class="flex items-center justify-between p-3 rounded-xl border border-slate-200 hover:border-[#0F4C5C]/30 transition-colors cursor-pointer bg-white">
                <div>
                    <div class="text-[13px] font-bold text-[#0F4C5C]">DOST Engineering Grant</div>
                    <div class="text-[11px] text-slate-500 mt-0.5">₱15,000/semester</div>
                </div>
                <div class="px-2 py-1 rounded-md bg-[#e8f8ed] text-[#1a9653] text-[11px] font-bold">81%</div>
            </div>
        </div>

        <div class="flex items-center justify-center gap-3">
            <button type="button" @click="showRejectionModal = false" 
                    class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-colors border border-slate-200 bg-white min-w-[120px]">
                Close
            </button>
            <a href="#" 
               class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-[#0F4C5C] hover:bg-slate-50 transition-colors border border-[#0F4C5C]/30 bg-white text-center min-w-[180px]">
                Browse all scholarships
            </a>
        </div>
    </div>
</div>
