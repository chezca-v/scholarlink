<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
            // ── USER 1 — Maria Santos ─────────────────────────
            ['user_id' => 1, 'document_type' => 'PSA Birth Certificate',              'file_url' => 'storage/app/documents/user_1/psa_birth_certificate.pdf',   'status' => 'verified', 'expiry_date' => null,         'verified_by' => 11, 'created_at' => '2026-01-08'],
            ['user_id' => 1, 'document_type' => 'Barangay Certificate of Indigency',  'file_url' => 'storage/app/documents/user_1/barangay_certificate.pdf',     'status' => 'pending',  'expiry_date' => '2027-01-01', 'verified_by' => null, 'created_at' => '2026-01-08'],
            ['user_id' => 1, 'document_type' => 'Latest Report Card / TOR',           'file_url' => 'storage/app/documents/user_1/report_card_tor.pdf',          'status' => 'rejected', 'expiry_date' => '2027-01-01', 'verified_by' => null, 'created_at' => '2026-01-08'],
            ['user_id' => 1, 'document_type' => 'Certificate of Good Moral Character','file_url' => 'storage/app/documents/user_1/good_moral_certificate.pdf',   'status' => 'verified', 'expiry_date' => '2027-01-01', 'verified_by' => 11,   'created_at' => '2026-01-08'],

            // ── USER 2 — Juan Dela Cruz ───────────────────────
            ['user_id' => 2, 'document_type' => 'PSA Birth Certificate',              'file_url' => 'storage/app/documents/user_2/psa_birth_certificate.pdf',   'status' => 'verified', 'expiry_date' => null,         'verified_by' => 11,   'created_at' => '2026-01-06'],
            ['user_id' => 2, 'document_type' => 'Barangay Certificate of Indigency',  'file_url' => 'storage/app/documents/user_2/barangay_certificate.pdf',     'status' => 'pending',  'expiry_date' => '2027-01-01', 'verified_by' => null, 'created_at' => '2026-01-06'],
            ['user_id' => 2, 'document_type' => 'Latest Report Card / TOR',           'file_url' => 'storage/app/documents/user_2/report_card_tor.pdf',          'status' => 'rejected', 'expiry_date' => '2027-01-01', 'verified_by' => null, 'created_at' => '2026-01-06'],
            ['user_id' => 2, 'document_type' => 'Certificate of Good Moral Character','file_url' => 'storage/app/documents/user_2/good_moral_certificate.pdf',   'status' => 'verified', 'expiry_date' => '2027-01-01', 'verified_by' => 11,   'created_at' => '2026-01-06'],

            // ── USER 3 — Angela Reyes ─────────────────────────
            ['user_id' => 3, 'document_type' => 'PSA Birth Certificate',              'file_url' => 'storage/app/documents/user_3/psa_birth_certificate.pdf',   'status' => 'verified', 'expiry_date' => null,         'verified_by' => 11,   'created_at' => '2026-01-07'],
            ['user_id' => 3, 'document_type' => 'Barangay Certificate of Indigency',  'file_url' => 'storage/app/documents/user_3/barangay_certificate.pdf',     'status' => 'verified', 'expiry_date' => '2027-01-01', 'verified_by' => 11,   'created_at' => '2026-01-07'],
            ['user_id' => 3, 'document_type' => 'Latest Report Card / TOR',           'file_url' => 'storage/app/documents/user_3/report_card_tor.pdf',          'status' => 'verified', 'expiry_date' => '2027-01-01', 'verified_by' => 11,   'created_at' => '2026-01-07'],
            ['user_id' => 3, 'document_type' => 'Certificate of Good Moral Character','file_url' => 'storage/app/documents/user_3/good_moral_certificate.pdf',   'status' => 'pending',  'expiry_date' => '2027-01-01', 'verified_by' => null, 'created_at' => '2026-01-07'],

            // ── USER 4 — Kevin Cruz ───────────────────────────
            ['user_id' => 4, 'document_type' => 'PSA Birth Certificate',              'file_url' => 'storage/app/documents/user_4/psa_birth_certificate.pdf',   'status' => 'verified', 'expiry_date' => null,         'verified_by' => 11,   'created_at' => '2026-01-03'],
            ['user_id' => 4, 'document_type' => 'Barangay Certificate of Indigency',  'file_url' => 'storage/app/documents/user_4/barangay_certificate.pdf',     'status' => 'pending',  'expiry_date' => '2027-01-01', 'verified_by' => null, 'created_at' => '2026-01-03'],
            ['user_id' => 4, 'document_type' => 'Latest Report Card / TOR',           'file_url' => 'storage/app/documents/user_4/report_card_tor.pdf',          'status' => 'pending',  'expiry_date' => '2027-01-01', 'verified_by' => null, 'created_at' => '2026-01-03'],
            ['user_id' => 4, 'document_type' => 'Certificate of Good Moral Character','file_url' => 'storage/app/documents/user_4/good_moral_certificate.pdf',   'status' => 'rejected', 'expiry_date' => '2027-01-01', 'verified_by' => null, 'created_at' => '2026-01-03'],

            // ── USER 5 — Liz Mendoza ──────────────────────────
            ['user_id' => 5, 'document_type' => 'PSA Birth Certificate',              'file_url' => 'storage/app/documents/user_5/psa_birth_certificate.pdf',   'status' => 'verified', 'expiry_date' => null,         'verified_by' => 11,   'created_at' => '2026-01-05'],
            ['user_id' => 5, 'document_type' => 'Barangay Certificate of Indigency',  'file_url' => 'storage/app/documents/user_5/barangay_certificate.pdf',     'status' => 'verified', 'expiry_date' => '2027-01-01', 'verified_by' => 11,   'created_at' => '2026-01-05'],
            ['user_id' => 5, 'document_type' => 'Latest Report Card / TOR',           'file_url' => 'storage/app/documents/user_5/report_card_tor.pdf',          'status' => 'verified', 'expiry_date' => '2027-01-01', 'verified_by' => 11,   'created_at' => '2026-01-05'],
            ['user_id' => 5, 'document_type' => 'Certificate of Good Moral Character','file_url' => 'storage/app/documents/user_5/good_moral_certificate.pdf',   'status' => 'verified', 'expiry_date' => '2027-01-01', 'verified_by' => 11,   'created_at' => '2026-01-05'],

            // ── USER 6 — Mark Villanueva ──────────────────────
            ['user_id' => 6, 'document_type' => 'PSA Birth Certificate',              'file_url' => 'storage/app/documents/user_6/psa_birth_certificate.pdf',   'status' => 'verified', 'expiry_date' => null,         'verified_by' => 11,   'created_at' => '2026-01-12'],
            ['user_id' => 6, 'document_type' => 'Barangay Certificate of Indigency',  'file_url' => 'storage/app/documents/user_6/barangay_certificate.pdf',     'status' => 'pending',  'expiry_date' => '2027-01-01', 'verified_by' => null, 'created_at' => '2026-01-12'],
            ['user_id' => 6, 'document_type' => 'Latest Report Card / TOR',           'file_url' => 'storage/app/documents/user_6/report_card_tor.pdf',          'status' => 'pending',  'expiry_date' => '2027-01-01', 'verified_by' => null, 'created_at' => '2026-01-12'],
            ['user_id' => 6, 'document_type' => 'Certificate of Good Moral Character','file_url' => 'storage/app/documents/user_6/good_moral_certificate.pdf',   'status' => 'rejected', 'expiry_date' => '2027-01-01', 'verified_by' => null, 'created_at' => '2026-01-12'],

            // ── USER 7 — Nicole Garcia ────────────────────────
            ['user_id' => 7, 'document_type' => 'PSA Birth Certificate',              'file_url' => 'storage/app/documents/user_7/psa_birth_certificate.pdf',   'status' => 'verified', 'expiry_date' => null,         'verified_by' => 11,   'created_at' => '2026-01-04'],
            ['user_id' => 7, 'document_type' => 'Barangay Certificate of Indigency',  'file_url' => 'storage/app/documents/user_7/barangay_certificate.pdf',     'status' => 'verified', 'expiry_date' => '2027-01-01', 'verified_by' => 11,   'created_at' => '2026-01-04'],
            ['user_id' => 7, 'document_type' => 'Latest Report Card / TOR',           'file_url' => 'storage/app/documents/user_7/report_card_tor.pdf',          'status' => 'pending',  'expiry_date' => '2027-01-01', 'verified_by' => null, 'created_at' => '2026-01-04'],
            ['user_id' => 7, 'document_type' => 'Certificate of Good Moral Character','file_url' => 'storage/app/documents/user_7/good_moral_certificate.pdf',   'status' => 'pending',  'expiry_date' => '2027-01-01', 'verified_by' => null, 'created_at' => '2026-01-04'],

            // ── USER 8 — Joshua Lim ───────────────────────────
            ['user_id' => 8, 'document_type' => 'PSA Birth Certificate',              'file_url' => 'storage/app/documents/user_8/psa_birth_certificate.pdf',   'status' => 'verified', 'expiry_date' => null,         'verified_by' => 11,   'created_at' => '2026-01-09'],
            ['user_id' => 8, 'document_type' => 'Barangay Certificate of Indigency',  'file_url' => 'storage/app/documents/user_8/barangay_certificate.pdf',     'status' => 'verified', 'expiry_date' => '2027-01-01', 'verified_by' => 11,   'created_at' => '2026-01-09'],
            ['user_id' => 8, 'document_type' => 'Latest Report Card / TOR',           'file_url' => 'storage/app/documents/user_8/report_card_tor.pdf',          'status' => 'verified', 'expiry_date' => '2027-01-01', 'verified_by' => 11,   'created_at' => '2026-01-09'],
            ['user_id' => 8, 'document_type' => 'Certificate of Good Moral Character','file_url' => 'storage/app/documents/user_8/good_moral_certificate.pdf',   'status' => 'verified', 'expiry_date' => '2027-01-01', 'verified_by' => 11,   'created_at' => '2026-01-09'],

            // ── USER 9 — Fatima Hassan ────────────────────────
            ['user_id' => 9, 'document_type' => 'PSA Birth Certificate',              'file_url' => 'storage/app/documents/user_9/psa_birth_certificate.pdf',   'status' => 'verified', 'expiry_date' => null,         'verified_by' => 11,   'created_at' => '2026-01-02'],
            ['user_id' => 9, 'document_type' => 'Barangay Certificate of Indigency',  'file_url' => 'storage/app/documents/user_9/barangay_certificate.pdf',     'status' => 'pending',  'expiry_date' => '2027-01-01', 'verified_by' => null, 'created_at' => '2026-01-02'],
            ['user_id' => 9, 'document_type' => 'Latest Report Card / TOR',           'file_url' => 'storage/app/documents/user_9/report_card_tor.pdf',          'status' => 'rejected', 'expiry_date' => '2027-01-01', 'verified_by' => null, 'created_at' => '2026-01-02'],
            ['user_id' => 9, 'document_type' => 'Certificate of Good Moral Character','file_url' => 'storage/app/documents/user_9/good_moral_certificate.pdf',   'status' => 'pending',  'expiry_date' => '2027-01-01', 'verified_by' => null, 'created_at' => '2026-01-02'],

            // ── USER 10 — Alex Rivera ─────────────────────────
            ['user_id' => 10, 'document_type' => 'PSA Birth Certificate',              'file_url' => 'storage/app/documents/user_10/psa_birth_certificate.pdf',   'status' => 'verified', 'expiry_date' => null,         'verified_by' => 11,   'created_at' => '2026-01-01'],
            ['user_id' => 10, 'document_type' => 'Barangay Certificate of Indigency',  'file_url' => 'storage/app/documents/user_10/barangay_certificate.pdf',     'status' => 'verified', 'expiry_date' => '2027-01-01', 'verified_by' => 11,   'created_at' => '2026-01-01'],
            ['user_id' => 10, 'document_type' => 'Latest Report Card / TOR',           'file_url' => 'storage/app/documents/user_10/report_card_tor.pdf',          'status' => 'verified', 'expiry_date' => '2027-01-01', 'verified_by' => 11,   'created_at' => '2026-01-01'],
            ['user_id' => 10, 'document_type' => 'Certificate of Good Moral Character','file_url' => 'storage/app/documents/user_10/good_moral_certificate.pdf',   'status' => 'verified', 'expiry_date' => '2027-01-01', 'verified_by' => 11,   'created_at' => '2026-01-01'],
        ];

        foreach ($documents as $document) {
            Document::create($document);
        }
    }
}
