<div x-data="{ open: false, activeTab: 'all' }" class="relative">
    <!-- Trigger -->
    <button @click="open = !open" class="relative p-2 text-slate-500 hover:text-[#0F4C5C] hover:bg-slate-100 rounded-full transition-colors focus:outline-none">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#D94848] rounded-full border-2 border-white"></span>
    </button>

    <!-- Dropdown -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="absolute right-0 mt-2 w-[380px] bg-white rounded-[20px] shadow-2xl border border-slate-100 z-50 overflow-hidden"
         style="display: none;">
        
        <!-- Header -->
        <div class="px-5 py-4 border-b border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <h3 class="font-display font-bold text-[18px] text-[#0F4C5C]">Notifications</h3>
                    <span class="bg-[#f2f8fa] text-[#0F4C5C] text-[10px] font-bold px-2 py-0.5 rounded border border-[#e8f4f7]">8 Unread</span>
                </div>
                <button class="text-[11px] font-bold text-slate-400 hover:text-[#0F4C5C] transition-colors">Mark all read</button>
            </div>

            <!-- Tabs -->
            <div class="flex items-center gap-4 text-[12px] font-semibold">
                <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'text-[#0F4C5C] border-b-2 border-[#0F4C5C] pb-2 -mb-[17px]' : 'text-slate-400 hover:text-slate-600 pb-2 -mb-[17px] border-b-2 border-transparent'">All</button>
                <button @click="activeTab = 'applications'" :class="activeTab === 'applications' ? 'text-[#0F4C5C] border-b-2 border-[#0F4C5C] pb-2 -mb-[17px]' : 'text-slate-400 hover:text-slate-600 pb-2 -mb-[17px] border-b-2 border-transparent'">Applications</button>
                <button @click="activeTab = 'documents'" :class="activeTab === 'documents' ? 'text-[#0F4C5C] border-b-2 border-[#0F4C5C] pb-2 -mb-[17px]' : 'text-slate-400 hover:text-slate-600 pb-2 -mb-[17px] border-b-2 border-transparent'">Documents</button>
                <button @click="activeTab = 'sms'" :class="activeTab === 'sms' ? 'text-[#0F4C5C] border-b-2 border-[#0F4C5C] pb-2 -mb-[17px]' : 'text-slate-400 hover:text-slate-600 pb-2 -mb-[17px] border-b-2 border-transparent'">SMS</button>
            </div>
        </div>

        <!-- List -->
        <div class="max-h-[360px] overflow-y-auto bg-slate-50">
            
            <!-- Item 1: Success -->
            <div x-show="activeTab === 'all' || activeTab === 'applications'" class="p-4 border-b border-slate-100 bg-white hover:bg-slate-50 transition-colors cursor-pointer relative">
                <div class="absolute w-1.5 h-1.5 bg-[#0F4C5C] rounded-full top-5 right-4"></div>
                <div class="flex items-start gap-3 pr-4">
                    <div class="w-8 h-8 rounded-full bg-[#e8f8ed] text-[#1a9653] flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-[#0F4C5C] mb-0.5">Application Approved!</p>
                        <p class="text-[12px] text-slate-500 leading-snug mb-1.5">Your application for Gabay Dunong Scholarship 2026 has been approved. Congratulations!</p>
                        <div class="flex items-center gap-2 text-[10px] text-slate-400 font-medium">
                            <span>2 hours ago</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span>Application</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 2: Info -->
            <div x-show="activeTab === 'all' || activeTab === 'applications'" class="p-4 border-b border-slate-100 bg-white hover:bg-slate-50 transition-colors cursor-pointer relative">
                <div class="absolute w-1.5 h-1.5 bg-[#0F4C5C] rounded-full top-5 right-4"></div>
                <div class="flex items-start gap-3 pr-4">
                    <div class="w-8 h-8 rounded-full bg-[#f2f8fa] text-[#0F4C5C] flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-[#0F4C5C] mb-0.5">Application Under Review</p>
                        <p class="text-[12px] text-slate-500 leading-snug mb-1.5">An evaluator started reviewing your Abot-Kaya Excellence Grant application.</p>
                        <div class="flex items-center gap-2 text-[10px] text-slate-400 font-medium">
                            <span>1 day ago</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span>Application</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 3: Warning -->
            <div x-show="activeTab === 'all' || activeTab === 'documents'" class="p-4 border-b border-slate-100 bg-white hover:bg-slate-50 transition-colors cursor-pointer relative">
                <div class="absolute w-1.5 h-1.5 bg-[#0F4C5C] rounded-full top-5 right-4"></div>
                <div class="flex items-start gap-3 pr-4">
                    <div class="w-8 h-8 rounded-full bg-[#FFFBEB] text-[#B07B10] flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-[#0F4C5C] mb-0.5">Document Expiring Soon</p>
                        <p class="text-[12px] text-slate-500 leading-snug mb-1.5">Your Certificate of Indigency expires in 14 days. Upload a new one to avoid issues.</p>
                        <div class="flex items-center gap-2 text-[10px] text-slate-400 font-medium">
                            <span>2 days ago</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span>Document</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 4: SMS -->
            <div x-show="activeTab === 'all' || activeTab === 'sms'" class="p-4 border-b border-slate-100 bg-white hover:bg-slate-50 transition-colors cursor-pointer relative">
                <div class="flex items-start gap-3 pr-4">
                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-[#0F4C5C] mb-0.5">SMS Sent</p>
                        <p class="text-[12px] text-slate-500 leading-snug mb-1.5">Interview schedule notification sent to +63 917 123 4567.</p>
                        <div class="flex items-center gap-2 text-[10px] text-slate-400 font-medium">
                            <span>3 days ago</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span>SMS</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div x-show="activeTab !== 'all' && activeTab !== 'applications' && activeTab !== 'documents' && activeTab !== 'sms'" class="p-8 text-center" style="display:none;">
                <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                <p class="text-[12px] text-slate-500">No notifications here</p>
            </div>
            
        </div>

        <div class="p-3 border-t border-slate-100 text-center bg-white">
            <a href="#" class="text-[12px] font-bold text-[#0F4C5C] hover:text-[#1a6878] transition-colors flex items-center justify-center gap-1">
                View all notifications
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </div>
</div>
