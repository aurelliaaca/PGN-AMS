<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the profile page.
     */
    public function show()
    {
        return view('menu.profile');
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'perusahaan' => ['nullable', 'string', 'max:255'],
            'bagian' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'noktp' => ['nullable', 'string', 'max:50'],
            'mobile_number' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->perusahaan = $request->perusahaan;
        $user->bagian = $request->bagian;
        $user->region = $request->region;
        $user->noktp = $request->noktp;
        $user->mobile_number = $request->mobile_number;
        $user->alamat = $request->alamat;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.show')->with('success', 'Your profile has been updated successfully!');
    }


    /**
     * Resend email verification notification.
     */
    public function sendVerification(Request $request)
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return Redirect::route('profile.show');
        }

        $user->notify(new VerifyEmail);

        return Redirect::route('profile.show')->with('success', 'Verification email has been sent.');
    }

    /**
     * Upload the user's signature.Í
     */
    public function uploadSignature(Request $request)
    {
        $request->validate([
            'signature' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $user = Auth::user();

        if ($user->signature) {
            Storage::disk('public')->delete($user->signature);
        }

        $file = $request->file('signature');
        $extension = $file->getClientOriginalExtension();

        $filename = 'signature' . Str::slug($user->name) . '.' . $extension;

        $path = $file->storeAs('signatures', $filename, 'public');

        $user->signature = $path;
        $user->save();

        return back()->with('success', 'Signature uploaded successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();

        // Delete signature if exists
        if ($user->signature) {
            Storage::disk('public')->delete($user->signature);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
