<div x-show="showConfirmModal" 
     style="display: none;"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
     x-transition.opacity>
    
    <div class="bg-white rounded-[20px] w-full max-w-[480px] p-8 shadow-2xl relative"
         @click.away="showConfirmModal = false"
         x-show="showConfirmModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <div class="text-center mb-6">
            <h2 class="font-display font-bold text-[22px] text-[#0F4C5C] mb-2">Confirm Application</h2>
            <p class="text-[13px] text-slate-500 leading-relaxed">
                You're about to submit your application for <strong class="text-[#0F4C5C] font-semibold">{{ $scholarship->name ?? 'Gabay Dunong Scholarship' }}</strong>. 
                Please review the details below:
            </p>
        </div>

        <div class="bg-[#f2f8fa] rounded-xl overflow-hidden mb-8 border border-[#e8f4f7]">
            <div class="flex justify-between items-center px-4 py-3 border-b border-white">
                <span class="text-[13px] text-slate-500 font-medium">Scholarship</span>
                <span class="text-[13px] font-semibold text-[#0F4C5C]">{{ $scholarship->name ?? 'Gabay Dunong 2026' }}</span>
            </div>
            <div class="flex justify-between items-center px-4 py-3 bg-[#e8f4f7] border-b border-white">
                <span class="text-[13px] text-slate-500 font-medium">Documents attached</span>
                <span class="text-[13px] font-semibold text-[#0F4C5C]">4 files</span>
            </div>
            <div class="flex justify-between items-center px-4 py-3 border-b border-white">
                <span class="text-[13px] text-slate-500 font-medium">Deadline</span>
                <span class="text-[13px] font-semibold text-[#0F4C5C]">{{ $scholarship->deadline ? \Carbon\Carbon::parse($scholarship->deadline)->format('M d, Y') : 'May 31, 2026' }}</span>
            </div>
            <div class="flex justify-between items-center px-4 py-3 bg-[#e8f4f7]">
                <span class="text-[13px] text-slate-500 font-medium">Status after submit</span>
                <span class="text-[13px] font-bold text-[#0F4C5C]">Under Review</span>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <button type="button" @click="showConfirmModal = false" 
                    class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-colors border border-slate-200 bg-white">
                Cancel
            </button>
            <button type="button" @click="$refs.submitForm.submit()" 
                    class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-white bg-[#0F4C5C] hover:bg-[#1a6878] shadow-md transition-colors border border-[#0F4C5C]">
                Submit Application
            </button>
        </div>
    </div>
</div>
