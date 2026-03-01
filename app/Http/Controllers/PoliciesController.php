<?php

namespace App\Http\Controllers;

use App\Models\Terms;
use App\Models\PrivacyPolicy;

class PoliciesController extends Controller
{
    /**
     * Display the terms and conditions page.
     */
    public function terms()
    {
        $terms = Terms::where('is_active', true)->first();

        return view('pages.terms', compact('terms'));
    }

    /**
     * Display the privacy policy page.
     */
    public function privacy()
    {
        $privacyPolicy = PrivacyPolicy::where('is_active', true)->first();

        return view('pages.privacy', compact('privacyPolicy'));
    }
}
