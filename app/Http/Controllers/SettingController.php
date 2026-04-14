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
        $validated = $request->validate([
            'parish_name' => 'nullable|string|max:255',
            'parish_address' => 'nullable|string|max:500',
            'parish_contact' => 'nullable|string|max:255',
            'parish_email' => 'nullable|email|max:255',
            'gcash_number' => 'nullable|string|max:255',
            'gcash_name' => 'nullable|string|max:255',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('qr_code')) {
            $path = $request->file('qr_code')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'qr_code'], ['value' => $path]);
        }

        foreach ($validated as $key => $value) {
            if ($key !== 'qr_code') {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }

        return back()->with('success', 'Settings updated successfully!');
    }
}
