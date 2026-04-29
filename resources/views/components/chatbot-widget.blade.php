@php
    $unreadCount = $unreadCount ?? 0;
    $botName = $botName ?? 'Scholar AI';
    $botAvatar = $botAvatar ?? null;
    $chips = $chips ?? [
        ['icon' => '📅', 'label' => 'Show deadlines'],
        ['icon' => '📊', 'label' => 'My app status'],
        ['icon' => '📤', 'label' => 'Upload doc'],
        ['icon' => '🔍', 'label' => 'More matches'],
    ];
    $isAmber = $isAmber ?? false;
@endphp

<div id="chat-widget-root"
     data-unread="{{ $unreadCount }}"
     data-amber="{{ $isAmber ? 'true' : 'false' }}"
     data-bot-initial="{{ strtoupper(substr($botName, 0, 1)) }}"
     data-chat-url="{{ route('ai.chat') }}">

    <button
        id="chat-fab"
        class="chat-fab {{ $isAmber ? 'chat-fab--amber' : '' }} {{ $unreadCount > 0 ? 'chat-fab--pulse' : '' }}"
        type="button"
        aria-label="Open chat"
        onclick="ChatWidget.toggleChat()"
    >
        <span class="chat-fab__icon chat-fab__icon--chat" aria-hidden="true">
            <svg class="chat-icon-svg" viewBox="0 0 24 24" fill="none">
                <path d="M20 3.5H4C2.9 3.5 2 4.4 2 5.5V21L6.4 16.8H20C21.1 16.8 22 15.9 22 14.8V5.5C22 4.4 21.1 3.5 20 3.5Z" fill="currentColor"/>
            </svg>
        </span>
        <span class="chat-fab__icon chat-fab__icon--close" aria-hidden="true">✕</span>

        @if ($unreadCount > 0)
            <span class="chat-fab__badge" id="chat-fab-badge">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div id="chat-pill"
         class="chat-pill"
         role="button"
         tabindex="0"
         aria-label="Expand chat"
         onclick="ChatWidget.expandChat()"
         onkeydown="if(event.key==='Enter'||event.key===' ')ChatWidget.expandChat()">
        <div class="chat-avatar chat-avatar--bot">
            @if ($botAvatar)
                <img src="{{ $botAvatar }}" alt="{{ $botName }} avatar">
            @else
                <span aria-hidden="true" class="chat-message-icon">
                    <svg class="chat-icon-svg" viewBox="0 0 24 24" fill="none">
                        <path d="M20 3.5H4C2.9 3.5 2 4.4 2 5.5V21L6.4 16.8H20C21.1 16.8 22 15.9 22 14.8V5.5C22 4.4 21.1 3.5 20 3.5Z" fill="currentColor"/>
                    </svg>
                </span>
            @endif
        </div>
        <div class="chat-pill__info">
            <span class="chat-pill__name">{{ $botName }}</span>
            <span class="chat-pill__subtitle">Online · Powered by Gemini AI</span>
        </div>
    </div>

    <section id="chat-window" class="chat-window" role="dialog" aria-label="{{ $botName }} chat">
        <header class="chat-window__header">
            <div class="chat-window__identity">
                <div class="chat-avatar chat-avatar--header">
                    @if ($botAvatar)
                        <img src="{{ $botAvatar }}" alt="{{ $botName }} avatar">
                    @else
                        <span aria-hidden="true" class="chat-message-icon">
                            <svg class="chat-icon-svg" viewBox="0 0 24 24" fill="none">
                                <path d="M20 3.5H4C2.9 3.5 2 4.4 2 5.5V21L6.4 16.8H20C21.1 16.8 22 15.9 22 14.8V5.5C22 4.4 21.1 3.5 20 3.5Z" fill="currentColor"/>
                            </svg>
                        </span>
                    @endif
                </div>
                <div>
                    <p class="chat-window__bot-name">{{ $botName }}</p>
                    <p class="chat-window__bot-status"><span></span>Online · Powered by Gemini AI</p>
                </div>
            </div>
            <div class="chat-window__header-actions">
                <button class="chat-icon-btn" type="button" aria-label="Minimize chat" onclick="ChatWidget.minimizeChat()">—</button>
                <button class="chat-icon-btn" type="button" aria-label="Close chat" onclick="ChatWidget.closeChat()">✕</button>
            </div>
        </header>

        <div id="chat-messages" class="chat-window__messages" aria-live="polite">
            <div class="chat-msg chat-msg--bot">
                <div class="chat-avatar chat-avatar--msg">
                    @if ($botAvatar)
                        <img src="{{ $botAvatar }}" alt="{{ $botName }} avatar">
                    @else
                        <span aria-hidden="true" class="chat-message-icon">
                            <svg class="chat-icon-svg" viewBox="0 0 24 24" fill="none">
                                <path d="M20 3.5H4C2.9 3.5 2 4.4 2 5.5V21L6.4 16.8H20C21.1 16.8 22 15.9 22 14.8V5.5C22 4.4 21.1 3.5 20 3.5Z" fill="currentColor"/>
                            </svg>
                        </span>
                    @endif
                </div>
                <div class="chat-msg__content">
                    <div class="chat-msg__bubble">
                        Hi! I'm <strong>{{ $botName }}</strong>, your AI assistant. I can help you find scholarships, check your eligibility, and track your applications.
                        <br><br>
                        What can I help you with today?
                    </div>
                    <div class="chat-msg__time">Just now</div>
                </div>
            </div>

            <div id="chat-typing" class="chat-typing" aria-label="{{ $botName }} is typing" hidden>
                <div class="chat-avatar chat-avatar--msg">
                    @if ($botAvatar)
                        <img src="{{ $botAvatar }}" alt="{{ $botName }} avatar">
                    @else
                        <span aria-hidden="true" class="chat-message-icon">
                            <svg class="chat-icon-svg" viewBox="0 0 24 24" fill="none">
                                <path d="M20 3.5H4C2.9 3.5 2 4.4 2 5.5V21L6.4 16.8H20C21.1 16.8 22 15.9 22 14.8V5.5C22 4.4 21.1 3.5 20 3.5Z" fill="currentColor"/>
                            </svg>
                        </span>
                    @endif
                </div>
                <div class="chat-typing__bubble">
                    <span class="chat-typing__dot"></span>
                    <span class="chat-typing__dot"></span>
                    <span class="chat-typing__dot"></span>
                </div>
            </div>
        </div>

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
                    <span>{{ $chip['label'] }}</span>
                </button>
            @endforeach
        </div>

        <div class="chat-window__input-area">
            <div class="chat-window__input-row">
                <input
                    id="chat-input"
                    type="text"
                    class="chat-window__input"
                    placeholder="Ask me about scholarships..."
                    aria-label="Type your message"
                    onkeydown="if(event.key==='Enter')ChatWidget.sendMessage()"
                >
                <button class="chat-window__send-btn" type="button" aria-label="Send message" onclick="ChatWidget.sendMessage()">➤</button>
            </div>
            <p class="chat-window__disclaimer">Scholar AI · Responses may not be 100% accurate</p>
        </div>
    </section>
</div>

<style>
:root {
    --cw-teal-900: #0f4c5c;
    --cw-teal-800: #176a78;
    --cw-teal-700: #1d7781;
    --cw-teal-100: #eaf7f7;
    --cw-teal-200: #c7e8e6;
    --cw-teal-500: #80b9b8;
    --cw-ink: #073643;
    --cw-gold: #f2c76e;
    --cw-white: #ffffff;
    --cw-panel: #eefafa;
    --cw-border: #d9eceb;
    --cw-shadow: 0 16px 48px rgba(15, 76, 92, 0.16);
    --cw-z: 9999;
    --cw-fab-size: 56px;
    --cw-fab-bottom: 28px;
    --cw-fab-right: 28px;
    --cw-transition: 0.24s cubic-bezier(.4, 0, .2, 1);
}

#chat-widget-root *,
#chat-widget-root *::before,
#chat-widget-root *::after {
    box-sizing: border-box;
}

.chat-fab {
    position: fixed;
    right: var(--cw-fab-right);
    bottom: var(--cw-fab-bottom);
    z-index: var(--cw-z);
    width: var(--cw-fab-size);
    height: var(--cw-fab-size);
    border: 0;
    border-radius: 999px;
    background: linear-gradient(135deg, var(--cw-teal-900), var(--cw-teal-800));
    color: var(--cw-gold);
    box-shadow: 0 12px 28px rgba(15, 76, 92, 0.28);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform var(--cw-transition), box-shadow var(--cw-transition);
}

.chat-fab:hover {
    transform: translateY(-2px) scale(1.04);
    box-shadow: 0 16px 34px rgba(15, 76, 92, 0.34);
}

.chat-fab__icon {
    position: absolute;
    font: 700 22px/1 "DM Sans", sans-serif;
    transition: opacity var(--cw-transition), transform var(--cw-transition);
}

.chat-icon-svg {
    width: 22px;
    height: 22px;
    display: block;
}

.chat-fab__icon--close {
    opacity: 0;
    transform: rotate(-90deg) scale(.7);
}

.chat-fab.is-open .chat-fab__icon--chat {
    opacity: 0;
    transform: rotate(90deg) scale(.7);
}

.chat-fab.is-open .chat-fab__icon--close {
    opacity: 1;
    transform: rotate(0) scale(1);
}

.chat-fab__badge {
    position: absolute;
    top: -4px;
    right: -4px;
    min-width: 20px;
    height: 20px;
    padding: 0 5px;
    border-radius: 999px;
    background: #ef4444;
    color: #fff;
    border: 2px solid #fff;
    font: 700 11px/16px "DM Sans", sans-serif;
}

.chat-fab--pulse::before,
.chat-fab--pulse::after {
    content: "";
    position: absolute;
    inset: -6px;
    border: 2px solid var(--cw-teal-700);
    border-radius: inherit;
    animation: chat-pulse 2.4s ease-out infinite;
    opacity: 0;
}

.chat-fab--pulse::after {
    animation-delay: .8s;
}

@keyframes chat-pulse {
    0% { opacity: .55; transform: scale(1); }
    80%, 100% { opacity: 0; transform: scale(1.5); }
}

.chat-pill {
    position: fixed;
    right: var(--cw-fab-right);
    bottom: calc(var(--cw-fab-bottom) + var(--cw-fab-size) + 12px);
    z-index: calc(var(--cw-z) - 1);
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px 10px 10px;
    border-radius: 999px;
    background: var(--cw-white);
    border: 1px solid var(--cw-border);
    box-shadow: var(--cw-shadow);
    cursor: pointer;
    opacity: 0;
    pointer-events: none;
    transform: translateY(8px) scale(.96);
    transition: opacity var(--cw-transition), transform var(--cw-transition);
}

.chat-pill.is-visible {
    opacity: 1;
    pointer-events: auto;
    transform: translateY(0) scale(1);
}

.chat-pill__info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.chat-pill__name {
    color: var(--cw-ink);
    font: 700 14px/1.1 "DM Sans", sans-serif;
}

.chat-pill__subtitle {
    color: var(--cw-teal-500);
    font: 400 12px/1.1 "DM Sans", sans-serif;
}

.chat-window {
    position: fixed;
    right: var(--cw-fab-right);
    bottom: calc(var(--cw-fab-bottom) + var(--cw-fab-size) + 12px);
    z-index: calc(var(--cw-z) - 1);
    width: 384px;
    height: min(680px, calc(100vh - 124px));
    max-height: 680px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    border-radius: 20px;
    background: var(--cw-white);
    border: 1px solid var(--cw-border);
    box-shadow: var(--cw-shadow);
    opacity: 0;
    pointer-events: none;
    transform: translateY(14px) scale(.96);
    transform-origin: bottom right;
    transition: opacity var(--cw-transition), transform var(--cw-transition);
}

.chat-window.is-open {
    opacity: 1;
    pointer-events: auto;
    transform: translateY(0) scale(1);
}

.chat-window__header {
    flex: 0 0 auto;
    min-height: 84px;
    padding: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    background: linear-gradient(135deg, var(--cw-teal-900), var(--cw-teal-700));
    color: var(--cw-white);
}

.chat-window__identity {
    min-width: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.chat-window__bot-name {
    margin: 0;
    color: var(--cw-white);
    font: 700 16px/1.2 "DM Sans", sans-serif;
}

.chat-window__bot-status {
    margin: 3px 0 0;
    color: rgba(255, 255, 255, .72);
    font: 400 12px/1.2 "DM Sans", sans-serif;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.chat-window__bot-status span {
    width: 7px;
    height: 7px;
    border-radius: 999px;
    background: #3ee77d;
}

.chat-window__header-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.chat-icon-btn {
    width: 32px;
    height: 32px;
    border: 0;
    border-radius: 8px;
    background: rgba(255, 255, 255, .14);
    color: rgba(255, 255, 255, .86);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font: 600 14px/1 "Inter", sans-serif;
    transition: background var(--cw-transition), color var(--cw-transition);
}

.chat-icon-btn:hover {
    background: rgba(255, 255, 255, .22);
    color: #fff;
}

.chat-avatar {
    flex: 0 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-radius: 999px;
    background: var(--cw-teal-900);
    color: var(--cw-gold);
    font-family: "DM Sans", sans-serif;
}

.chat-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.chat-message-icon {
    display: flex;
    align-items: center;
    justify-content: center;
}

.chat-avatar--header {
    width: 44px;
    height: 44px;
    background: rgba(255, 255, 255, .16);
    border: 2px solid rgba(255, 255, 255, .28);
    font-size: 21px;
}

.chat-avatar--header .chat-icon-svg {
    width: 21px;
    height: 21px;
}

.chat-avatar--bot,
.chat-avatar--msg {
    width: 28px;
    height: 28px;
    font-size: 13px;
}

.chat-avatar--bot .chat-icon-svg,
.chat-avatar--msg .chat-icon-svg {
    width: 14px;
    height: 14px;
}

.chat-window__messages {
    flex: 1 1 auto;
    min-height: 0;
    display: flex;
    flex-direction: column;
    gap: 14px;
    overflow-y: auto;
    padding: 18px 18px 14px;
    background: var(--cw-panel);
    scrollbar-width: thin;
    scrollbar-color: var(--cw-teal-200) transparent;
}

.chat-msg {
    display: flex;
    align-items: flex-end;
    gap: 8px;
}

.chat-msg--user {
    flex-direction: row-reverse;
}

.chat-msg__content {
    max-width: min(272px, calc(100% - 42px));
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.chat-msg--user .chat-msg__content {
    align-items: flex-end;
}

.chat-msg__bubble {
    width: fit-content;
    max-width: 100%;
    padding: 12px 14px;
    border-radius: 18px 18px 18px 5px;
    background: var(--cw-white);
    border: 1px solid var(--cw-border);
    color: var(--cw-ink);
    font: 400 13px/1.55 "DM Sans", sans-serif;
}

.chat-msg--user .chat-msg__bubble {
    border-color: transparent;
    border-radius: 18px 18px 5px 18px;
    background: linear-gradient(135deg, var(--cw-teal-900), var(--cw-teal-800));
    color: #fff;
}

.chat-msg__time {
    padding: 0 4px;
    color: var(--cw-teal-500);
    font: 400 10px/1.2 "DM Sans", sans-serif;
}

.chat-msg--user .chat-msg__time {
    text-align: right;
}

.chat-typing {
    display: flex;
    align-items: flex-end;
    gap: 8px;
}

.chat-typing[hidden] {
    display: none;
}

.chat-typing__bubble {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 12px 16px;
    border-radius: 18px 18px 18px 5px;
    background: var(--cw-white);
    border: 1px solid var(--cw-border);
}

.chat-typing__dot {
    width: 6px;
    height: 6px;
    border-radius: 2px;
    background: var(--cw-teal-500);
    animation: chat-typing-bounce .9s ease-in-out infinite;
}

.chat-typing__dot:nth-child(2) {
    animation-delay: .15s;
}

.chat-typing__dot:nth-child(3) {
    animation-delay: .3s;
}

@keyframes chat-typing-bounce {
    0%, 80%, 100% { opacity: .45; transform: translateY(0); }
    40% { opacity: 1; transform: translateY(-4px); }
}

.chat-chips {
    flex: 0 0 auto;
    display: flex;
    flex-wrap: wrap;
    align-content: flex-start;
    gap: 8px;
    min-height: 80px;
    padding: 12px 18px;
    background: var(--cw-panel);
    border-top: 1px solid rgba(217, 236, 235, .65);
}

.chat-chips.is-hidden {
    display: none;
}

.chat-chip {
    height: 27px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 12px;
    border-radius: 999px;
    border: 1px solid #bfdedd;
    background: #fff;
    color: var(--cw-teal-900);
    cursor: pointer;
    white-space: nowrap;
    font: 700 12px/1 "DM Sans", sans-serif;
    transition: background var(--cw-transition), border-color var(--cw-transition), transform var(--cw-transition);
}

.chat-chip:hover {
    background: #f5ffff;
    border-color: var(--cw-teal-500);
    transform: translateY(-1px);
}

.chat-chip__icon {
    font-size: 12px;
    line-height: 1;
}

.chat-window__input-area {
    flex: 0 0 auto;
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 12px 14px 14px;
    background: #fff;
    border-top: 1px solid var(--cw-border);
}

.chat-window__input-row {
    display: flex;
    align-items: center;
    gap: 10px;
}

.chat-window__input {
    min-width: 0;
    flex: 1 1 auto;
    height: 44px;
    padding: 0 16px;
    border: 1px solid var(--cw-border);
    border-radius: 999px;
    background: #f2fbfb;
    color: var(--cw-ink);
    outline: none;
    font: 400 14px/1 "DM Sans", sans-serif;
}

.chat-window__input::placeholder {
    color: var(--cw-teal-500);
}

.chat-window__input:focus {
    border-color: var(--cw-teal-500);
    background: #fff;
}

.chat-window__send-btn {
    flex: 0 0 auto;
    width: 44px;
    height: 44px;
    border: 0;
    border-radius: 999px;
    background: linear-gradient(135deg, var(--cw-teal-900), var(--cw-teal-800));
    color: var(--cw-gold);
    cursor: pointer;
    box-shadow: 0 3px 10px rgba(15, 76, 92, .25);
    font: 700 17px/1 "Inter", sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform var(--cw-transition), box-shadow var(--cw-transition);
}

.chat-window__send-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 14px rgba(15, 76, 92, .28);
}

.chat-window__send-btn:disabled,
.chat-window__input:disabled {
    opacity: .65;
}

.chat-window__disclaimer {
    margin: 0;
    color: var(--cw-teal-500);
    text-align: center;
    font: 400 10px/1.2 "DM Sans", sans-serif;
}

@media (max-width: 440px) {
    .chat-window {
        right: 8px;
        left: 8px;
        bottom: 8px;
        width: auto;
        height: min(680px, calc(100vh - 16px));
    }

    .chat-fab {
        right: 18px;
        bottom: 18px;
    }

    .chat-pill {
        right: 18px;
        bottom: 86px;
        max-width: calc(100vw - 36px);
    }
}
</style>

<<<<<<< HEAD

{{-- ═══════════════════════════════════════════════════════════════════════════
     JAVASCRIPT — ChatWidget namespace
═══════════════════════════════════════════════════════════════════════════ --}}
=======
@once
>>>>>>> a0f55fbe7848207707a89c7aef22ded2ba576bcd
<script>
(function () {
    'use strict';

    const state = {
        isOpen: false,
        isMinimized: false,
        isSending: false,
        lastGeminiRequestAt: 0,
        unread: parseInt(document.getElementById('chat-widget-root')?.dataset.unread || '0', 10),
    };

    const fab = () => document.getElementById('chat-fab');
    const pill = () => document.getElementById('chat-pill');
    const win = () => document.getElementById('chat-window');
    const messages = () => document.getElementById('chat-messages');
    const typing = () => document.getElementById('chat-typing');
    const chips = () => document.getElementById('chat-chips');
    const input = () => document.getElementById('chat-input');
    const badge = () => document.getElementById('chat-fab-badge');
    const root = () => document.getElementById('chat-widget-root');

    function botInitial() {
        return root()?.dataset.botInitial || 'I';
    }

    function chatUrl() {
        return root()?.dataset.chatUrl || '/ai/chat';
    }

    function responseCacheKey(text) {
        return 'scholarlink-chat:' + text.trim().toLowerCase().replace(/\s+/g, ' ');
    }

    function getCachedResponse(text) {
        try {
            const raw = sessionStorage.getItem(responseCacheKey(text));
            if (!raw) return null;

            const cached = JSON.parse(raw);
            if (!cached.reply || Date.now() > cached.expiresAt) {
                sessionStorage.removeItem(responseCacheKey(text));
                return null;
            }

            return cached.reply;
        } catch {
            return null;
        }
    }

    function setCachedResponse(text, reply) {
        try {
            sessionStorage.setItem(responseCacheKey(text), JSON.stringify({
                reply,
                expiresAt: Date.now() + 20 * 60 * 1000,
            }));
        } catch {
            // Storage can be unavailable in private windows.
        }
    }

    function messageIconSvg() {
        return '<span aria-hidden="true" class="chat-message-icon"><svg class="chat-icon-svg" viewBox="0 0 24 24" fill="none"><path d="M20 3.5H4C2.9 3.5 2 4.4 2 5.5V21L6.4 16.8H20C21.1 16.8 22 15.9 22 14.8V5.5C22 4.4 21.1 3.5 20 3.5Z" fill="currentColor"/></svg></span>';
    }

    function nowTime() {
        return new Intl.DateTimeFormat(undefined, {
            hour: 'numeric',
            minute: '2-digit',
        }).format(new Date());
    }

    function scrollToBottom() {
        const el = messages();
        if (el) el.scrollTop = el.scrollHeight;
    }

    function escapeHtml(str) {
        return String(str).replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function appendMessage(text, role) {
        const el = messages();
        if (!el) return;

        const msg = document.createElement('div');
        msg.className = 'chat-msg chat-msg--' + role;
        msg.style.opacity = '0';
        msg.style.transform = 'translateY(6px)';
        msg.style.transition = 'opacity .22s ease, transform .22s ease';

        if (role === 'bot') {
            msg.innerHTML = `
                <div class="chat-avatar chat-avatar--msg">${messageIconSvg()}</div>
                <div class="chat-msg__content">
                    <div class="chat-msg__bubble">${escapeHtml(text)}</div>
                    <div class="chat-msg__time">${nowTime()}</div>
                </div>`;
        } else {
            msg.innerHTML = `
                <div class="chat-msg__content">
                    <div class="chat-msg__bubble">${escapeHtml(text)}</div>
                    <div class="chat-msg__time">${nowTime()}</div>
                </div>`;
        }

        el.insertBefore(msg, typing());

        requestAnimationFrame(() => {
            msg.style.opacity = '1';
            msg.style.transform = 'translateY(0)';
        });

        scrollToBottom();
    }

    function clearUnread() {
        state.unread = 0;
        const b = badge();
        if (b) b.remove();
        fab()?.classList.remove('chat-fab--pulse');
    }

    function setSending(isSending) {
        state.isSending = isSending;

        const inp = input();
        const sendButton = document.querySelector('.chat-window__send-btn');

        if (inp) inp.disabled = isSending;
        if (sendButton) {
            sendButton.disabled = isSending;
            sendButton.style.cursor = isSending ? 'wait' : 'pointer';
        }
    }

    window.ChatWidget = {
        toggleChat() {
            state.isOpen ? this.closeChat() : this.openChat();
        },

        openChat() {
            state.isOpen = true;
            state.isMinimized = false;

            fab()?.classList.add('is-open');
            win()?.classList.add('is-open');
            pill()?.classList.remove('is-visible');

            clearUnread();
            scrollToBottom();
            setTimeout(() => input()?.focus(), 240);
        },

        closeChat() {
            state.isOpen = false;
            state.isMinimized = false;

            fab()?.classList.remove('is-open');
            win()?.classList.remove('is-open');
            pill()?.classList.remove('is-visible');
        },

        minimizeChat() {
            state.isOpen = false;
            state.isMinimized = true;

            fab()?.classList.remove('is-open');
            win()?.classList.remove('is-open');
            pill()?.classList.add('is-visible');
        },

        expandChat() {
            pill()?.classList.remove('is-visible');
            this.openChat();
        },

        sendChip(label) {
            if (state.isSending) return;

            chips()?.classList.add('is-hidden');
            appendMessage(label, 'user');
            this._fetchBotResponse(label);
        },

        sendMessage() {
            if (state.isSending) return;

            const inp = input();
            const text = inp?.value.trim();
            if (!text) return;

            chips()?.classList.add('is-hidden');
            appendMessage(text, 'user');
            inp.value = '';

            this._fetchBotResponse(text);
        },

        showTyping() {
            const t = typing();
            if (t) {
                t.removeAttribute('hidden');
                scrollToBottom();
            }
        },

        hideTyping() {
            typing()?.setAttribute('hidden', '');
        },

        _fetchBotResponse(text) {
            const cachedReply = getCachedResponse(text);
            if (cachedReply) {
                appendMessage(cachedReply, 'bot');
                return;
            }

            const waitMs = 5000 - (Date.now() - state.lastGeminiRequestAt);
            if (waitMs > 0) {
                appendMessage(`Please wait ${Math.ceil(waitMs / 1000)} seconds before sending another AI request. This helps protect the free-tier quota.`, 'bot');
                return;
            }

            state.lastGeminiRequestAt = Date.now();
            setSending(true);
            this.showTyping();

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            fetch(chatUrl(), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ message: text }),
            })
            .then(async res => {
                const data = await res.json().catch(() => ({}));
                if (!res.ok && !data.reply) throw new Error('Network error ' + res.status);
                return data;
            })
            .then(data => {
                this.hideTyping();
                const reply = data.reply || 'Sorry, I could not get a response.';
                appendMessage(reply, 'bot');

                if (!data.cached && !reply.includes('quota') && !reply.includes('rate limit') && !reply.includes('Unable to get')) {
                    setCachedResponse(text, reply);
                }
            })
            .catch(() => {
                this.hideTyping();
                appendMessage('Something went wrong while contacting Gemini. Please try again.', 'bot');
            })
            .finally(() => {
                setSending(false);
                input()?.focus();
            });
        },
    };
})();
</script>
<<<<<<< HEAD
=======
@endonce
>>>>>>> a0f55fbe7848207707a89c7aef22ded2ba576bcd
