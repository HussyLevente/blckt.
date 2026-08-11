<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        // Honeypot: only bots fill this hidden field. Pretend it worked so they don't adapt.
        if (filled($request->input('website'))) {
            return $this->respondSuccess($request);
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'budget' => ['nullable', 'numeric', 'min:0', 'max:999999999'],
            'message' => ['required', 'string', 'max:5000'],
        ], [
            'full_name.required' => __('Please enter your name.'),
            'full_name.max' => __('That name is too long.'),
            'email.required' => __('Please enter your email address.'),
            'email.email' => __('That doesn’t look like a valid email address.'),
            'email.max' => __('That email address is too long.'),
            'budget.numeric' => __('Budget should be a number.'),
            'budget.min' => __('Budget can’t be negative.'),
            'message.required' => __('Please tell me a bit about your project.'),
            'message.max' => __('That message is a little long — please keep it under 5000 characters.'),
        ]);

        try {
            Mail::to(config('mail.contact_to'))->send(new ContactFormMail([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'budget' => $validated['budget'] ?? null,
                'message' => $validated['message'],
                'locale' => app()->getLocale(),
                'source_page' => $request->headers->get('referer', $request->url()),
                'submitted_at' => now()->format('Y-m-d H:i'),
            ]));
        } catch (\Throwable $e) {
            report($e);

            $error = __('Something went wrong sending your message. Please email me directly at blckt.websites@gmail.com.');

            if ($request->expectsJson()) {
                return response()->json(['message' => $error], 500);
            }

            return back()->withInput()->with('contact_error', $error);
        }

        return $this->respondSuccess($request);
    }

    private function respondSuccess(Request $request): RedirectResponse|JsonResponse
    {
        $message = __('Thanks — your message is on its way. I’ll get back to you within 24 hours.');

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        return back()->with('contact_success', $message);
    }
}
