@extends('layouts.public')

@section('title', 'Terms and Conditions — ScholarLink')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-10 shadow-sm">
        <h1 class="font-serif text-4xl font-bold text-[#0F4C5C] mb-8">Terms and Conditions</h1>
        
        <div class="prose prose-teal max-w-none text-gray-600 leading-relaxed space-y-6">
            <p class="font-bold text-gray-800">Effective Date: January 1, 2025</p>
            
            <section>
                <h2 class="text-xl font-bold text-[#0F4C5C] mb-3">1. Acceptance of Terms</h2>
                <p>By accessing and using ScholarLink, you agree to be bound by these Terms and Conditions. If you do not agree, please refrain from using our services.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-[#0F4C5C] mb-3">2. Description of Service</h2>
                <p>ScholarLink is a platform that connects Filipino students with scholarship opportunities. We provide tools for scholarship discovery, application management, and document storage.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-[#0F4C5C] mb-3">3. User Obligations</h2>
                <ul class="list-disc pl-5 space-y-2">
                    <li>You must provide accurate and truthful information.</li>
                    <li>You are responsible for maintaining the confidentiality of your account credentials.</li>
                    <li>You agree not to use the platform for any fraudulent or illegal activities.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-[#0F4C5C] mb-3">4. Intellectual Property</h2>
                <p>All content, logos, and software on ScholarLink are the property of ScholarLink and are protected by intellectual property laws.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-[#0F4C5C] mb-3">5. Limitation of Liability</h2>
                <p>ScholarLink is not responsible for the ultimate decision of scholarship providers. We provide the connection but do not guarantee selection.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-[#0F4C5C] mb-3">6. Changes to Terms</h2>
                <p>We reserve the right to modify these terms at any time. Your continued use of the platform constitutes acceptance of updated terms.</p>
            </section>
        </div>

        <div class="mt-12 pt-8 border-t border-gray-100">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-[#0F4C5C] font-bold hover:underline">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
