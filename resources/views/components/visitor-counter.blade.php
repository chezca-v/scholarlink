@php
    $initialCount = 0;
@endphp

<div id="booth-counter" class="visitor-widget shadow-xl border border-teal-100 bg-white/90 backdrop-blur-xl rounded-2xl fixed z-[9990] transition-all duration-500 ease-out opacity-0 translate-y-20">
    <div id="bc-handle" class="bc-drag-handle cursor-grab active:cursor-grabbing flex items-center justify-center h-6 w-full opacity-30 hover:opacity-100 transition-opacity">
        <div class="w-10 h-1 bg-slate-300 rounded-full"></div>
    </div>
    
    <div class="p-4 pt-1 flex flex-row items-center gap-4">
        <div class="flex-shrink-0 w-12 h-12 bg-teal-500 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-teal-500/20" id="bc-num-container">
            <span id="bc-num">0</span>
        </div>
        <div class="flex-1 min-w-0">
            <h4 class="text-[13px] font-bold text-teal-900 leading-tight">Daily Visitors</h4>
            <p class="text-[11px] text-teal-600 truncate" id="bc-sub">No visitors yet</p>
        </div>
        <div class="flex flex-col gap-1">
            <button id="bc-log" class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center hover:bg-amber-200 transition-colors" title="Log Visitor">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            </button>
            <button id="bc-toggle" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-slate-200 transition-colors" title="Collapse">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
            </button>
        </div>
    </div>
    
    <div id="bc-reset-container" class="px-4 pb-3 flex justify-end">
        <button id="bc-reset" class="text-[9px] uppercase tracking-widest font-bold text-slate-300 hover:text-red-400 transition-colors">Reset Counter</button>
    </div>
</div>

<div id="bc-toast" class="fixed bottom-32 right-8 z-[10000] bg-teal-900 text-white px-4 py-2 rounded-xl text-sm font-medium shadow-2xl translate-y-10 opacity-0 pointer-events-none transition-all duration-300">
    Visitor logged!
</div>

<style>
    .visitor-widget {
        width: 240px;
        right: 28px;
        bottom: 28px;
    }
    .visitor-widget.visible {
        opacity: 1;
        transform: translateY(0);
    }
    .visitor-widget.collapsed {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        padding: 0;
        overflow: hidden;
    }
    .visitor-widget.collapsed #bc-handle,
    .visitor-widget.collapsed .flex-1,
    .visitor-widget.collapsed .flex-col,
    .visitor-widget.collapsed #bc-reset-container {
        display: none;
    }
    .visitor-widget.collapsed #bc-num-container {
        width: 100%;
        height: 100%;
        border-radius: 0;
        box-shadow: none;
    }
    #bc-num.bump {
        animation: bc-bump 0.4s cubic-bezier(.17,.67,.35,1.3);
    }
    @keyframes bc-bump {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.4); }
    }
    #bc-toast.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const STORAGE_KEY = 'sl_booth_visitors';
    const STORAGE_DATE_KEY = 'sl_booth_date';
    
    const widget = document.getElementById('booth-counter');
    const handle = document.getElementById('bc-handle');
    const numEl = document.getElementById('bc-num');
    const subEl = document.getElementById('bc-sub');
    const logBtn = document.getElementById('bc-log');
    const toggleBtn = document.getElementById('bc-toggle');
    const resetBtn = document.getElementById('bc-reset');
    const toast = document.getElementById('bc-toast');

    // Dragging state
    let isDragging = false;
    let startX, startY, initialLeft, initialTop;

    // Daily reset check
    const today = new Date().toDateString();
    if (localStorage.getItem(STORAGE_DATE_KEY) !== today) {
        localStorage.setItem(STORAGE_KEY, '0');
        localStorage.setItem(STORAGE_DATE_KEY, today);
    }

    let visitorCount = parseInt(localStorage.getItem(STORAGE_KEY) || '0', 10);

    function updateDisplay() {
        numEl.textContent = visitorCount;
        subEl.textContent = visitorCount === 0 ? 'No visitors yet' : 
                          visitorCount === 1 ? '1 visitor today' : 
                          visitorCount + ' visitors today';
        localStorage.setItem(STORAGE_KEY, visitorCount);
    }

    function logVisitor() {
        visitorCount++;
        updateDisplay();
        numEl.classList.add('bump');
        setTimeout(() => numEl.classList.remove('bump'), 400);
        toast.textContent = '👋 Visitor logged! #' + visitorCount;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2500);
    }

    // Handlers
    logBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        logVisitor();
    });

    toggleBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        widget.classList.add('collapsed');
    });

    widget.addEventListener('click', () => {
        if (widget.classList.contains('collapsed')) {
            widget.classList.remove('collapsed');
        }
    });

    resetBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        if (confirm('Reset visitor count?')) {
            visitorCount = 0;
            updateDisplay();
        }
    });

    // Dragging
    handle.addEventListener('mousedown', (e) => {
        if (widget.classList.contains('collapsed')) return;
        isDragging = true;
        startX = e.clientX;
        startY = e.clientY;
        const rect = widget.getBoundingClientRect();
        initialLeft = rect.left;
        initialTop = rect.top;
        widget.style.transition = 'none';
        handle.style.cursor = 'grabbing';
    });

    window.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        const dx = e.clientX - startX;
        const dy = e.clientY - startY;
        widget.style.left = (initialLeft + dx) + 'px';
        widget.style.top = (initialTop + dy) + 'px';
        widget.style.bottom = 'auto';
        widget.style.right = 'auto';
    });

    window.addEventListener('mouseup', () => {
        isDragging = false;
        widget.style.transition = '';
        handle.style.cursor = 'grab';
    });

    // Initialize
    updateDisplay();
    setTimeout(() => widget.classList.add('visible'), 1000);
});
</script>
