<div x-show="showSuccessModal" 
     style="display: none;"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
     x-transition.opacity>
    
    <div class="bg-white rounded-[20px] w-full max-w-[420px] p-8 pb-10 shadow-2xl relative text-center"
         @click.away="showSuccessModal = false"
         x-show="showSuccessModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <div class="mx-auto w-14 h-14 bg-[#0F4C5C] rounded-full flex items-center justify-center mb-6 shadow-md border-4 border-[#e8f4f7]">
            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
        </div>

        <h2 class="font-display font-bold text-[24px] text-[#0F4C5C] mb-3">Application Submitted!</h2>
        <p class="text-[13px] text-slate-500 leading-relaxed mb-6 px-2">
            Your application for <strong class="text-[#0F4C5C]">Gabay Dunong Scholarship 2026</strong> has been received. You'll get updates via email and SMS.
        </p>

        <div class="mb-8">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Application ID</p>
            <div class="inline-flex items-center justify-center px-6 py-2 bg-[#fdf7e3] border border-[#f9d679] text-[#b07b10] font-bold text-lg rounded-full mb-2">
                SL-2026-00214
            </div>
            <p class="text-[11px] text-slate-400"><span class="bg-[#f2f8fa] text-slate-500 px-2 py-0.5 rounded border border-slate-100">Estimated review: 7-14 days</span></p>
        </div>

        <div class="text-left bg-slate-50/50 rounded-xl p-4 mb-8">
            <div class="flex items-start gap-3 mb-4">
                <div class="w-5 h-5 rounded-full bg-[#1a9653] text-white flex items-center justify-center flex-shrink-0 mt-0.5"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                <div class="text-[13px] font-semibold text-[#1a9653]">Application submitted</div>
            </div>
            <div class="flex items-start gap-3 mb-4">
                <div class="w-5 h-5 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center flex-shrink-0 mt-0.5 text-[11px] font-bold">2</div>
                <div class="text-[13px] text-slate-500 font-medium">Document verification</div>
            </div>
            <div class="flex items-start gap-3 mb-4">
                <div class="w-5 h-5 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center flex-shrink-0 mt-0.5 text-[11px] font-bold">3</div>
                <div class="text-[13px] text-slate-500 font-medium">Evaluation and scoring</div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-5 h-5 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center flex-shrink-0 mt-0.5 text-[11px] font-bold">4</div>
                <div class="text-[13px] text-slate-500 font-medium">Final decision</div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <a href="#" class="block w-full text-center px-4 py-2.5 rounded-xl text-[12px] font-semibold text-[#0F4C5C] hover:bg-slate-50 transition-colors border border-[#0F4C5C]/30">
                Browse more<br>scholarships
            </a>
            <a href="#" class="block w-full text-center px-4 py-2.5 rounded-xl text-[12px] font-semibold text-[#0F4C5C] hover:bg-slate-50 transition-colors border border-[#0F4C5C]/30 flex items-center justify-center">
                Track my application
            </a>
        </div>
    </div>
</div>
