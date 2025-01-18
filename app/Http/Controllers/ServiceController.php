<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(['preacher'])
            ->whereNotNull(['message_title', 'audio_recording'])
            ->orderBy('date', 'desc')
            ->take(6)  // Show last 6 services
            ->get();

        return view('services', compact('services'));
    }

    public function archive()
    {
        $services = Service::with(['preacher'])
            ->whereNotNull(['message_title', 'audio_recording'])
            ->orderBy('date', 'desc')
            ->paginate(12);  // Show 12 services per page in archive

        return view('services.archive', compact('services'));
    }

    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }
}