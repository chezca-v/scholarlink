<div x-show="showWeightModal" 
     style="display: none;"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
     x-transition.opacity>
    
    <div class="bg-white rounded-[20px] w-full max-w-[540px] p-8 shadow-2xl relative"
         @click.away="showWeightModal = false"
         x-show="showWeightModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-data="{ academic: 60, financial: 40, get total() { return parseInt(this.academic) + parseInt(this.financial); } }">

        <button @click="showWeightModal = false" class="absolute top-6 right-6 w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <h2 class="font-display font-bold text-[22px] text-[#0F4C5C] mb-1">Configure Scoring Weights</h2>
        <p class="text-[12px] text-slate-500 mb-6">{{ $scholarship->name ?? 'Gabay Dunong Scholarship 2026' }}</p>

        <div class="bg-[#FFFBEB] border border-[#FDE68A] rounded-xl p-4 mb-6 flex items-start gap-3 text-[#B07B10]">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-[12px] leading-relaxed">
                <strong class="font-bold">Both weights must total 100%.</strong> The formula determines how each applicant's final score is computed during blind screening.
            </p>
        </div>

        <div class="space-y-6 mb-6 px-2">
            <!-- Academic Score -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">📚</span>
                        <span class="text-[13px] font-bold text-[#0F4C5C]">Academic Score (GPA/QPI)</span>
                    </div>
                    <span class="text-[15px] font-bold text-[#0F4C5C]" x-text="academic + '%'"></span>
                </div>
                <input type="range" x-model="academic" min="0" max="100" step="5" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#0F4C5C]">
            </div>

            <!-- Financial Need -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">💰</span>
                        <span class="text-[13px] font-bold text-[#0F4C5C]">Financial Need (Income)</span>
                    </div>
                    <span class="text-[15px] font-bold text-[#0F4C5C]" x-text="financial + '%'"></span>
                </div>
                <input type="range" x-model="financial" min="0" max="100" step="5" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#0F4C5C]">
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 mb-6 px-2">
            <span class="text-[13px] text-slate-500 font-medium">Total</span>
            <span class="text-[20px] font-bold" :class="total === 100 ? 'text-[#1a9653]' : 'text-[#D94848]'" x-text="total + '%'"></span>
            <span x-show="total === 100" class="flex items-center gap-1 text-[11px] font-bold text-[#1a9653] bg-[#e8f8ed] px-2 py-0.5 rounded-md ml-1 border border-[#1a9653]/20">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                Valid
            </span>
            <span x-show="total !== 100" class="flex items-center gap-1 text-[11px] font-bold text-[#D94848] bg-[#FEF2F2] px-2 py-0.5 rounded-md ml-1 border border-[#FECACA]">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                Invalid
            </span>
        </div>

        <div class="bg-[#f2f8fa] border border-[#e8f4f7] rounded-xl p-4 mb-8 text-center">
            <p class="text-[13px] font-bold text-[#0F4C5C] mb-1">
                Final Score = (GPA Score × <span x-text="academic + '%'"></span>) + (Income Score × <span x-text="financial + '%'"></span>)
            </p>
            <p class="text-[11px] text-[#1a6878]">Used for blind applicant ranking during evaluation.</p>
        </div>

        <div class="flex items-center justify-end gap-3">
            <button type="button" @click="showWeightModal = false" 
                    class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-colors border border-slate-200 bg-white">
                Cancel
            </button>
            <button type="button" 
                    :disabled="total !== 100"
                    :class="total === 100 ? 'bg-[#0F4C5C] hover:bg-[#1a6878] text-white border border-[#0F4C5C] shadow-md' : 'bg-slate-200 text-slate-400 cursor-not-allowed border border-slate-300'"
                    class="px-5 py-2.5 rounded-xl text-[13px] font-semibold transition-colors">
                Save Weights
            </button>
        </div>
    </div>
</div>
