<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Use admin view if user is admin
        if ($request->user()->role === 'admin') {
            return view('admin.profile.edit', [
                'user' => $request->user(),
            ]);
        }
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        Log::debug('ProfileController@update called', [
            'user_id' => $request->user()->id,
            'request_data' => $request->all(),
            'files' => $request->files->all(),
        ]);

        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle company_logo upload
        if ($request->hasFile('company_logo')) {
            $logo = $request->file('company_logo');
            $logoPath = $logo->store('company_logos', 'public');
            $user->company_logo = $logoPath;
        }

        // Handle user_image upload
        if ($request->hasFile('user_image')) {
            $image = $request->file('user_image');
            $imagePath = $image->store('user_images', 'public');
            $user->user_image = $imagePath;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
