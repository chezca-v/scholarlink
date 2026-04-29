@props(['documentTitle' => 'Document Preview', 'uploadDate' => 'Today', 'fileSize' => '1.2 mb'])

<div x-show="showDocumentPreview" 
     style="display: none;"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
     x-transition.opacity>
    
    <div class="bg-white rounded-[20px] w-full max-w-[500px] p-8 shadow-2xl relative"
         @click.away="showDocumentPreview = false"
         x-show="showDocumentPreview"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <button @click="showDocumentPreview = false" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div class="mb-5">
            <h2 class="font-display font-bold text-xl text-[#0F4C5C] mb-1" x-text="documentTitle">{{ $documentTitle }}</h2>
            <p class="text-xs text-slate-500">
                Uploaded <span x-text="uploadDate">{{ $uploadDate }}</span> • PDF <span x-text="fileSize">{{ $fileSize }}</span>
            </p>
        </div>

        <div class="bg-[#f0f4f5] rounded-xl flex flex-col items-center justify-center py-16 mb-6 border border-[#e2e8e6]">
            <svg class="w-12 h-12 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <p class="text-[13px] font-semibold text-slate-500">PDF preview</p>
            <p class="text-[11px] text-slate-400 mt-1" x-text="documentTitle + '_Sanchez.pdf'">Grade_12_ReportCard_Sanchez.pdf</p>
        </div>

        <div class="flex items-center justify-end gap-3">
            <button type="button" @click="showDocumentPreview = false" 
                    class="px-6 py-2.5 rounded-xl text-[13px] font-semibold text-slate-600 hover:bg-slate-100 transition-colors border border-slate-200 bg-white">
                Reject
            </button>
            <button type="button" @click="showDocumentPreview = false" 
                    class="px-6 py-2.5 rounded-xl text-[13px] font-semibold text-white bg-[#0F4C5C] hover:bg-[#1a6878] shadow-md transition-colors border border-[#0F4C5C]">
                Approve Document
            </button>
        </div>
    </div>
</div>
