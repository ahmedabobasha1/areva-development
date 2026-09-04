<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request, string $locale): RedirectResponse
    {
        app()->setLocale($locale);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:190'],
            'message' => ['nullable', 'string', 'max:5000'],
            'source_page' => ['nullable', 'string', 'max:190'],
        ]);

        ContactMessage::query()->create([
            ...$data,
            'locale' => $locale,
        ]);

        return back()->with('status', $locale === 'ar' ? 'تم إرسال رسالتك بنجاح.' : 'Your message has been sent.');
    }
}
