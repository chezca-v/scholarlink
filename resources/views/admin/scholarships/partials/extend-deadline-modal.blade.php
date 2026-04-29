<div x-show="showExtendModal" 
     style="display: none;"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
     x-transition.opacity>
    
    <div class="bg-white rounded-[20px] w-full max-w-[480px] p-8 shadow-2xl relative"
         @click.away="showExtendModal = false"
         x-show="showExtendModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <h2 class="font-display font-bold text-[22px] text-[#0F4C5C] mb-4 text-center">Extend Application Deadline</h2>
        
        <div class="mb-6 text-center">
            <p class="text-[12px] text-slate-500 mb-1">
                Scholarship: <strong class="text-[#0F4C5C] font-bold">{{ $scholarship->name ?? 'Gabay Dunong Scholarship 2026' }}</strong>
            </p>
            <p class="text-[12px] text-slate-500">
                Current Deadline: <span class="bg-[#f2f8fa] text-[#0F4C5C] px-2 py-0.5 rounded font-bold border border-[#e8f4f7]">{{ $scholarship->deadline ? \Carbon\Carbon::parse($scholarship->deadline)->format('M d, Y') : 'May 31, 2026' }}</span>
            </p>
        </div>

        <form action="{{ isset($scholarship) ? route('admin.scholarships.extend', $scholarship->id) : '#' }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-[11px] font-bold text-[#0F4C5C] mb-1.5 ml-1">New Deadline</label>
                <div class="relative">
                    <input type="date" name="new_deadline" required
                           class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-[13px] text-slate-700 focus:outline-none focus:border-[#0F4C5C] focus:ring-1 focus:ring-[#0F4C5C] transition-all">
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-[11px] font-bold text-[#0F4C5C] mb-1.5 ml-1">Reason for extension</label>
                <textarea name="reason" rows="3" required
                          placeholder="e.g. Extended due to public holiday..."
                          class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-[13px] text-slate-700 focus:outline-none focus:border-[#0F4C5C] focus:ring-1 focus:ring-[#0F4C5C] transition-all resize-none"></textarea>
            </div>

            <div class="flex items-center justify-between p-4 bg-[#e8f8ed]/50 rounded-xl border border-[#1a9653]/10 mb-8" x-data="{ notify: true }">
                <div class="flex items-center gap-2 text-[#1a9653]">
                    <span class="text-[13px] font-bold">Notify all applicants</span>
                </div>
                <!-- Toggle switch -->
                <button type="button" 
                        class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                        :class="notify ? 'bg-[#1a9653]' : 'bg-slate-200'"
                        @click="notify = !notify">
                    <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                          :class="notify ? 'translate-x-4' : 'translate-x-0'"></span>
                    <input type="hidden" name="notify_applicants" :value="notify ? '1' : '0'">
                </button>
            </div>

            <div class="flex items-center justify-end gap-3">
                <button type="button" @click="showExtendModal = false" 
                        class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-colors border border-slate-200 bg-white">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl text-[13px] font-semibold text-white bg-[#0F4C5C] hover:bg-[#1a6878] shadow-md transition-colors border border-[#0F4C5C]">
                    Save Extension
                </button>
            </div>
        </form>
    </div>
</div>
