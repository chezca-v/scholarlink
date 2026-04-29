@extends('layouts.applicant')

@section('title', 'Data Privacy Act Compliance — ScholarLink')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-10 shadow-sm">
        <h1 class="font-serif text-4xl font-bold text-[#0F4C5C] mb-8">Data Privacy Act (RA 10173)</h1>
        
        <div class="prose prose-teal max-w-none text-gray-600 leading-relaxed space-y-6">
            <p class="font-bold text-gray-800">Compliance Statement</p>
            
            <p>ScholarLink is fully committed to complying with Republic Act No. 10173, also known as the <strong>Data Privacy Act of 2012 (DPA)</strong>, and its Implementing Rules and Regulations.</p>

            <section>
                <h2 class="text-xl font-bold text-[#0F4C5C] mb-3">Our Commitment</h2>
                <p>We respect your right to privacy and aim to comply with the requirements of all relevant privacy and data protection laws of the Philippines.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-[#0F4C5C] mb-3">Data Subject Rights</h2>
                <p>As a data subject, you have the following rights under the DPA:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Right to be informed:</strong> You have the right to know how your data is being processed.</li>
                    <li><strong>Right to access:</strong> You can request a copy of your personal data held by us.</li>
                    <li><strong>Right to rectification:</strong> You can correct inaccuracies in your data.</li>
                    <li><strong>Right to erasure or blocking:</strong> You can request the removal of your data from our systems.</li>
                    <li><strong>Right to data portability:</strong> You can request your data in a structured, commonly used format.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-[#0F4C5C] mb-3">Data Protection Officer</h2>
                <p>If you have any concerns regarding your data privacy, you may contact our Data Protection Officer at:</p>
                <p class="font-bold">privacy@scholarlink.ph</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-[#0F4C5C] mb-3">Consent</h2>
                <p>By creating an account and using our platform, you signify your explicit consent to the collection and processing of your personal information as described in our Privacy Policy.</p>
            </section>
        </div>

        <div class="mt-12 pt-8 border-t border-gray-100">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-[#0F4C5C] font-bold hover:underline">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
