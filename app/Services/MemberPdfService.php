<?php

namespace App\Services;

use App\Models\Member;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class MemberPdfService
{
    public function generateMemberList(Collection $members, array $options = []): \Barryvdh\DomPDF\PDF
    {
        $data = [
            'members' => $members,
            'title' => $options['title'] ?? 'Member Directory',
            'generated_at' => now(),
            'total_count' => $members->count(),
            'church_name' => config('app.church_name', 'His Kingdom Church'),
            'church_address' => config('app.church_address', 'Your Church Address'),
            'church_phone' => config('app.church_phone', '+260 XXX XXX XXX'),
            'church_email' => config('app.church_email', 'info@hkc.co.zm'),
            'logo_path' => public_path('images/church-logo.png'),
            'include_photos' => $options['include_photos'] ?? false,
            'include_contact' => $options['include_contact'] ?? true,
            'include_spiritual' => $options['include_spiritual'] ?? true,
        ];

        return PDF::loadView('pdf.member-list', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
            ]);
    }

    public function generateMemberCards(Collection $members, array $options = []): \Barryvdh\DomPDF\PDF
    {
        $data = [
            'members' => $members->chunk(4), // 4 cards per page
            'generated_at' => now(),
            'church_name' => config('app.church_name', 'His Kingdom Church'),
            'card_style' => $options['card_style'] ?? 'professional', // professional, executive, simple
            'include_photos' => $options['include_photos'] ?? false,
            'include_contact' => $options['include_contact'] ?? true,
        ];

        $template = $options['card_style'] === 'simple' ? 'pdf.member-cards-simple' : 'pdf.member-cards';

        return PDF::loadView($template, $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_top' => 8,
                'margin_bottom' => 8,
                'margin_left' => 8,
                'margin_right' => 8,
            ]);
    }

    public function generateMemberBadges(Collection $members): \Barryvdh\DomPDF\PDF
    {
        $data = [
            'members' => $members->chunk(6), // 6 badges per page
            'generated_at' => now(),
            'church_name' => config('app.church_name', 'His Kingdom Church'),
        ];

        return PDF::loadView('pdf.member-badges', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_top' => 5,
                'margin_bottom' => 5,
                'margin_left' => 5,
                'margin_right' => 5,
            ]);
    }

    public function generateMemberCertificates(Collection $members, array $options = []): \Barryvdh\DomPDF\PDF
    {
        $data = [
            'members' => $members,
            'generated_at' => now(),
            'church_name' => config('app.church_name', 'His Kingdom Church'),
            'certificate_type' => $options['certificate_type'] ?? 'membership',
            'pastor_name' => $options['pastor_name'] ?? 'Pastor Name',
            'church_address' => config('app.church_address', 'Your Church Address'),
        ];

        return PDF::loadView('pdf.member-certificates', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'chroot' => public_path(),
                'enable_font_subsetting' => false,
                'isPhpEnabled' => true,
            ]);
    }
}
