<?php

namespace App\Http\Controllers;

use App\Http\Requests\WaitlistSignupRequest;
use App\Models\WaitlistSignup;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class WaitlistSignupController extends Controller
{
    /**
     * Store a new waitlist signup.
     */
    public function store(WaitlistSignupRequest $request): RedirectResponse
    {
        WaitlistSignup::create($request->validated());

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => "You're on the list — we'll be in touch.",
        ]);

        return back();
    }
}
