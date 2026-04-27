{{--
╔══════════════════════════════════════════════════════════════════════╗
║  ISKO CHAT WIDGET  ·  Laravel Blade View                            ║
║  ──────────────────────────────────────────────────────────────────  ║
║  Pixel-perfect recreation of the provided UI mockup.                ║
║  360×560px · fixed; bottom:88px; right:28px · border-radius:20px   ║
║                                                                      ║
║  REQUIRED CONTROLLER VARIABLES                                       ║
║  ──────────────────────────────────────────────────────────────────  ║
║  $assistant = [                                                       ║
║      'name'          => string,   // "Isko"                          ║
║      'subtitle'      => string,   // "Powered by Gemini AI"          ║
║      'avatar_emoji'  => string,   // "🤖"                            ║
║      'online'        => bool,                                         ║
║      'disclaimer'    => string,   // footer note                     ║
║  ]                                                                   ║
║                                                                      ║
║  $user = [                                                           ║
║      'name'     => string,   // "Ysa"                               ║
║      'initials' => string,   // "JD"                                ║
║  ]                                                                   ║
║                                                                      ║
║  $messages = [ [                                                     ║
║      'type'       => 'bot'|'user',                                   ║
║      'text'       => string,   // plain text (HTML escaped for bot)  ║
║      'time'       => string|null,                                    ║
║      'html'       => bool,     // true = render raw HTML in bubble   ║
║  ], ... ]                                                            ║
║                                                                      ║
║  $quickActions = [ [                                                 ║
║      'icon'    => string,   // emoji                                 ║
║      'label'   => string,                                            ║
║      'action'  => string,   // machine key sent to backend           ║
║  ], ... ]                                                            ║
║                                                                      ║
║  $inputPlaceholder = string                                          ║
║  $chatEndpoint     = string   // POST route URL                      ║
╚══════════════════════════════════════════════════════════════════════╝
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    {{-- CSRF token read by JS for AJAX fetch --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $assistant['name'] }}</title>

    {{-- ================================================================
         STYLES
         ================================================================ --}}
    <style>
        /* ── 0. Reset ─────────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ── 1. Page demo backdrop ────────────────────────────────────── */
        /*
         * Remove or override this rule in your real layout.
         * It is only here so the widget is visible on a blank page.
         */
        html, body {
            min-height: 100vh;
            background: #112030;   /* dark teal-navy matching screenshot bg */
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                         'Helvetica Neue', Arial, sans-serif;
        }

        /* ================================================================
           2. WIDGET SHELL
           Exact dimensions from spec: 360×560 · bottom:88 · right:28 · r:20
           ================================================================ */
        .cw {                               /* cw = chat-widget */
            position: fixed;
            bottom: 88px;
            right: 28px;
            width: 360px;
            height: 560px;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            background: #eef2f7;            /* body bg — light blue-grey */
            box-shadow:
                0 24px 56px rgba(0,0,0,0.40),
                0  8px 20px rgba(0,0,0,0.22),
                0  0   0  1px rgba(255,255,255,0.07);
            z-index: 9999;
            /* Entry animation */
            animation: cwSlideIn .38s cubic-bezier(.34,1.56,.64,1) both;
        }

        @keyframes cwSlideIn {
            from { opacity:0; transform:translateY(28px) scale(.95); }
            to   { opacity:1; transform:translateY(0)    scale(1);   }
        }

        /* ================================================================
           3. HEADER
           ================================================================ */
        .cw__header {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 14px 13px 16px;
            /*
             * The header background from the image is a very dark navy/teal.
             * Measured: approximately #1b2d3e (dark desaturated teal-blue).
             */
            background: #1b2d3e;
        }

        /* ── Bot avatar circle (header) ── */
        .cw__hd-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            /* Teal-to-blue gradient matching the robot icon ring in image */
            background: linear-gradient(145deg, #2e9688 0%, #1f6fb0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.30);
        }

        /* ── Name + status ── */
        .cw__hd-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .cw__hd-name {
            font-size: 15.5px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: .01em;
            line-height: 1.2;
        }

        .cw__hd-status {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            color: #8fadc8;
            line-height: 1;
        }

        /* Pulsing green dot */
        .cw__online-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #2ecc71;
            flex-shrink: 0;
            animation: cwPulse 2.2s ease-in-out infinite;
        }

        @keyframes cwPulse {
            0%,100% { box-shadow: 0 0 0 0   rgba(46,204,113,.65); }
            55%      { box-shadow: 0 0 0 4px rgba(46,204,113,.00); }
        }

        /* ── Control buttons (─ and ×) ── */
        .cw__hd-controls {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }

        .cw__ctrl {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: none;
            background: rgba(255,255,255,.12);
            color: #a8c4dc;
            font-size: 16px;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .18s, color .18s, transform .14s;
        }

        .cw__ctrl:hover {
            background: rgba(255,255,255,.22);
            color: #ffffff;
            transform: scale(1.10);
        }

        .cw__ctrl--close:hover {
            background: rgba(220,53,69,.30);
            color: #ff8a95;
        }

        /* ── Minus icon: render as a horizontal bar ── */
        .cw__ctrl--min .cw__ctrl-icon {
            display: block;
            width: 12px;
            height: 2px;
            border-radius: 1px;
            background: currentColor;
        }

        /* ================================================================
           4. CHAT BODY  (scrollable)
           ================================================================ */
        .cw__body {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 20px 14px 8px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            scroll-behavior: smooth;
        }

        /* Thin custom scrollbar */
        .cw__body::-webkit-scrollbar        { width: 4px; }
        .cw__body::-webkit-scrollbar-track  { background: transparent; }
        .cw__body::-webkit-scrollbar-thumb  {
            background: rgba(0,0,0,.15);
            border-radius: 4px;
        }

        /* ================================================================
           5. MESSAGE ROWS
           ================================================================ */
        /* Each .cw-msg is a full-width flex column */
        .cw-msg {
            display: flex;
            flex-direction: column;
            gap: 5px;
            animation: cwMsgIn .22s ease both;
        }

        @keyframes cwMsgIn {
            from { opacity:0; transform:translateY(6px); }
            to   { opacity:1; transform:translateY(0);   }
        }

        .cw-msg--bot  { align-items: flex-start; }
        .cw-msg--user { align-items: flex-end;   }

        /* The horizontal row that holds avatar + bubble */
        .cw-msg__row {
            display: flex;
            align-items: flex-end;      /* avatars sit at bubble bottom */
            gap: 8px;
            max-width: 100%;
        }

        .cw-msg--user .cw-msg__row { flex-direction: row-reverse; }

        /* ── Bot avatar (small, beside bubble) ── */
        .cw-msg__bot-av {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(145deg, #2e9688 0%, #1f6fb0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(0,0,0,.22);
            /* The bot avatar is placed to the LEFT and slightly outside;
               a negative left margin nudges it into the chat gutter. */
            margin-left: -4px;
        }

        /* ── User initials avatar ── */
        .cw-msg__user-av {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            /*
             * From image: user avatar is a dark teal/navy circle with white
             * "JD" text — very close to #1b4060 or similar.
             */
            background: #1c3f58;
            color: #ffffff;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .03em;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-right: -4px;
        }

        /* ── Bubble base ── */
        .cw-msg__bubble {
            padding: 12px 15px;
            font-size: 13.5px;
            line-height: 1.56;
            word-break: break-word;
            max-width: calc(100% - 52px);   /* leave room for avatar */
        }

        /* Bot bubble: white card */
        .cw-msg--bot .cw-msg__bubble {
            background: #ffffff;
            color: #1e2d3d;
            border-radius: 16px 16px 16px 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,.08), 0 2px 8px rgba(0,0,0,.05);
        }

        /* User bubble: dark teal */
        .cw-msg--user .cw-msg__bubble {
            /*
             * From image: dark desaturated teal. Measured ~#1c3b4e.
             */
            background: #1c3b4e;
            color: #e6f0f8;
            border-radius: 16px 16px 4px 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,.25);
        }

        /* Timestamp */
        .cw-msg__time {
            font-size: 10.5px;
            color: #8fa8c0;
            /* Indent to align with bubble (past avatar width + gap) */
            padding: 0 2px;
        }

        .cw-msg--bot  .cw-msg__time { margin-left: 36px;  }   /* avatar(32) + gap(8) - 4 */
        .cw-msg--user .cw-msg__time { margin-right: 36px; text-align: right; }

        /* ================================================================
           6. QUICK-ACTION CHIPS
           ================================================================ */
        .cw__actions {
            flex-shrink: 0;
            padding: 4px 14px 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            background: #eef2f7;
        }

        .cw__chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 13px;
            border: 1.5px solid #cdd8e8;
            border-radius: 20px;
            background: #ffffff;
            font-size: 12.5px;
            font-weight: 500;
            color: #2d3f52;
            cursor: pointer;
            white-space: nowrap;
            line-height: 1;
            transition:
                border-color .18s,
                background   .18s,
                color        .18s,
                transform    .14s,
                box-shadow   .18s;
        }

        .cw__chip:hover {
            border-color: #1e8b7a;
            background: #eaf7f5;
            color: #155f54;
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(30,139,122,.14);
        }

        .cw__chip:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .cw__chip-icon { font-size: 13px; }

        /* ================================================================
           7. INPUT AREA
           ================================================================ */
        .cw__input-area {
            flex-shrink: 0;
            padding: 8px 14px 13px;
            background: #eef2f7;
            border-top: 1px solid rgba(0,0,0,.07);
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .cw__input-row {
            display: flex;
            align-items: flex-end;
            gap: 9px;
        }

        /* Rounded textarea pill */
        .cw__textarea {
            flex: 1;
            padding: 10px 16px;
            border: 1.5px solid #cdd8e8;
            border-radius: 22px;
            background: #ffffff;
            font-size: 13.5px;
            font-family: inherit;
            color: #1e2d3d;
            resize: none;
            outline: none;
            line-height: 1.45;
            /*
             * Max-height = 3 lines of text + padding.
             * 1.45em × 3 ≈ 4.35em + top+bottom padding (20px) = ~4.35em + 20px
             */
            max-height: calc(1.45em * 3 + 22px);
            overflow-y: auto;
            transition: border-color .20s, box-shadow .20s;
        }

        .cw__textarea::placeholder { color: #9eb3c8; }

        .cw__textarea:focus {
            border-color: #2e9688;
            box-shadow: 0 0 0 3px rgba(46,150,136,.13);
        }

        .cw__textarea::-webkit-scrollbar { width: 0; }

        /* Send button — dark teal circle */
        .cw__send {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: none;
            /*
             * From image: the send button is a dark teal circle ~#1c3b4e.
             */
            background: #1c3b4e;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(28,59,78,.35);
            transition: background .18s, transform .14s, box-shadow .18s;
        }

        .cw__send:hover {
            background: #12293a;
            transform: scale(1.08);
            box-shadow: 0 5px 16px rgba(28,59,78,.45);
        }

        .cw__send:active { transform: scale(.96); }

        /* Paper-plane SVG */
        .cw__send svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
            transform: translateX(1px);   /* optical centre */
        }

        /* Disclaimer */
        .cw__disclaimer {
            font-size: 10.5px;
            color: #90a8be;
            text-align: center;
            line-height: 1.35;
        }

        /* ================================================================
           8. TYPING INDICATOR
           ================================================================ */
        .cw__typing {
            display: none;
            align-items: center;
            gap: 4px;
            padding: 11px 15px;
            background: #ffffff;
            border-radius: 16px 16px 16px 4px;
            width: fit-content;
            margin-left: 36px;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
        }

        .cw__typing.is-on { display: flex; }

        .cw__typing-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #8fa8c0;
            animation: cwDot 1.3s ease-in-out infinite;
        }

        .cw__typing-dot:nth-child(2) { animation-delay: .20s; }
        .cw__typing-dot:nth-child(3) { animation-delay: .40s; }

        @keyframes cwDot {
            0%,55%,100% { transform:translateY(0);    opacity:.45; }
            28%          { transform:translateY(-5px); opacity:1;   }
        }
    </style>
</head>
<body>

{{-- ================================================================
     WIDGET ROOT
     ================================================================ --}}
<div
    class="cw"
    id="iskoWidget"
    role="dialog"
    aria-label="{{ $assistant['name'] }} Chat Assistant"
    aria-modal="true"
>

    {{-- ────────────────────────────────────────────────────────────
         HEADER
         ──────────────────────────────────────────────────────────── --}}
    <header class="cw__header">

        {{-- Bot avatar --}}
        <div class="cw__hd-avatar" aria-hidden="true">
            {{ $assistant['avatar_emoji'] }}
        </div>

        {{-- Name + online status --}}
        <div class="cw__hd-info">
            <span class="cw__hd-name">{{ $assistant['name'] }}</span>

            <div class="cw__hd-status">
                @if($assistant['online'])
                    <span class="cw__online-dot" aria-hidden="true"></span>
                    <span>Online &middot; {{ $assistant['subtitle'] }}</span>
                @else
                    <span>Offline &middot; {{ $assistant['subtitle'] }}</span>
                @endif
            </div>
        </div>

        {{-- Window controls --}}
        <div class="cw__hd-controls" role="group" aria-label="Window controls">

            {{-- Minimize (renders as a short horizontal bar, matching the image) --}}
            <button
                class="cw__ctrl cw__ctrl--min"
                type="button"
                aria-label="Minimize"
                onclick="cwMinimize()"
            ><span class="cw__ctrl-icon" aria-hidden="true"></span></button>

            {{-- Close --}}
            <button
                class="cw__ctrl cw__ctrl--close"
                type="button"
                aria-label="Close"
                onclick="cwClose()"
            >&times;</button>

        </div>
    </header>

    {{-- ────────────────────────────────────────────────────────────
         CHAT BODY  (scrollable)
         ──────────────────────────────────────────────────────────── --}}
    <div
        class="cw__body"
        id="cwBody"
        role="log"
        aria-live="polite"
        aria-relevant="additions"
    >
        {{-- ── Render all messages ── --}}
        @foreach($messages as $msg)

            <div class="cw-msg cw-msg--{{ $msg['type'] }}">

                @if($msg['type'] === 'bot')
                    {{-- Bot: avatar LEFT, bubble RIGHT of avatar --}}
                    <div class="cw-msg__row">
                        <div class="cw-msg__bot-av" aria-hidden="true">
                            {{ $assistant['avatar_emoji'] }}
                        </div>
                        <div class="cw-msg__bubble">
                            {{--
                                If the message is marked as pre-rendered HTML
                                (e.g. bold text, links), output raw.
                                Otherwise nl2br + escape for safety.
                            --}}
                            @if(!empty($msg['html']))
                                {!! $msg['text'] !!}
                            @else
                                {!! nl2br(e($msg['text'])) !!}
                            @endif
                        </div>
                    </div>

                @elseif($msg['type'] === 'user')
                    {{-- User: bubble LEFT, initials avatar RIGHT --}}
                    <div class="cw-msg__row">
                        <div class="cw-msg__bubble">{{ $msg['text'] }}</div>
                        <div
                            class="cw-msg__user-av"
                            aria-label="{{ $user['name'] }}"
                        >{{ $user['initials'] }}</div>
                    </div>
                @endif

                {{-- Timestamp (optional per message) --}}
                @if(!empty($msg['time']))
                    <span class="cw-msg__time">{{ $msg['time'] }}</span>
                @endif

            </div>

        @endforeach

        {{-- ── Typing indicator — JS toggles .is-on ── --}}
        <div
            class="cw__typing"
            id="cwTyping"
            role="status"
            aria-label="{{ $assistant['name'] }} is typing"
        >
            <span class="cw__typing-dot"></span>
            <span class="cw__typing-dot"></span>
            <span class="cw__typing-dot"></span>
        </div>

    </div>{{-- /.cw__body --}}

    {{-- ────────────────────────────────────────────────────────────
         QUICK-ACTION CHIPS
         ──────────────────────────────────────────────────────────── --}}
    <div class="cw__actions" role="toolbar" aria-label="Quick actions">

        @foreach($quickActions as $qa)
            <button
                class="cw__chip"
                type="button"
                data-action="{{ $qa['action'] }}"
                onclick="cwQuickAction({{ json_encode($qa['action']) }}, {{ json_encode($qa['label']) }})"
            >
                <span class="cw__chip-icon" aria-hidden="true">{{ $qa['icon'] }}</span>
                {{ $qa['label'] }}
            </button>
        @endforeach

    </div>

    {{-- ────────────────────────────────────────────────────────────
         INPUT AREA
         ──────────────────────────────────────────────────────────── --}}
    <div class="cw__input-area">

        <div class="cw__input-row">

            {{-- Auto-resize textarea --}}
            <textarea
                class="cw__textarea"
                id="cwInput"
                placeholder="{{ $inputPlaceholder }}"
                aria-label="Type a message"
                rows="1"
            ></textarea>

            {{-- Send button with paper-plane SVG --}}
            <button
                class="cw__send"
                id="cwSendBtn"
                type="button"
                aria-label="Send message"
                onclick="cwSend()"
            >
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                </svg>
            </button>

        </div>

        {{-- Disclaimer --}}
        <p class="cw__disclaimer">{{ $assistant['disclaimer'] }}</p>

    </div>

</div>{{-- /.cw --}}


{{-- ================================================================
     JAVASCRIPT
     ================================================================ --}}
<script>
(function () {
    'use strict';

    /* ── DOM refs ─────────────────────────────────────────────────── */
    const widget   = document.getElementById('iskoWidget');
    const body     = document.getElementById('cwBody');
    const inputEl  = document.getElementById('cwInput');
    const typingEl = document.getElementById('cwTyping');

    /* ── Config injected from Blade ────────────────────────────────
     *  @json() safely JSON-encodes PHP values for inline JS.          */
    const CFG = {
        endpoint:    @json($chatEndpoint),
        csrf:        document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        botEmoji:    @json($assistant['avatar_emoji']),
        userName:    @json($user['name']),
        userInitials:@json($user['initials']),
    };

    /* ================================================================
       TEXTAREA AUTO-RESIZE  (max 3 lines)
       ================================================================ */
    inputEl.addEventListener('input', resizeInput);

    function resizeInput() {
        inputEl.style.height = 'auto';
        const cs   = getComputedStyle(inputEl);
        const padY = parseFloat(cs.paddingTop) + parseFloat(cs.paddingBottom);
        const lineH= parseFloat(cs.lineHeight);
        inputEl.style.height = Math.min(inputEl.scrollHeight, lineH * 3 + padY) + 'px';
    }

    /* Enter = send  ·  Shift+Enter = newline */
    inputEl.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            cwSend();
        }
    });

    /* ================================================================
       SCROLL TO BOTTOM
       ================================================================ */
    function scrollEnd() {
        body.scrollTo({ top: body.scrollHeight, behavior: 'smooth' });
    }

    /* ================================================================
       APPEND BUBBLE  (called after send & after bot reply)
       ================================================================ */
    /**
     * @param {'bot'|'user'} type
     * @param {string}       html   — for bot: may contain HTML;
     *                                for user: will be HTML-escaped
     * @param {string|null}  time
     */
    function appendMsg(type, html, time) {
        const ts    = time ?? new Date().toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
        const wrap  = document.createElement('div');
        wrap.className = 'cw-msg cw-msg--' + type;

        if (type === 'bot') {
            wrap.innerHTML =
                '<div class="cw-msg__row">' +
                    '<div class="cw-msg__bot-av" aria-hidden="true">' + esc(CFG.botEmoji) + '</div>' +
                    '<div class="cw-msg__bubble">' + html + '</div>' +
                '</div>' +
                '<span class="cw-msg__time">' + esc(ts) + '</span>';
        } else {
            wrap.innerHTML =
                '<div class="cw-msg__row">' +
                    '<div class="cw-msg__bubble">' + esc(html) + '</div>' +
                    '<div class="cw-msg__user-av" aria-label="' + esc(CFG.userName) + '">' +
                        esc(CFG.userInitials) +
                    '</div>' +
                '</div>' +
                '<span class="cw-msg__time">' + esc(ts) + '</span>';
        }

        /* Insert before typing indicator so it stays last */
        body.insertBefore(wrap, typingEl);
        scrollEnd();
    }

    /* HTML-escape helper — prevents XSS on user-generated content */
    function esc(s) {
        return String(s)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
    }

    /* ================================================================
       TYPING INDICATOR
       ================================================================ */
    function setTyping(on) {
        typingEl.classList.toggle('is-on', on);
        if (on) scrollEnd();
    }

    /* ================================================================
       SEND MESSAGE  (exposed globally for onclick)
       ================================================================ */
    window.cwSend = async function () {
        const text = inputEl.value.trim();
        if (!text) return;

        /* 1. Render user bubble immediately */
        appendMsg('user', text, null);

        /* 2. Clear & reset input */
        inputEl.value       = '';
        inputEl.style.height= 'auto';

        /* 3. Show typing indicator */
        setTyping(true);

        try {
            /*
             * POST to the Laravel endpoint.
             * Expected JSON response shape:
             *   { "reply": "string", "time": "HH:MM AM" }
             */
            const res = await fetch(CFG.endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type':  'application/json',
                    'Accept':        'application/json',
                    'X-CSRF-TOKEN':  CFG.csrf,
                },
                body: JSON.stringify({ message: text }),
            });

            if (!res.ok) throw new Error('HTTP ' + res.status);

            const data = await res.json();
            setTyping(false);
            appendMsg('bot', data.reply ?? 'Sorry, I didn\'t get a response.', data.time ?? null);

        } catch (err) {
            console.error('[Isko]', err);
            setTyping(false);
            appendMsg('bot', 'Something went wrong. Please try again.', null);
        }
    };

    /* ================================================================
       QUICK-ACTION CHIPS
       ================================================================ */
    window.cwQuickAction = function (action, label) {
        /*
         * Populate the input with the chip label and send it,
         * letting the backend interpret the action key.
         */
        inputEl.value = label;
        resizeInput();
        cwSend();
    };

    /* ================================================================
       WIDGET VISIBILITY
       ================================================================ */
    window.cwMinimize = function () {
        _hideWidget();
    };

    window.cwClose = function () {
        _hideWidget();
    };

    /* Re-open from an external launcher button */
    window.cwOpen = function () {
        widget.style.display    = 'flex';
        widget.style.opacity    = '0';
        widget.style.transform  = 'translateY(18px) scale(.95)';
        void widget.offsetHeight; /* force reflow */
        widget.style.transition = 'opacity .28s ease, transform .36s cubic-bezier(.34,1.56,.64,1)';
        widget.style.opacity    = '1';
        widget.style.transform  = 'translateY(0) scale(1)';
        scrollEnd();
        /* Clean up inline transition after animation */
        setTimeout(function() { widget.style.transition = ''; }, 400);
    };

    function _hideWidget() {
        widget.style.transition = 'opacity .22s ease, transform .26s ease';
        widget.style.opacity    = '0';
        widget.style.transform  = 'translateY(16px) scale(.96)';
        setTimeout(function () {
            widget.style.display    = 'none';
            widget.style.opacity    = '';
            widget.style.transform  = '';
            widget.style.transition = '';
        }, 280);
    }

    /* ── Initial scroll to show latest messages ────────────────── */
    scrollEnd();

}());
</script>

</body>
</html>