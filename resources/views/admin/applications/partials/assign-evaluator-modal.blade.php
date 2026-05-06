<div id="assign-evaluator-modal"
     x-data="{ showAssignModal: false, selectedEvaluator: 1, searchQuery: '' }"
     x-show="showAssignModal"
     style="display: none;"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
     x-transition.opacity>
    
    <div class="bg-white rounded-[20px] w-full max-w-[500px] shadow-2xl relative flex flex-col max-h-[90vh]"
         @click.away="showAssignModal = false"
         x-show="showAssignModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <!-- Header -->
        <div class="p-6 pb-4 border-b border-slate-100 shrink-0">
            <button @click="showAssignModal = false" class="absolute top-6 right-6 w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-800 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h2 class="font-display font-bold text-[22px] text-[#0F4C5C] mb-1">Assign Evaluator</h2>
            <p class="text-[12px] text-slate-500">Selected application(s)</p>
            
            <div class="mt-5 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" x-model="searchQuery" placeholder="Search evaluators..." class="w-full bg-[#f8fafc] border border-slate-200 rounded-xl pl-9 pr-4 py-2.5 text-[13px] text-slate-700 focus:outline-none focus:border-[#0F4C5C] focus:bg-white transition-colors">
            </div>
        </div>

        <!-- List -->
        <div class="p-6 overflow-y-auto grow">
            <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Available Evaluators (4)</h4>
            <div class="flex flex-col gap-2">
                <!-- Evaluator 1 -->
                <div @click="selectedEvaluator = 1" 
                     class="flex items-center justify-between p-3 rounded-xl border transition-colors cursor-pointer"
                     :class="selectedEvaluator === 1 ? 'border-[#0F4C5C] bg-[#f2f8fa]' : 'border-slate-200 bg-white hover:border-[#0F4C5C]/30'">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#0F4C5C] text-white flex items-center justify-center font-bold text-[12px] shrink-0">EP</div>
                        <div>
                            <div class="text-[13px] font-bold text-[#0F4C5C] flex items-center gap-2">
                                Engr. Paulo Reyes
                                <svg x-show="selectedEvaluator === 1" class="w-4 h-4 text-[#0F4C5C]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Lead Evaluator • TechBridge Scholarship</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-[14px] font-bold text-[#0F4C5C]">6</div>
                        <div class="text-[9px] text-slate-400 uppercase tracking-wide">in queue</div>
                    </div>
                </div>

                <!-- Evaluator 2 -->
                <div @click="selectedEvaluator = 2" 
                     class="flex items-center justify-between p-3 rounded-xl border transition-colors cursor-pointer"
                     :class="selectedEvaluator === 2 ? 'border-[#0F4C5C] bg-[#f2f8fa]' : 'border-slate-200 bg-white hover:border-[#0F4C5C]/30'">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#7C5CBF] text-white flex items-center justify-center font-bold text-[12px] shrink-0">DM</div>
                        <div>
                            <div class="text-[13px] font-bold text-[#0F4C5C] flex items-center gap-2">
                                Dr. Maria Ocampo
                                <svg x-show="selectedEvaluator === 2" class="w-4 h-4 text-[#0F4C5C]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Senior Evaluator • Gabay Foundation</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-[14px] font-bold text-[#D94848]">14</div>
                        <div class="text-[9px] text-[#D94848]/70 uppercase tracking-wide">in queue</div>
                    </div>
                </div>

                <!-- Evaluator 3 -->
                <div @click="selectedEvaluator = 3" 
                     class="flex items-center justify-between p-3 rounded-xl border transition-colors cursor-pointer"
                     :class="selectedEvaluator === 3 ? 'border-[#0F4C5C] bg-[#f2f8fa]' : 'border-slate-200 bg-white hover:border-[#0F4C5C]/30'">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#1A9E6A] text-white flex items-center justify-center font-bold text-[12px] shrink-0">RL</div>
                        <div>
                            <div class="text-[13px] font-bold text-[#0F4C5C] flex items-center gap-2">
                                Rico Lim
                                <svg x-show="selectedEvaluator === 3" class="w-4 h-4 text-[#0F4C5C]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Evaluator • Abot-Kaya Inc.</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-[14px] font-bold text-[#0F4C5C]">2</div>
                        <div class="text-[9px] text-slate-400 uppercase tracking-wide">in queue</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-slate-100 bg-slate-50 rounded-b-[20px] shrink-0">
            <div class="flex items-center justify-between mb-4">
                <div class="text-[12px] text-slate-500">Selected: <strong class="text-[#0F4C5C]" x-text="selectedEvaluator === 1 ? 'Engr. Paulo Reyes' : (selectedEvaluator === 2 ? 'Dr. Maria Ocampo' : 'Rico Lim')"></strong></div>
                <div class="bg-white border border-slate-200 px-2 py-1 rounded text-[11px] font-bold text-[#0F4C5C] shadow-sm"><span x-text="selectedEvaluator === 1 ? '6' : (selectedEvaluator === 2 ? '14' : '2')"></span> in queue</div>
            </div>
            <div class="flex items-center justify-end gap-3">
                <button type="button" @click="showAssignModal = false" 
                        class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-colors border border-slate-200 bg-white">
                    Cancel
                </button>
                <button type="button" 
                        class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-white bg-[#0F4C5C] hover:bg-[#1a6878] shadow-md transition-colors border border-[#0F4C5C] flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Assign Evaluator
                </button>
            </div>
        </div>
    </div>
</div>
