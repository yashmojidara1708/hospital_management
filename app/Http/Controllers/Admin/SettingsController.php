<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $hospitalName = GlobalHelper::getSetting('hospital_name', 'Default Hospital');
        $countries = GlobalHelper::getAllCountries();
        return view('admin.settings.index', compact('settings', 'hospitalName', 'countries'));    
    }

    public function update(Request $request)
    {
        $existingLogo = Setting::where('key', 'company_logo')->value('value');
        $existingFavicon = Setting::where('key', 'favicon')->value('value');
        $data = [];

        if ($request->hasFile('company_logo')) {
            if ($existingLogo) {
                $oldLogoPath = public_path('uploads/' . $existingLogo);
                if (file_exists($oldLogoPath)) {
                    unlink($oldLogoPath);
                }
            }

            $companyLogo = $request->file('company_logo');
            $companyLogoName = 'company_logo_' . time() . '.' . $companyLogo->getClientOriginalExtension();
            $companyLogo->move(public_path('uploads'), $companyLogoName);
            $data['company_logo'] = $companyLogoName;
        }

        if ($request->hasFile('favicon')) {
            if ($existingFavicon) {
                $oldFaviconPath = public_path('uploads/' . $existingFavicon);
                if (file_exists($oldFaviconPath)) {
                    unlink($oldFaviconPath);
                }
            }

            $favicon = $request->file('favicon');
            $faviconName = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path('uploads'), $faviconName);
            $data['favicon'] = $faviconName; 
        }

        $otherSettings = $request->except('_token', 'company_logo', 'favicon');
        foreach ($otherSettings as $key => $value) {
            if (!empty($value)) {
                $data[$key] = $value;
            }
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json(['success' => true, 'message' => 'Settings updated successfully!']);
    }
}
