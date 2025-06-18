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
use App\Models\Region;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $regions = Region::pluck('nama_region');
        return view('menu.profile', compact('regions'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'perusahaan' => ['nullable', 'string', 'max:255'],
            'bagian' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'noktp' => ['required', 'digits:16', 'regex:/^[0-9]+$/'],
            'mobile_number' => ['required', 'string', 'max:15', 'regex:/^[0-9+\-\s]+$/'],
            'alamat' => ['nullable', 'string'],
        ], [
            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email sudah digunakan!',

            'mobile_number.required' => 'No Telepon wajib diisi!',
            'mobile_number.max' => 'No Telepon maksimal 15 karakter!',
            'mobile_number.regex' => 'Format No Telepon hanya boleh angka, spasi, +, dan -',

            'noktp.required' => 'No KTP wajib diisi!',
            'noktp.digits' => 'No KTP harus terdiri dari 16 digit angka!',
            'noktp.regex' => 'No KTP hanya boleh berisi angka!',
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

        return Redirect::route('profile.show')->with('success', 'Profil kamu berhasil diperbarui!');
    }

    public function sendVerification(Request $request)
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return Redirect::route('profile.show');
        }

        $user->notify(new VerifyEmail);

        return Redirect::route('profile.show')->with('success', 'Verification email has been sent.');
    }

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
