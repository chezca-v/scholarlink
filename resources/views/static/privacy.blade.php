@extends('layouts.public')

@section('title', 'Privacy Policy — ScholarLink')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-10 shadow-sm">
        <h1 class="font-serif text-4xl font-bold text-[#0F4C5C] mb-8">Privacy Policy</h1>
        
        <div class="prose prose-teal max-w-none text-gray-600 leading-relaxed space-y-6">
            <p class="font-bold text-gray-800">Last Updated: January 1, 2025</p>
            
            <p>At ScholarLink, we take your privacy seriously. This policy explains how we collect, use, and protect your personal information.</p>

            <section>
                <h2 class="text-xl font-bold text-[#0F4C5C] mb-3">1. Information We Collect</h2>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Personal Details: Name, email, date of birth.</li>
                    <li>Academic Data: GWA, university, course program.</li>
                    <li>Documents: Transcripts, IDs, certificates uploaded to your wallet.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-[#0F4C5C] mb-3">2. How We Use Your Data</h2>
                <p>We use your information to match you with relevant scholarships, process your applications, and improve our AI recommendation engine.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-[#0F4C5C] mb-3">3. Data Sharing</h2>
                <p>Your data is only shared with scholarship providers when you explicitly submit an application to them. We do not sell your personal data to third parties.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-[#0F4C5C] mb-3">4. Data Security</h2>
                <p>We implement industry-standard encryption and security measures to protect your documents and personal information from unauthorized access.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-[#0F4C5C] mb-3">5. Your Rights</h2>
                <p>You have the right to access, correct, or delete your data at any time through your profile settings or by contacting our support team.</p>
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
