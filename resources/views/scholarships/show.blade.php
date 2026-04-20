<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $scholarship->name }} — ScholarLink</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<div class="min-h-screen bg-white" style="font-family: 'DM Sans', sans-serif;">

    {{-- ===================== NAVBAR ===================== --}}
    <nav class="w-full bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-[1280px] mx-auto px-16 h-[68px] flex items-center justify-between gap-8">

            {{-- Logo --}}
            <a href="{{ route('landing') }}" class="flex items-center gap-3 flex-shrink-0">
                <div class="w-9 h-9 rounded-[10px] flex items-center justify-center flex-shrink-0"
                     style="background: linear-gradient(135deg, #0F4C5C 0%, #1A6B7A 100%);">
                    <span style="font-family: 'Fraunces', serif; font-weight: 700; font-size: 18px; color: #F9D679;">🎓</span>
                </div>
                <span style="font-family: 'Fraunces', serif; font-weight: 700; font-size: 21px; color: #0F4C5C; letter-spacing: -0.5px;">ScholarLink</span>
            </a>

            {{-- Nav Center --}}
            <div class="flex items-center gap-1 px-1 py-1 rounded-[10px]" style="background: #F0FAFA;">
                <a href="{{ route('scholarships.index') }}"
                   class="px-4 py-[7px] rounded-lg text-[13px] font-medium transition-all duration-150"
                   style="{{ request()->routeIs('scholarships.*') ? 'color: #0F4C5C; background: #FFFFFF; box-shadow: 0px 1px 6px rgba(15,76,92,0.08);' : 'color: #4A7A80;' }}">
                    Browse
                </a>
                <a href="#" class="px-4 py-[7px] rounded-lg text-[13px] font-medium transition-all duration-150" style="color: #4A7A80;">How It Works</a>
                <a href="#" class="px-4 py-[7px] rounded-lg text-[13px] font-medium transition-all duration-150" style="color: #4A7A80;">Organizations</a>
                <a href="#" class="px-4 py-[7px] rounded-lg text-[13px] font-medium transition-all duration-150" style="color: #4A7A80;">About</a>
            </div>

            {{-- Nav Right --}}
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('login') }}"
                   class="px-5 py-2 rounded-[9px] text-[13px] font-semibold border transition-all duration-150 hover:bg-[#F0FAFA]"
                   style="color: #0F4C5C; border-color: #C8E8E4;">
                    Log In
                </a>
                <a href="{{ route('register') }}"
                   class="px-5 py-2 rounded-[9px] text-[13px] font-bold transition-all duration-150 hover:opacity-90"
                   style="background: linear-gradient(104.98deg, #0F4C5C 0%, #1A6B7A 100%); color: #F9D679; box-shadow: 0px 4px 12px rgba(15,76,92,0.2);">
                    Get Started →
                </a>
            </div>
        </div>
    </nav>

    {{-- ===================== MAIN CONTENT ===================== --}}
    <div class="max-w-[1280px] mx-auto px-16 py-10">

        {{-- Back Link --}}
        <a href="{{ route('scholarships.index') }}"
           class="inline-flex items-center gap-1 text-[14px] font-bold mb-6 hover:opacity-70 transition-opacity"
           style="color: #0F4C5C;">
            ← Back to Scholarships
        </a>

        {{-- Status Badge --}}
        <div class="mb-4">
            @if($scholarship->status === 'open')
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-bold tracking-[0.5px] uppercase"
                      style="background: #C3FBD7; color: #16A34A;">
                    <span class="w-1.5 h-1.5 rounded-full" style="background: #16A34A;"></span>
                    Open
                </span>
            @elseif($scholarship->status === 'closed')
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-bold tracking-[0.5px] uppercase"
                      style="background: #FFE4E4; color: #DC2626;">
                    <span class="w-1.5 h-1.5 rounded-full" style="background: #DC2626;"></span>
                    Closed
                </span>
            @else
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-bold tracking-[0.5px] uppercase"
                      style="background: #FEF9C3; color: #CA8A04;">
                    <span class="w-1.5 h-1.5 rounded-full" style="background: #CA8A04;"></span>
                    Coming Soon
                </span>
            @endif
        </div>

        {{-- Two Column Layout --}}
        <div class="flex gap-12">

            {{-- ===== LEFT COLUMN ===== --}}
            <div class="flex-1 min-w-0">

                {{-- Name --}}
                <h1 class="mb-2 leading-none"
                    style="font-family: 'Fraunces', serif; font-weight: 700; font-size: 64px; color: #0A3040; letter-spacing: -2.5px;">
                    {{ $scholarship->name }}
                </h1>

                {{-- Tagline --}}
                <p class="mb-4"
                   style="font-family: 'Fraunces', serif; font-weight: 700; font-size: 18px; color: #0A3040; letter-spacing: 0.025em;">
                    {{ $scholarship->tagline }}
                </p>

                {{-- Benefit Snippet Row --}}
                <div class="flex flex-wrap items-center gap-6 mb-10 text-[15px]" style="color: #000000; letter-spacing: 0.0175em;">
                    @if($scholarship->income_bracket)
                        <span class="flex items-center gap-2">
                            <span style="color: #E8A838;">💰</span>
                            {{ $scholarship->income_bracket }}
                        </span>
                    @endif
                    @if($scholarship->gpa_requirement)
                        <span class="flex items-center gap-2">
                            <span>📚</span>
                            GPA {{ $scholarship->gpa_requirement }}
                        </span>
                    @endif
                    @if($scholarship->open_date)
                        <span class="flex items-center gap-2">
                            <span style="color: #E8A838;">🕐</span>
                            Posted {{ \Carbon\Carbon::parse($scholarship->open_date)->format('F j, Y') }}
                        </span>
                    @endif
                </div>

                {{-- About This Scholarship --}}
                <section class="mb-10">
                    <h2 class="mb-6" style="font-family: 'Fraunces', serif; font-weight: 700; font-size: 40px; color: #0A3040; letter-spacing: -0.015em;">
                        About This Scholarship
                    </h2>
                    <div class="text-[15px] font-bold leading-8 space-y-4" style="color: #0A3040; letter-spacing: 0.0175em; max-width: 728px;">
                        @foreach(explode("\n\n", $scholarship->description) as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                </section>

                {{-- Benefits --}}
                @if($scholarship->benefits)
                <section class="mb-10">
                    <h2 class="mb-6" style="font-family: 'Fraunces', serif; font-weight: 700; font-size: 40px; color: #0A3040; letter-spacing: -0.015em;">
                        Benefits
                    </h2>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach(explode("\n\n", $scholarship->benefits) as $benefit)
                            @if(trim($benefit))
                            <div class="flex items-center gap-3 px-4 py-4 rounded-2xl" style="background: #C8E8E4;">
                                <span class="flex-shrink-0">
                                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                                        <circle cx="14" cy="14" r="13" stroke="#E8A838" stroke-width="2"/>
                                        <path d="M8 14l4 4 8-8" stroke="#E8A838" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span class="text-[15px] font-bold leading-6" style="color: #0A3040; letter-spacing: 0.0175em;">
                                    {{ trim($benefit) }}
                                </span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </section>
                @endif

                {{-- Requirements --}}
                @if($scholarship->requirements)
                <section class="mb-10">
                    <h2 class="mb-6" style="font-family: 'Fraunces', serif; font-weight: 700; font-size: 40px; color: #0A3040; letter-spacing: -0.015em;">
                        Requirements
                    </h2>
                    <div class="space-y-4">
                        @foreach(explode("\n\n", $scholarship->requirements) as $index => $requirement)
                            @if(trim($requirement))
                            <div class="flex items-center gap-4">
                                <span class="flex-shrink-0 w-[30px] h-[30px] rounded-full flex items-center justify-center"
                                      style="background: #C8E8E4;">
                                    <span style="font-family: 'Fraunces', serif; font-weight: 700; font-size: 15px; color: #0F4C5C;">
                                        {{ $index + 1 }}
                                    </span>
                                </span>
                                <span class="text-[15px] font-bold leading-8" style="color: #0A3040; letter-spacing: 0.0175em;">
                                    {{ trim($requirement) }}
                                </span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </section>
                @endif

                {{-- Eligibility Checklist --}}
                @if($scholarship->eligibility)
                <section class="mb-10">
                    <div class="rounded-2xl px-8 pt-6 pb-8" style="background: #C8E8E4;">
                        <h2 class="mb-6" style="font-family: 'Fraunces', serif; font-weight: 700; font-size: 40px; color: #000000; letter-spacing: -0.015em;">
                            Eligibility Checklist
                        </h2>
                        <div class="space-y-3">
                            @foreach(explode("\n\n", $scholarship->eligibility) as $item)
                                @if(trim($item))
                                <div class="flex items-center gap-3">
                                    <span class="flex-shrink-0 w-[30px] h-[30px] rounded-full flex items-center justify-center"
                                          style="background: #51F49A;">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M3 8l3.5 3.5L13 5" stroke="#025724" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                    <span class="text-[15px] font-bold leading-8" style="color: #000000; letter-spacing: 0.0175em;">
                                        {{ trim($item) }}
                                    </span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </section>
                @endif

                {{-- Offered By --}}
                <section class="mb-10">
                    <h2 class="mb-6" style="font-family: 'Fraunces', serif; font-weight: 700; font-size: 40px; color: #0A3040; letter-spacing: -0.015em;">
                        Offered By
                    </h2>
                    <div class="rounded-2xl p-8" style="background: #C8E8E4;">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-[30px] h-[30px] rounded-full flex-shrink-0" style="background: #D9D9D9;"></div>
                            <span class="text-[18px] font-bold" style="color: #0A3040; letter-spacing: 0.0175em;">
                                {{ $scholarship->provider_name }}
                            </span>
                        </div>
                        <div class="space-y-2 pl-[42px]">
                            @if($scholarship->address)
                                <div class="flex items-center gap-2 text-[15px] font-bold" style="color: #0A3040;">
                                    <span class="material-icons" style="font-size: 18px; color: #1D1B20;">location_on</span>
                                    {{ $scholarship->address }}
                                </div>
                            @endif
                            @if($scholarship->website)
                                <div class="flex items-center gap-2 text-[15px] font-bold" style="color: #0A3040;">
                                    <span class="material-icons" style="font-size: 18px; color: #1D1B20;">language</span>
                                    <a href="{{ $scholarship->website }}" target="_blank" class="hover:underline">
                                        {{ $scholarship->website }}
                                    </a>
                                </div>
                            @endif
                            @if($scholarship->contact_email)
                                <div class="flex items-center gap-2 text-[15px] font-bold" style="color: #0A3040;">
                                    <span class="material-icons" style="font-size: 18px; color: #1D1B20;">mark_email_unread</span>
                                    <a href="mailto:{{ $scholarship->contact_email }}" class="hover:underline">
                                        {{ $scholarship->contact_email }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>

            </div>{{-- END LEFT COLUMN --}}

            {{-- ===== RIGHT COLUMN (Sticky Sidebar) ===== --}}
            <div class="w-[378px] flex-shrink-0">
                <div class="sticky top-[88px] space-y-4">

                    {{-- Action Bar (Apply + Save + Share) --}}
                    <div class="rounded-[20px] p-6" style="background: #1A6B7A;">

                        {{-- Apply Now --}}
                        @auth
                            <a href="{{ route('applications.create', $scholarship->id) }}"
                               class="block w-full text-center py-3 px-7 rounded-[11px] text-[15px] font-bold mb-3 transition-all hover:opacity-90"
                               style="background: linear-gradient(107.41deg, #D4A84B 0%, #F9D679 100%); color: #0F4C5C; box-shadow: 0px 8px 24px rgba(232,168,56,0.28);">
                                Apply Now
                            </a>
                        @else
                            <a href="{{ route('register') }}"
                               class="block w-full text-center py-3 px-7 rounded-[11px] text-[15px] font-bold mb-3 transition-all hover:opacity-90"
                               style="background: linear-gradient(107.41deg, #D4A84B 0%, #F9D679 100%); color: #0F4C5C; box-shadow: 0px 8px 24px rgba(232,168,56,0.28);">
                                Apply Now
                            </a>
                        @endauth

                        {{-- Save Scholarship --}}
                        @auth
                            <form action="{{ route('scholarships.save', $scholarship->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 py-2 px-4 rounded-[11px] text-[15px] font-bold border transition-all hover:bg-gray-50"
                                        style="background: #FFFFFF; color: #0F4C5C; border-color: #C8E8E4;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                        <path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/>
                                    </svg>
                                    Save Scholarship
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                               class="w-full flex items-center justify-center gap-2 py-2 px-4 rounded-[11px] text-[15px] font-bold border transition-all hover:bg-gray-50"
                               style="background: #FFFFFF; color: #0F4C5C; border-color: #C8E8E4;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                    <path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/>
                                </svg>
                                Save Scholarship
                            </a>
                        @endauth

                        {{-- Share --}}
                        <div class="mt-4 pt-4" style="border-top: 1px solid #C8E8E4;">
                            <p class="text-[13px] font-bold mb-3" style="color: #7AACAA; letter-spacing: 0.5px;">SHARE</p>
                            <div class="flex items-center gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                   target="_blank"
                                   class="w-[30px] h-[30px] flex items-center justify-center rounded-[5px] border transition-all hover:scale-110"
                                   style="background: #FFFFFF; border-color: #C8E8E4;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#1E1E1E">
                                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                                    </svg>
                                </a>
                                <a href="https://www.instagram.com/" target="_blank"
                                   class="w-[30px] h-[30px] flex items-center justify-center rounded-[5px] border transition-all hover:scale-110"
                                   style="background: #FFFFFF; border-color: #C8E8E4;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1E1E1E" stroke-width="2">
                                        <rect x="2" y="2" width="20" height="20" rx="5"/>
                                        <circle cx="12" cy="12" r="4"/>
                                        <circle cx="17.5" cy="6.5" r="0.5" fill="#1E1E1E"/>
                                    </svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($scholarship->name) }}"
                                   target="_blank"
                                   class="w-[30px] h-[30px] flex items-center justify-center rounded-[5px] border transition-all hover:scale-110"
                                   style="background: #FFFFFF; border-color: #C8E8E4;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="#1E1E1E">
                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                    </svg>
                                </a>
                                <button onclick="navigator.clipboard.writeText(window.location.href); this.title='Copied!';"
                                        class="w-[30px] h-[30px] flex items-center justify-center rounded-[5px] border transition-all hover:scale-110"
                                        style="background: #FFFFFF; border-color: #C8E8E4;" title="Copy link">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#1E1E1E" stroke-width="2.5">
                                        <path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/>
                                        <path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Slots Remaining --}}
                    <div class="rounded-[20px] p-5 border" style="background: #C8E8E4; border-color: #C8E8E4;">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F4C5C" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <span class="text-[15px] font-bold" style="color: #0F4C5C;">Slots Remaining</span>
                            </div>
                            <span style="font-family: 'Fraunces', serif; font-weight: 700; font-size: 15px; color: #0F4C5C;">
                                /{{ $scholarship->slots }}
                            </span>
                        </div>
                        @php
                            $applications = $scholarship->applications_count ?? 0;
                            $pct = $scholarship->slots > 0
                                ? min(round(($applications / $scholarship->slots) * 100), 100)
                                : 0;
                        @endphp
                        <div class="w-full h-1 rounded-full mb-2" style="background: #D8F0EE;">
                            <div class="h-full rounded-full transition-all"
                                 style="width: {{ $pct }}%; background: linear-gradient(90deg, #0F4C5C 0%, #D4A84B 100%);"></div>
                        </div>
                        <p class="text-[13px] font-bold text-center" style="color: #7AACAA;">
                            {{ $pct }}% of the slots have been claimed
                        </p>
                    </div>

                    {{-- Application Deadline Timer --}}
                    <div class="rounded-[20px] p-5 border" style="background: #1A6B7A; border-color: #C8E8E4;"
                         x-data="countdownTimer('{{ $scholarship->deadline }}')"
                         x-init="startTimer()">
                        <p class="text-[13px] font-bold mb-4" style="color: #7AACAA; letter-spacing: 1px;">APPLICATION DEADLINE</p>
                        <div class="flex items-end justify-center gap-3">
                            <div class="text-center">
                                <div class="text-[32px] font-bold leading-none mb-1"
                                     style="font-family: 'Fraunces', serif; color: #FFFFFF;" x-text="days">00</div>
                                <div class="text-[13px] font-bold" style="color: #7AACAA;">DAYS</div>
                            </div>
                            <div class="text-[32px] font-bold pb-5" style="color: #7AACAA;">:</div>
                            <div class="text-center">
                                <div class="text-[32px] font-bold leading-none mb-1"
                                     style="font-family: 'Fraunces', serif; color: #FFFFFF;" x-text="hours">00</div>
                                <div class="text-[13px] font-bold" style="color: #7AACAA;">HOURS</div>
                            </div>
                            <div class="text-[32px] font-bold pb-5" style="color: #7AACAA;">:</div>
                            <div class="text-center">
                                <div class="text-[32px] font-bold leading-none mb-1"
                                     style="font-family: 'Fraunces', serif; color: #FFFFFF;" x-text="minutes">00</div>
                                <div class="text-[13px] font-bold" style="color: #7AACAA;">MIN</div>
                            </div>
                            <div class="text-[32px] font-bold pb-5" style="color: #7AACAA;">:</div>
                            <div class="text-center">
                                <div class="text-[32px] font-bold leading-none mb-1"
                                     style="font-family: 'Fraunces', serif; color: #FFFFFF;" x-text="seconds">00</div>
                                <div class="text-[13px] font-bold" style="color: #7AACAA;">SEC</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>{{-- END RIGHT COLUMN --}}

        </div>{{-- END TWO COLUMN --}}
    </div>{{-- END MAIN CONTENT --}}

    {{-- ===================== FOOTER ===================== --}}
    <footer style="background: #071820;" class="mt-20">
        <div class="max-w-[1280px] mx-auto px-16 pt-16 pb-8">
            <div class="flex gap-12 pb-12" style="border-bottom: 1px solid rgba(255,255,255,0.06);">

                {{-- Brand --}}
                <div class="flex-1">
                    <p class="mb-3" style="font-family: 'Fraunces', serif; font-weight: 400; font-size: 20px; color: #F9D679; letter-spacing: -0.5px;">ScholarLink</p>
                    <p class="text-[13px] leading-[22px]" style="color: rgba(255,255,255,0.35); max-width: 280px;">
                        Bridging Filipino students to scholarship opportunities — one profile, every scholarship.
                    </p>
                </div>

                {{-- Platform --}}
                <div class="w-[160px]">
                    <p class="text-[10px] uppercase tracking-[2px] mb-[14px]" style="color: rgba(255,255,255,0.25);">Platform</p>
                    <ul class="space-y-[10px]">
                        <li><a href="{{ route('scholarships.index') }}" class="text-[13px] transition-colors hover:text-white/70" style="color: rgba(255,255,255,0.45);">Browse</a></li>
                        <li><a href="#" class="text-[13px] transition-colors hover:text-white/70" style="color: rgba(255,255,255,0.45);">How It Works</a></li>
                        <li><a href="#" class="text-[13px] transition-colors hover:text-white/70" style="color: rgba(255,255,255,0.45);">For Organizations</a></li>
                        <li><a href="#" class="text-[13px] transition-colors hover:text-white/70" style="color: rgba(255,255,255,0.45);">AI Matching</a></li>
                    </ul>
                </div>

                {{-- Account --}}
                <div class="w-[160px]">
                    <p class="text-[10px] uppercase tracking-[2px] mb-[14px]" style="color: rgba(255,255,255,0.25);">Account</p>
                    <ul class="space-y-[10px]">
                        <li><a href="{{ route('register') }}" class="text-[13px] transition-colors hover:text-white/70" style="color: rgba(255,255,255,0.45);">Sign Up</a></li>
                        <li><a href="{{ route('login') }}" class="text-[13px] transition-colors hover:text-white/70" style="color: rgba(255,255,255,0.45);">Log In</a></li>
                        <li><a href="#" class="text-[13px] transition-colors hover:text-white/70" style="color: rgba(255,255,255,0.45);">My Applications</a></li>
                        <li><a href="#" class="text-[13px] transition-colors hover:text-white/70" style="color: rgba(255,255,255,0.45);">Document Wallet</a></li>
                    </ul>
                </div>

                {{-- Legal --}}
                <div class="w-[160px]">
                    <p class="text-[10px] uppercase tracking-[2px] mb-[14px]" style="color: rgba(255,255,255,0.25);">Legal</p>
                    <ul class="space-y-[10px]">
                        <li><a href="#" class="text-[13px] transition-colors hover:text-white/70" style="color: rgba(255,255,255,0.45);">Privacy Policy</a></li>
                        <li><a href="#" class="text-[13px] transition-colors hover:text-white/70" style="color: rgba(255,255,255,0.45);">Terms of Service</a></li>
                        <li><a href="#" class="text-[13px] transition-colors hover:text-white/70" style="color: rgba(255,255,255,0.45);">Data Privacy Act</a></li>
                        <li><a href="#" class="text-[13px] transition-colors hover:text-white/70" style="color: rgba(255,255,255,0.45);">Contact</a></li>
                    </ul>
                </div>

            </div>
            <div class="pt-6">
                <p class="text-[12px]" style="color: rgba(255,255,255,0.2);">© 2025 ScholarLink. Philippines 🇵🇭</p>
            </div>
        </div>
    </footer>

</div>

@push('scripts')
<script>
function countdownTimer(deadline) {
    return {
        days: '00', hours: '00', minutes: '00', seconds: '00',
        startTimer() {
            this.update();
            setInterval(() => this.update(), 1000);
        },
        update() {
            const diff = new Date(deadline).getTime() - new Date().getTime();
            if (diff <= 0) {
                this.days = this.hours = this.minutes = this.seconds = '00';
                return;
            }
            this.days    = String(Math.floor(diff / 86400000)).padStart(2, '0');
            this.hours   = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
            this.minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
            this.seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
        }
    }
}
</script>
<script>
function countdownTimer(deadline) {
    return {
        days: '00', hours: '00', minutes: '00', seconds: '00',
        startTimer() {
            this.update();
            setInterval(() => this.update(), 1000);
        },
        update() {
            const diff = new Date(deadline).getTime() - new Date().getTime();
            if (diff <= 0) { this.days = this.hours = this.minutes = this.seconds = '00'; return; }
            this.days    = String(Math.floor(diff / 86400000)).padStart(2, '0');
            this.hours   = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
            this.minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
            this.seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
        }
    }
}
</script>
</body>
</html>
