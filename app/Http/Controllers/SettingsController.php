<?php

namespace App\Http\Controllers;

use App\Models\UserSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $settings = $request->user()->getOrCreateSettings();
        return view('settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = $request->user()->getOrCreateSettings();

        $settings->update([
            'alarm_enabled' => $request->boolean('alarm_enabled'),
            'visual_warning' => $request->boolean('visual_warning'),
            'dev_mode' => $request->boolean('dev_mode'),
            'dark_mode' => $request->boolean('dark_mode'),
        ]);

        return response()->json(['status' => 'ok']);
    }
}
