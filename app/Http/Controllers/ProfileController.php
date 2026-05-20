<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\MonitoringSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile page with stats.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $sessions = $user->sessions()->where('status', 'completed');

        $totalDrives = $sessions->count();
        $totalHours = round($sessions->sum('duration_seconds') / 3600, 1);
        $totalAlerts = $sessions->sum('microsleep_count')
            + $sessions->sum('perclos_alerts')
            + $sessions->sum('yawn_count');
        $safeSessions = (clone $sessions)
            ->whereRaw('microsleep_count + perclos_alerts + yawn_count = 0')
            ->count();
        $safeRate = $totalDrives > 0 ? round(($safeSessions / $totalDrives) * 100, 1) : 0;

        return view('profile', compact(
            'user', 'totalDrives', 'totalHours', 'totalAlerts', 'safeRate'
        ));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $user = $request->user();
        $user->name = $request->name;

        if ($user->email !== $request->email) {
            $user->email = $request->email;
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile')->with('status', 'profile-updated');
    }

    /**
     * Upload profile picture.
     */
    public function uploadPicture(Request $request)
    {
        $request->validate([
            'profile_picture' => ['required', 'image', 'max:2048'],
        ]);

        $user = $request->user();

        // Delete old picture if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $path = $request->file('profile_picture')->store('avatars', 'public');
        $user->update(['profile_picture' => $path]);

        return Redirect::route('profile')->with('status', 'picture-updated');
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
