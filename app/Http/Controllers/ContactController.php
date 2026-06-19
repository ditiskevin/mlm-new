<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'min:10', 'max:4000'],
            // Honeypot — bots fill it, humans never see it.
            'website' => ['nullable', 'size:0'],
        ]);

        $contact = ContactMessage::create([
            'user_id' => $request->user()?->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'],
            'message' => $data['message'],
        ]);

        // Notify the inbox, but never let a mail hiccup lose the message.
        try {
            Mail::to(config('mail.contact_to'))->send(new ContactMessageReceived($contact));
        } catch (\Throwable $e) {
            Log::warning('Contact mail kon niet worden verzonden: '.$e->getMessage());
        }

        return back()->with('success', 'Bedankt voor je bericht! We nemen zo snel mogelijk contact met je op. 💛');
    }
}
