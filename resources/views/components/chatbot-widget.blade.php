{{--
|--------------------------------------------------------------------------
| chat-widget.blade.php
|--------------------------------------------------------------------------
| A fully self-contained Laravel Blade component for the Scholar Chat
| Assistant UI. Includes:
|   01 — Floating Action Button (FAB) with states
|   02 — Minimized Chat Pill
|   03 — Typing Indicator
|   04 — Quick Reply Chips
|
| Usage (in any Blade layout or view):
|   @include('components.chat-widget', [
|       'unreadCount' => 3,
|       'botName'     => 'Scholar',
|       'botAvatar'   => asset('images/scholar-avatar.png'),
|       'chips'       => [
|           ['icon' => '🔍', 'label' => 'Find scholarships for me'],
|           ['icon' => '📋', 'label' => 'Check my eligibility'],
|           ['icon' => '📄', 'label' => 'What documents do I need?'],
|           ['icon' => '📊', 'label' => 'Application status'],
|           ['icon' => '🛡️', 'label' => 'Upcoming deadlines'],
|           ['icon' => '💡', 'label' => 'Tips for applying'],
|       ],
|   ])
--}}

{{-- ─── Prop defaults ──────────────────────────────────────────────────────── --}}
@php
    $unreadCount = $unreadCount ?? 0;
    $botName     = $botName     ?? 'Scholar';
    $botAvatar   = $botAvatar   ?? null;          // null → initials fallback
    $chips       = $chips       ?? [
        ['icon' => '🔍', 'label' => 'Find scholarships for me'],
        ['icon' => '📋', 'label' => 'Check my eligibility'],
        ['icon' => '📄', 'label' => 'What documents do I need?'],
        ['icon' => '📊', 'label' => 'Application status'],
        ['icon' => '🛡️', 'label' => 'Upcoming deadlines'],
        ['icon' => '💡', 'label' => 'Tips for applying'],
    ];
    $isAmber     = $isAmber     ?? false;         // true → amber FAB variant
@endphp

{{-- ═══════════════════════════════════════════════════════════════════════════
     01 — FLOATING ACTION BUTTON (FAB)
     • Size      : 56 × 56 px
     • Position  : fixed; bottom: 28px; right: 28px; z-index: 300
     • Shape     : circle
     • Pulse ring: shown when $unreadCount > 0 OR on first load
     • Badge     : shows $unreadCount (hidden when 0)
     • Rotates to ✕ when chat is open
═══════════════════════════════════════════════════════════════════════════ --}}
<div id="chat-widget-root"
     data-unread="{{ $unreadCount }}"
     data-amber="{{ $isAmber ? 'true' : 'false' }}">

    {{-- FAB Button --}}
    <button
        id="chat-fab"
        class="chat-fab {{ $isAmber ? 'chat-fab--amber' : '' }} {{ $unreadCount > 0 ? 'chat-fab--pulse' : '' }}"
        aria-label="Open chat"
        onclick="ChatWidget.toggleChat()"
    >
        {{-- Default icon: chat bubble (SVG) --}}
        <span class="chat-fab__icon chat-fab__icon--chat" aria-hidden="true">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M20 2H4C2.9 2 2 2.9 2 4V22L6 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2Z"
                      fill="currentColor"/>
            </svg>
        </span>

        {{-- Close icon: shown when chat is open --}}
        <span class="chat-fab__icon chat-fab__icon--close" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <line x1="3" y1="3" x2="17" y2="17" stroke="currentColor" stroke-width="2.5"
                      stroke-linecap="round"/>
                <line x1="17" y1="3" x2="3" y2="17" stroke="currentColor" stroke-width="2.5"
                      stroke-linecap="round"/>
            </svg>
        </span>

        {{-- Unread badge --}}
        @if ($unreadCount > 0)
            <span class="chat-fab__badge" id="chat-fab-badge">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif

        {{-- Pulse ring (rendered in CSS via ::before / ::after) --}}
    </button>

    {{-- ═══════════════════════════════════════════════════════════════════════
         02 — MINIMIZED CHAT PILL
         • Appears above FAB (bottom: 28px + 56px + 12px gap = ~96px; right: 28px)
         • Snaps to collapsed pill when user minimizes
         • Click anywhere on pill to expand back
    ═══════════════════════════════════════════════════════════════════════════ --}}
    <div id="chat-pill"
         class="chat-pill"
         role="button"
         tabindex="0"
         aria-label="Expand chat"
         onclick="ChatWidget.expandChat()"
         onkeydown="if(event.key==='Enter'||event.key===' ')ChatWidget.expandChat()">

        {{-- Bot avatar --}}
        <div class="chat-pill__avatar">
            @if ($botAvatar)
                <img src="{{ $botAvatar }}" alt="{{ $botName }} avatar" />
            @else
                <span class="chat-pill__avatar-initials">
                    {{ strtoupper(substr($botName, 0, 1)) }}
                </span>
            @endif
        </div>

        {{-- Bot info --}}
        <div class="chat-pill__info">
            <span class="chat-pill__name">{{ $botName }}</span>
            <span class="chat-pill__subtitle">Ask me anything…</span>
        </div>

        {{-- Expand arrow (▲) --}}
        <div class="chat-pill__arrow" aria-hidden="true">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M1 9L6 3L11 9" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════
         CHAT WINDOW  (main container — houses messages + chips + input)
    ═══════════════════════════════════════════════════════════════════════════ --}}
    <div id="chat-window" class="chat-window" role="dialog" aria-label="{{ $botName }} chat">

        {{-- Chat header --}}
        <div class="chat-window__header">
            <div class="chat-window__header-left">
                <div class="chat-pill__avatar chat-pill__avatar--sm">
                    @if ($botAvatar)
                        <img src="{{ $botAvatar }}" alt="{{ $botName }}" />
                    @else
                        <span class="chat-pill__avatar-initials">
                            {{ strtoupper(substr($botName, 0, 1)) }}
                        </span>
                    @endif
                </div>
                <div>
                    <p class="chat-window__bot-name">{{ $botName }}</p>
                    <p class="chat-window__bot-status">Online</p>
                </div>
            </div>
            <div class="chat-window__header-actions">
                {{-- Minimize button --}}
                <button class="chat-icon-btn" aria-label="Minimize chat"
                        onclick="ChatWidget.minimizeChat()">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <line x1="3" y1="9" x2="15" y2="9" stroke="currentColor"
                              stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                {{-- Close button --}}
                <button class="chat-icon-btn" aria-label="Close chat"
                        onclick="ChatWidget.closeChat()">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <line x1="3" y1="3" x2="15" y2="15" stroke="currentColor"
                              stroke-width="2" stroke-linecap="round"/>
                        <line x1="15" y1="3" x2="3" y2="15" stroke="currentColor"
                              stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Messages area --}}
        <div id="chat-messages" class="chat-window__messages" aria-live="polite">

            {{-- Welcome message (static example — replace with @foreach over messages) --}}
            <div class="chat-msg chat-msg--bot">
                <div class="chat-msg__avatar">
                    @if ($botAvatar)
                        <img src="{{ $botAvatar }}" alt="{{ $botName }}" />
                    @else
                        <span class="chat-pill__avatar-initials">
                            {{ strtoupper(substr($botName, 0, 1)) }}
                        </span>
                    @endif
                </div>
                <div class="chat-msg__bubble">
                    Hi! I'm <strong>{{ $botName }}</strong>. How can I help you today?
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════════
                 03 — TYPING INDICATOR
                 • 3-dot animated bounce
                 • Shown while waiting for bot API response
                 • Hidden by default; toggled via ChatWidget.showTyping() / hideTyping()
            ═══════════════════════════════════════════════════════════════════ --}}
            <div id="chat-typing" class="chat-typing" aria-label="Scholar is typing" hidden>
                <div class="chat-msg__avatar">
                    @if ($botAvatar)
                        <img src="{{ $botAvatar }}" alt="{{ $botName }}" />
                    @else
                        <span class="chat-pill__avatar-initials">
                            {{ strtoupper(substr($botName, 0, 1)) }}
                        </span>
                    @endif
                </div>
                <div class="chat-typing__bubble">
                    <span class="chat-typing__dot"></span>
                    <span class="chat-typing__dot"></span>
                    <span class="chat-typing__dot"></span>
                </div>
            </div>

        </div>{{-- /chat-messages --}}

        {{-- ═══════════════════════════════════════════════════════════════════
             04 — QUICK REPLY CHIPS
             • Shown after welcome message and certain bot responses
             • Clicking a chip sends it as a user message automatically
             • Driven by $chips prop via @foreach
        ═══════════════════════════════════════════════════════════════════════ --}}
        <div id="chat-chips" class="chat-chips" role="group" aria-label="Quick reply suggestions">
            @foreach ($chips as $chip)
                <button
                    class="chat-chip"
                    type="button"
                    onclick="ChatWidget.sendChip({{ json_encode($chip['label']) }})"
                    aria-label="{{ $chip['label'] }}"
                >
                    @if (!empty($chip['icon']))
                        <span class="chat-chip__icon" aria-hidden="true">{{ $chip['icon'] }}</span>
                    @endif
                    <span class="chat-chip__label">{{ $chip['label'] }}</span>
                </button>
            @endforeach
        </div>

        {{-- Input area --}}
        <div class="chat-window__input-area">
            <input
                id="chat-input"
                type="text"
                class="chat-window__input"
                placeholder="Type a message…"
                aria-label="Type your message"
                onkeydown="if(event.key==='Enter')ChatWidget.sendMessage()"
            />
            <button class="chat-window__send-btn"
                    aria-label="Send message"
                    onclick="ChatWidget.sendMessage()">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M2 10L18 2L10 18L9 11L2 10Z" fill="currentColor"/>
                </svg>
            </button>
        </div>
    </div>{{-- /chat-window --}}
</div>{{-- /chat-widget-root --}}


{{-- ═══════════════════════════════════════════════════════════════════════════
     STYLES — scoped with .chat-* namespace to avoid collisions
═══════════════════════════════════════════════════════════════════════════ --}}
@push('styles')
<style>
/* ── CSS Custom Properties ────────────────────────────────────────────── */
:root {
    --cw-teal:        #0d3d3a;   /* deep teal (FAB default BG) */
    --cw-teal-mid:    #0e5c56;   /* mid teal */
    --cw-teal-light:  #1a7a72;
    --cw-amber:       #d4a017;   /* amber FAB variant */
    --cw-amber-light: #f0c040;
    --cw-white:       #ffffff;
    --cw-surface:     #f5f7f6;   /* chat window bg */
    --cw-border:      #e2e8e7;
    --cw-text-dark:   #1a2e2c;
    --cw-text-mid:    #4a6460;
    --cw-text-light:  #7a9a96;
    --cw-chip-bg:     #eef3f2;
    --cw-chip-border: #cddedd;
    --cw-shadow:      0 8px 32px rgba(0,0,0,.18), 0 2px 8px rgba(0,0,0,.10);
    --cw-radius-pill: 9999px;
    --cw-radius-lg:   16px;
    --cw-radius-md:   10px;
    --cw-fab-size:    56px;      /* spec: 56×56px */
    --cw-fab-bottom:  28px;      /* spec: bottom 28px */
    --cw-fab-right:   28px;      /* spec: right 28px */
    --cw-z:           300;       /* spec: z-index 300 */
    --cw-transition:  .28s cubic-bezier(.4,0,.2,1);
}

/* ── Reset / base ─────────────────────────────────────────────────────── */
#chat-widget-root *, #chat-widget-root *::before, #chat-widget-root *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

/* ── 01  FLOATING ACTION BUTTON ───────────────────────────────────────── */
.chat-fab {
    /* Size & position — pixel-perfect from spec */
    position:      fixed;
    bottom:        var(--cw-fab-bottom);
    right:         var(--cw-fab-right);
    z-index:       var(--cw-z);
    width:         var(--cw-fab-size);
    height:        var(--cw-fab-size);

    /* Shape & appearance */
    border-radius: var(--cw-radius-pill);
    border:        none;
    cursor:        pointer;
    background:    var(--cw-teal);
    color:         var(--cw-white);
    box-shadow:    var(--cw-shadow);

    /* Icon layout */
    display:        flex;
    align-items:    center;
    justify-content:center;
    overflow:       visible;

    transition: background var(--cw-transition),
                transform  var(--cw-transition),
                box-shadow var(--cw-transition);
}

.chat-fab:hover  { background: var(--cw-teal-mid); transform: scale(1.07); }
.chat-fab:active { transform:  scale(.96); }

/* Amber variant */
.chat-fab--amber { background: var(--cw-amber); color: var(--cw-text-dark); }
.chat-fab--amber:hover { background: var(--cw-amber-light); }

/* Icon states */
.chat-fab__icon          { position: absolute; transition: opacity var(--cw-transition),
                            transform var(--cw-transition); }
.chat-fab__icon--close   { opacity: 0; transform: rotate(-90deg) scale(.6); }
.chat-fab__icon--chat    { opacity: 1; transform: rotate(0) scale(1); }

/* When open — swap icons */
.chat-fab.is-open .chat-fab__icon--chat  { opacity: 0; transform: rotate(90deg) scale(.6); }
.chat-fab.is-open .chat-fab__icon--close { opacity: 1; transform: rotate(0) scale(1); }

/* Pulse ring — shown when unread or on first load */
.chat-fab--pulse::before,
.chat-fab--pulse::after {
    content:       '';
    position:      absolute;
    inset:         -6px;
    border-radius: var(--cw-radius-pill);
    border:        2.5px solid var(--cw-teal-light);
    opacity:       0;
    animation:     fab-pulse 2.4s ease-out infinite;
}
.chat-fab--pulse::after { animation-delay: .8s; }

@keyframes fab-pulse {
    0%   { transform: scale(1);    opacity: .7; }
    80%  { transform: scale(1.55); opacity: 0;  }
    100% { transform: scale(1.55); opacity: 0;  }
}

/* Unread badge */
.chat-fab__badge {
    position:      absolute;
    top:           -4px;
    right:         -4px;
    min-width:     20px;
    height:        20px;
    padding:       0 5px;
    border-radius: var(--cw-radius-pill);
    background:    #e74c3c;
    color:         var(--cw-white);
    font-size:     11px;
    font-weight:   700;
    line-height:   20px;
    text-align:    center;
    border:        2px solid var(--cw-white);
    pointer-events:none;
    transition:    opacity var(--cw-transition), transform var(--cw-transition);
}
/* Hide badge when chat is open */
.chat-fab.is-open .chat-fab__badge { opacity: 0; transform: scale(0); }

/* ── 02  MINIMIZED PILL ───────────────────────────────────────────────── */
.chat-pill {
    /* Spec: appears above FAB; bottom: 28px + 56px + 12px = 96px; right: 28px */
    position:      fixed;
    bottom:        calc(var(--cw-fab-bottom) + var(--cw-fab-size) + 12px);
    right:         var(--cw-fab-right);
    z-index:       calc(var(--cw-z) - 1);

    display:       flex;
    align-items:   center;
    gap:           10px;
    padding:       10px 14px 10px 10px;

    background:    var(--cw-white);
    border-radius: var(--cw-radius-pill);
    box-shadow:    var(--cw-shadow);
    cursor:        pointer;
    user-select:   none;
    white-space:   nowrap;

    /* Hidden by default; shown when minimized */
    opacity:       0;
    transform:     translateY(8px) scale(.95);
    pointer-events:none;
    transition:    opacity var(--cw-transition), transform var(--cw-transition);
}

.chat-pill.is-visible {
    opacity:        1;
    transform:      translateY(0) scale(1);
    pointer-events: auto;
}
.chat-pill:hover { box-shadow: 0 12px 36px rgba(0,0,0,.22); transform: translateY(-1px); }

/* Pill avatar */
.chat-pill__avatar {
    width:          36px;
    height:         36px;
    border-radius:  var(--cw-radius-pill);
    background:     var(--cw-teal);
    overflow:       hidden;
    flex-shrink:    0;
    display:        flex;
    align-items:    center;
    justify-content:center;
}
.chat-pill__avatar img { width: 100%; height: 100%; object-fit: cover; }
.chat-pill__avatar--sm { width: 32px; height: 32px; }

.chat-pill__avatar-initials {
    color:       var(--cw-white);
    font-weight: 700;
    font-size:   15px;
}
.chat-pill__info { display: flex; flex-direction: column; gap: 1px; }
.chat-pill__name { font-weight: 700; font-size: 14px; color: var(--cw-text-dark); }
.chat-pill__subtitle { font-size: 12px; color: var(--cw-text-light); }

.chat-pill__arrow {
    color:      var(--cw-teal-mid);
    flex-shrink:0;
    margin-left:4px;
}

/* ── CHAT WINDOW ──────────────────────────────────────────────────────── */
.chat-window {
    position:      fixed;
    bottom:        calc(var(--cw-fab-bottom) + var(--cw-fab-size) + 12px);
    right:         var(--cw-fab-right);
    z-index:       calc(var(--cw-z) - 1);

    width:         360px;
    max-height:    540px;
    border-radius: var(--cw-radius-lg);
    background:    var(--cw-surface);
    box-shadow:    var(--cw-shadow);
    display:       flex;
    flex-direction:column;
    overflow:      hidden;

    /* Hidden by default */
    opacity:        0;
    transform:      translateY(12px) scale(.96);
    pointer-events: none;
    transition:     opacity var(--cw-transition), transform var(--cw-transition);
    transform-origin: bottom right;
}

.chat-window.is-open {
    opacity:        1;
    transform:      translateY(0) scale(1);
    pointer-events: auto;
}

/* Header */
.chat-window__header {
    display:        flex;
    align-items:    center;
    justify-content:space-between;
    padding:        14px 16px;
    background:     var(--cw-teal);
    color:          var(--cw-white);
    flex-shrink:    0;
}
.chat-window__header-left  { display: flex; align-items: center; gap: 10px; }
.chat-window__header-actions { display: flex; gap: 4px; }
.chat-window__bot-name   { font-weight: 700; font-size: 15px; }
.chat-window__bot-status { font-size: 12px; opacity: .75; }

.chat-icon-btn {
    width:          32px;
    height:         32px;
    border-radius:  var(--cw-radius-pill);
    border:         none;
    background:     transparent;
    color:          var(--cw-white);
    cursor:         pointer;
    display:        flex;
    align-items:    center;
    justify-content:center;
    transition:     background var(--cw-transition);
}
.chat-icon-btn:hover { background: rgba(255,255,255,.15); }

/* Messages */
.chat-window__messages {
    flex:       1;
    overflow-y: auto;
    padding:    16px;
    display:    flex;
    flex-direction: column;
    gap:        12px;
    scrollbar-width: thin;
    scrollbar-color: var(--cw-border) transparent;
}

.chat-msg { display: flex; align-items: flex-end; gap: 8px; }
.chat-msg--user { flex-direction: row-reverse; }

.chat-msg__avatar {
    width:          28px;
    height:         28px;
    border-radius:  var(--cw-radius-pill);
    background:     var(--cw-teal);
    overflow:       hidden;
    flex-shrink:    0;
    display:        flex;
    align-items:    center;
    justify-content:center;
    font-size:      12px;
}
.chat-msg__avatar img { width: 100%; height: 100%; object-fit: cover; }

.chat-msg__bubble {
    max-width:    75%;
    padding:      10px 14px;
    border-radius:16px 16px 16px 4px;
    background:   var(--cw-white);
    color:        var(--cw-text-dark);
    font-size:    14px;
    line-height:  1.5;
    box-shadow:   0 1px 3px rgba(0,0,0,.08);
    border:       1px solid var(--cw-border);
}
.chat-msg--user .chat-msg__bubble {
    background:    var(--cw-teal);
    color:         var(--cw-white);
    border-color:  transparent;
    border-radius: 16px 16px 4px 16px;
}

/* ── 03  TYPING INDICATOR ─────────────────────────────────────────────── */
.chat-typing {
    display:    flex;
    align-items:flex-end;
    gap:        8px;
}
/* hidden attribute handled natively; JS sets/removes it */

.chat-typing__bubble {
    display:       flex;
    align-items:   center;
    gap:           5px;
    padding:       12px 16px;
    background:    var(--cw-white);
    border:        1px solid var(--cw-border);
    border-radius: 16px 16px 16px 4px;
    box-shadow:    0 1px 3px rgba(0,0,0,.08);
}

.chat-typing__dot {
    width:         8px;
    height:        8px;
    border-radius: var(--cw-radius-pill);
    background:    var(--cw-teal-light);
    animation:     typing-bounce .9s ease-in-out infinite;
}
.chat-typing__dot:nth-child(2) { animation-delay: .18s; }
.chat-typing__dot:nth-child(3) { animation-delay: .36s; }

@keyframes typing-bounce {
    0%, 80%, 100% { transform: translateY(0);    opacity: .5; }
    40%           { transform: translateY(-6px); opacity: 1;  }
}

/* ── 04  QUICK REPLY CHIPS ────────────────────────────────────────────── */
.chat-chips {
    display:       flex;
    flex-wrap:     wrap;
    gap:           6px;
    padding:       8px 16px 4px;
    flex-shrink:   0;
    border-top:    1px solid var(--cw-border);
}
/* Hide chips after user sends a message — toggled via JS */
.chat-chips.is-hidden { display: none; }

.chat-chip {
    display:       inline-flex;
    align-items:   center;
    gap:           5px;
    padding:       6px 12px;
    border-radius: var(--cw-radius-pill);
    border:        1.5px solid var(--cw-chip-border);
    background:    var(--cw-chip-bg);
    color:         var(--cw-text-dark);
    font-size:     12.5px;
    font-weight:   500;
    cursor:        pointer;
    white-space:   nowrap;
    transition:    background var(--cw-transition), border-color var(--cw-transition),
                   transform .15s ease, box-shadow .15s ease;
}
.chat-chip:hover {
    background:    var(--cw-teal-light);
    color:         var(--cw-white);
    border-color:  var(--cw-teal-light);
    transform:     translateY(-1px);
    box-shadow:    0 3px 10px rgba(0,0,0,.12);
}
.chat-chip:active { transform: translateY(0); }
.chat-chip__icon  { font-size: 14px; }

/* Input area */
.chat-window__input-area {
    display:      flex;
    align-items:  center;
    gap:          8px;
    padding:      10px 14px;
    background:   var(--cw-white);
    border-top:   1px solid var(--cw-border);
    flex-shrink:  0;
}
.chat-window__input {
    flex:          1;
    padding:       9px 14px;
    border:        1.5px solid var(--cw-border);
    border-radius: var(--cw-radius-pill);
    font-size:     14px;
    color:         var(--cw-text-dark);
    background:    var(--cw-surface);
    outline:       none;
    transition:    border-color var(--cw-transition);
}
.chat-window__input:focus { border-color: var(--cw-teal-mid); }

.chat-window__send-btn {
    width:          38px;
    height:         38px;
    border-radius:  var(--cw-radius-pill);
    border:         none;
    background:     var(--cw-teal);
    color:          var(--cw-white);
    display:        flex;
    align-items:    center;
    justify-content:center;
    cursor:         pointer;
    flex-shrink:    0;
    transition:     background var(--cw-transition), transform .15s ease;
}
.chat-window__send-btn:hover  { background: var(--cw-teal-mid); transform: scale(1.08); }
.chat-window__send-btn:active { transform: scale(.95); }

/* ── Responsive ───────────────────────────────────────────────────────── */
@media (max-width: 440px) {
    .chat-window {
        width:         calc(100vw - 2 * var(--cw-fab-right));
        right:         var(--cw-fab-right);
        left:          var(--cw-fab-right);
        max-height:    70vh;
    }
}
</style>
@endpush


{{-- ═══════════════════════════════════════════════════════════════════════════
     JAVASCRIPT — ChatWidget namespace
═══════════════════════════════════════════════════════════════════════════ --}}
@push('scripts')
<script>
(function () {
    'use strict';

    // ── State ────────────────────────────────────────────────────────────
    const state = {
        isOpen:      false,
        isMinimized: false,
        unread:      parseInt(document.getElementById('chat-widget-root')
                        ?.dataset.unread || '0', 10),
    };

    // ── DOM refs ─────────────────────────────────────────────────────────
    const fab       = () => document.getElementById('chat-fab');
    const pill      = () => document.getElementById('chat-pill');
    const win       = () => document.getElementById('chat-window');
    const messages  = () => document.getElementById('chat-messages');
    const typing    = () => document.getElementById('chat-typing');
    const chips     = () => document.getElementById('chat-chips');
    const input     = () => document.getElementById('chat-input');
    const badge     = () => document.getElementById('chat-fab-badge');

    // ── Helpers ──────────────────────────────────────────────────────────
    function scrollToBottom() {
        const el = messages();
        if (el) el.scrollTop = el.scrollHeight;
    }

    function appendMessage(text, role /* 'user' | 'bot' */) {
        const el = messages();
        if (!el) return;

        // Remove typing indicator from DOM flow temporarily so we insert before it
        const typingEl = typing();

        const msg = document.createElement('div');
        msg.className = 'chat-msg chat-msg--' + role;
        msg.style.opacity = '0';
        msg.style.transform = 'translateY(6px)';
        msg.style.transition = 'opacity .25s ease, transform .25s ease';

        if (role === 'bot') {
            msg.innerHTML = `
                <div class="chat-msg__avatar">
                    <span class="chat-pill__avatar-initials">S</span>
                </div>
                <div class="chat-msg__bubble">${escapeHtml(text)}</div>`;
        } else {
            msg.innerHTML = `<div class="chat-msg__bubble">${escapeHtml(text)}</div>`;
        }

        // Insert before typing indicator
        el.insertBefore(msg, typingEl);

        // Animate in
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                msg.style.opacity  = '1';
                msg.style.transform = 'translateY(0)';
            });
        });

        scrollToBottom();
    }

    function escapeHtml(str) {
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;')
                  .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function clearUnread() {
        state.unread = 0;
        const b = badge();
        if (b) b.remove();
        fab()?.classList.remove('chat-fab--pulse');
    }

    // ── Public API ───────────────────────────────────────────────────────
    window.ChatWidget = {

        /**
         * toggleChat — open if closed; close if open
         */
        toggleChat() {
            state.isOpen ? this.closeChat() : this.openChat();
        },

        /**
         * openChat — show chat window; hide pill
         */
        openChat() {
            state.isOpen      = true;
            state.isMinimized = false;

            fab()?.classList.add('is-open');
            win()?.classList.add('is-open');
            pill()?.classList.remove('is-visible');

            clearUnread();
            scrollToBottom();

            // Focus input
            setTimeout(() => input()?.focus(), 300);
        },

        /**
         * closeChat — hide chat window and pill; restore FAB to default
         */
        closeChat() {
            state.isOpen      = false;
            state.isMinimized = false;

            fab()?.classList.remove('is-open');
            win()?.classList.remove('is-open');
            pill()?.classList.remove('is-visible');
        },

        /**
         * minimizeChat — collapse to pill, hide window, keep FAB visible
         */
        minimizeChat() {
            state.isOpen      = false;
            state.isMinimized = true;

            fab()?.classList.remove('is-open');
            win()?.classList.remove('is-open');

            // Spec: pill appears above FAB at same right alignment
            pill()?.classList.add('is-visible');
        },

        /**
         * expandChat — restore from minimized pill back to full chat window
         */
        expandChat() {
            pill()?.classList.remove('is-visible');
            this.openChat();
        },

        /**
         * sendChip — called when a quick reply chip is clicked
         * @param {string} label
         */
        sendChip(label) {
            // Hide chips after first interaction (Spec: shown after welcome + certain responses)
            chips()?.classList.add('is-hidden');

            // Show as user message
            appendMessage(label, 'user');

            // Simulate bot response
            this._simulateBotResponse();
        },

        /**
         * sendMessage — reads input and sends
         */
        sendMessage() {
            const inp  = input();
            const text = inp?.value.trim();
            if (!text) return;

            chips()?.classList.add('is-hidden');
            appendMessage(text, 'user');
            inp.value = '';

            this._simulateBotResponse();
        },

        /**
         * showTyping — display the 3-dot typing indicator
         */
        showTyping() {
            const t = typing();
            if (t) {
                t.removeAttribute('hidden');
                scrollToBottom();
            }
        },

        /**
         * hideTyping — remove the typing indicator
         */
        hideTyping() {
            typing()?.setAttribute('hidden', '');
        },

        /**
         * _simulateBotResponse — internal demo helper.
         * Replace this with a real fetch() / Axios call to your API endpoint.
         *
         * Example real usage:
         *   ChatWidget.showTyping();
         *   fetch('/api/chat', { method:'POST', body: JSON.stringify({message: text}) })
         *     .then(r => r.json())
         *     .then(d => { ChatWidget.hideTyping(); appendMessage(d.reply, 'bot'); });
         */
        _simulateBotResponse() {
            this.showTyping();
            setTimeout(() => {
                this.hideTyping();
                appendMessage('I\'m looking into that for you…', 'bot');
                // Optionally re-show chips after bot responds
                // chips()?.classList.remove('is-hidden');
            }, 1800);
        },
    };

    // ── Init — show pulse on first load if no unread ─────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        if (state.unread === 0) {
            // Brief pulse on first load per spec
            fab()?.classList.add('chat-fab--pulse');
            setTimeout(() => fab()?.classList.remove('chat-fab--pulse'), 6000);
        }
    });

})();
</script>
@endpush