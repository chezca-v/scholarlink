<div x-show="showScholarshipDrawer" 
     style="display: none;"
     class="fixed inset-0 z-[9999] flex justify-end"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="showScholarshipDrawer = false"></div>

    <!-- Drawer Panel -->
    <div class="relative w-full max-w-[440px] bg-white h-full shadow-2xl flex flex-col"
         x-show="showScholarshipDrawer"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         @click.stop>
        
        <!-- Header Image Area (Optional context) -->
        <div class="h-[120px] bg-gradient-to-br from-[#0F4C5C] to-[#1a6878] relative shrink-0">
            <button @click="showScholarshipDrawer = false" class="absolute top-6 left-6 w-8 h-8 flex items-center justify-center rounded-full bg-black/20 text-white hover:bg-black/40 transition-colors backdrop-blur-md">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
        </div>

        <div class="p-8 grow overflow-y-auto">
            <div class="mb-5 flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide uppercase bg-[#e8f8ed] text-[#1a9653] border border-[#1a9653]/20">
                    Open • 12 slots left
                </span>
            </div>

            <h2 class="font-display font-bold text-[26px] text-[#0F4C5C] leading-tight mb-2">
                {{ $scholarship->name ?? 'Gabay Dunong Scholarship 2026' }}
            </h2>
            <p class="text-[13px] text-slate-500 mb-8">Gabay Foundation PH • Nationwide</p>

            <!-- Match Score -->
            <div class="text-center mb-8">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Your Match Score</p>
                <div class="text-[32px] font-display font-bold text-[#D94848] mb-2">87%</div>
                <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden max-w-[200px] mx-auto">
                    <div class="h-full bg-gradient-to-r from-[#D94848] to-[#ea8c55] rounded-full" style="width: 87%"></div>
                </div>
            </div>

            <!-- Details Table -->
            <div class="bg-[#f2f8fa] rounded-[16px] overflow-hidden mb-6 border border-[#e8f4f7]">
                <div class="flex justify-between items-center px-5 py-3.5 border-b border-white">
                    <span class="text-[12px] text-slate-500 font-medium">Grant Amount</span>
                    <span class="text-[13px] font-bold text-[#0F4C5C]">₱30,000/yr</span>
                </div>
                <div class="flex justify-between items-center px-5 py-3.5 bg-[#e8f4f7] border-b border-white">
                    <span class="text-[12px] text-slate-500 font-medium">GPA required</span>
                    <span class="text-[13px] font-bold text-[#0F4C5C]">≥ 90</span>
                </div>
                <div class="flex justify-between items-center px-5 py-3.5 border-b border-white">
                    <span class="text-[12px] text-slate-500 font-medium">Income Bracket</span>
                    <span class="text-[13px] font-bold text-[#0F4C5C]">≤ ₱300k/yr</span>
                </div>
                <div class="flex justify-between items-center px-5 py-3.5 bg-[#e8f4f7] border-b border-white">
                    <span class="text-[12px] text-slate-500 font-medium">Program</span>
                    <span class="text-[13px] font-bold text-[#0F4C5C]">Engineering, CS, IT</span>
                </div>
                <div class="flex justify-between items-center px-5 py-3.5">
                    <span class="text-[12px] text-slate-500 font-medium">Deadline</span>
                    <span class="text-[13px] font-bold text-[#0F4C5C]">March 31, 2026</span>
                </div>
            </div>

            <p class="text-[13px] text-slate-600 leading-relaxed mb-8">
                Full merit-based scholarship for outstanding STEM students from low-income families enrolled in accredited PH universities.
            </p>

            <div class="grid grid-cols-3 gap-3">
                <a href="#" class="col-span-2 flex items-center justify-center px-4 py-3.5 rounded-xl text-[14px] font-bold text-[#0F4C5C] bg-[#F9D679] hover:bg-[#f5c754] transition-colors shadow-md">
                    Apply Now
                </a>
                <button type="button" class="col-span-1 flex items-center justify-center gap-2 px-4 py-3.5 rounded-xl text-[13px] font-bold text-[#0F4C5C] bg-white border border-[#0F4C5C]/20 hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
