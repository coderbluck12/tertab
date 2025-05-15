<?php

namespace App\Http\Controllers;

use App\Models\PlatformSetting;
use Illuminate\Http\Request;

class PlatfromSettingsController extends Controller
{
    public function index()
    {
        // Retrieve the platform settings, if none exists, create default settings
        $settings = PlatformSetting::first() ?? PlatformSetting::create(['reference_request_price' => 0.00]);

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'reference_request_price' => 'required|numeric|min:0',
        ]);

        $settings = PlatformSetting::first() ?? PlatformSetting::create(['reference_request_price' => 0.00]);

        $settings->update([
            'reference_request_price' => $request->input('reference_request_price'),
        ]);

//        return view('admin.settings', compact('request'))->with('success', 'Settings updated successfully.');
        return redirect()->route('admin.platform.settings')->with('success', 'Settings updated successfully.');
    }
}
