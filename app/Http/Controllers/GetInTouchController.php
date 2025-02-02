<?php

namespace App\Http\Controllers;

use App\Models\GetInTouch;
use Illuminate\Http\Request;

class GetInTouchController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        GetInTouch::create($validated);

        return redirect()->back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }
}
