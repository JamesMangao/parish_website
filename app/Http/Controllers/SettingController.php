<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        if ($request->hasFile('priest_image') && !$request->file('priest_image')->isValid()) {
            return back()->withErrors(['priest_image' => 'Upload Error Code: ' . $request->file('priest_image')->getError() . ' - ' . $request->file('priest_image')->getErrorMessage()]);
        }

        $validated = $request->validate([
            'parish_name' => 'nullable|string|max:255',
            'parish_address' => 'nullable|string|max:500',
            'parish_contact' => 'nullable|string|max:255',
            'parish_email' => 'nullable|email|max:255',
            'gcash_number' => 'nullable|string|max:255',
            'gcash_name' => 'nullable|string|max:255',
            'qr_code' => 'nullable|image|max:10240',
            'priest_image' => 'nullable|image|max:10240',
            'priest_name' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('qr_code')) {
            $path = $request->file('qr_code')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'qr_code'], ['value' => $path]);
        }

        if ($request->hasFile('priest_image')) {
            $path = $request->file('priest_image')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'priest_image'], ['value' => $path]);
        } elseif ($request->file('priest_image') !== null && !$request->file('priest_image')->isValid()) {
            return back()->withErrors(['priest_image' => 'File upload failed: ' . $request->file('priest_image')->getErrorMessage()]);
        }

        foreach ($validated as $key => $value) {
            if (!in_array($key, ['qr_code', 'priest_image'])) {
                if ($value !== null) {
                    Setting::updateOrCreate(['key' => $key], ['value' => $value]);
                }
            }
        }

        return back()->with('success', 'Settings updated successfully!');
    }
}
