<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $rules = [
            'hospital_name' => 'required|string|max:255',
            'address' => 'required|string',
            'country' => 'required|string',
            'state' => 'required|string',
            'city' => 'required|string',
            'zipcode' => 'required|digits:6',
            'phone_number' => 'required|string',
            'email' => 'required|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:512',
        ];

        // Define custom error messages
        $messages = [
            'hospital_name.required' => 'Please enter the hospital name.',
            'address.required' => 'Please enter the address.',
            'country.required' => 'Please select a country.',
            'state.required' => 'Please enter the state.',
            'city.required' => 'Please enter the city.',
            'zipcode.required' => 'Please enter the ZIP code.',
            'zipcode.digits' => 'ZIP code must be 6 digits.',
            'phone_number.required' => 'Please enter the phone number.',
            'email.required' => 'Please enter the email address.',
            'company_logo.image' => 'The company logo must be an image file.',
            'company_logo.mimes' => 'The company logo must be a JPEG, PNG, JPG, or GIF file.',
            'favicon.image' => 'The favicon must be an image file.',
            'favicon.mimes' => 'The favicon must be a JPEG, PNG, JPG, or GIF file.',
        ];

        // Run validation
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'errors' => $validator->errors()]);
        }

        // Proceed with storing settings...
        $settingsData = $request->except('_token', 'company_logo', 'favicon');

        // Handle multiple phone numbers
        if ($request->has('phone_number')) {
            $phoneNumbers = explode(',', $request->input('phone_number'));
            $phoneNumbers = array_map('trim', $phoneNumbers); // Trim whitespace
            $settingsData['phone_number'] = implode(',', $phoneNumbers); // Store as comma-separated string
        }

        // Handle multiple email addresses
        if ($request->has('email')) {
            $emails = explode(',', $request->input('email'));
            $emails = array_map('trim', $emails); // Trim whitespace
            $settingsData['email'] = implode(',', $emails); // Store as comma-separated string
        }

        // Handle file uploads
        if ($request->hasFile('company_logo')) {
            // Delete the old company logo if it exists
            $oldLogo = Setting::where('key', 'company_logo')->value('value');
            if ($oldLogo && file_exists(public_path('uploads/' . $oldLogo))) {
                unlink(public_path('uploads/' . $oldLogo)); // Delete the old file
            }

            // Save the new company logo
            $logo = $request->file('company_logo');
            $logoName = 'company_logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads'), $logoName);
            $settingsData['company_logo'] = $logoName;
        }

        if ($request->hasFile('favicon')) {
            // Delete the old favicon if it exists
            $oldFavicon = Setting::where('key', 'favicon')->value('value');
            if ($oldFavicon && file_exists(public_path('uploads/' . $oldFavicon))) {
                unlink(public_path('uploads/' . $oldFavicon)); // Delete the old file
            }

            // Save the new favicon
            $favicon = $request->file('favicon');
            $faviconName = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path('uploads'), $faviconName);
            $settingsData['favicon'] = $faviconName;
        }

        // Save settings in DB
        foreach ($settingsData as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return response()->json(['status' => 1, 'message' => 'Settings updated successfully!']);
    }
    
}
