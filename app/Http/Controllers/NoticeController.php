<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Jorenvh\Share\ShareFacade;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::where('is_active', true)
                         ->orderBy('date', 'desc')
                         ->paginate(10);

        return view('notices.index', compact('notices'));
    }

    public function show($id)
    {
        $notice = Notice::findOrFail($id);

        // Increment view count
        if (!session()->has('viewed_notice_' . $id)) {
            $notice->incrementViewCount();
            session()->put('viewed_notice_' . $id, true);
        }

        // Generate share links for social media
        $shareLinks = $this->generateShareLinks($notice);

        return view('notices.show', compact('notice', 'shareLinks'));
    }

    private function generateShareLinks($notice)
    {
        // Make sure you have jorenvanhocht/laravel-share package installed
        // composer require jorenvanhocht/laravel-share

        $title = urlencode($notice->title);
        $url = route('notices.show', $notice->id);

        return [
            'whatsapp' => 'https://wa.me/?text=' . $title . ' ' . $url,
            'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
        ];
    }
}
