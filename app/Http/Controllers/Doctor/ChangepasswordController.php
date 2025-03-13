<?php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ChangepasswordController extends Controller
{
    //
    public function changepassword()
    {
        return view('doctor.dashboard.ChangePassword');
    }
    public function doctorUpdatePassword(Request $request)
    {
        $post = $request->post();
        $hid = isset($post['hid']) ? intval($post['hid']) : null;
        $request->validate([
            'oldpassword' => 'required',
            'newpassword' => 'required|min:8',
            'confirmpassword' => 'required|same:newpassword',
        ],
        $messages = [
            'oldpassword.required' => 'This field is required',
            'newpassword.required' => 'This field is required',
            'newpassword.min' => 'Password must be at least 8 characters long',
           // 'newpassword.different' => 'New password cannot be the same as the old password.',
            'confirmpassword.required' => 'This field is required',
            'confirmpassword.same' => 'Confirm password must match the new password',
        ]);
        $doctor = DB::table('doctors')->where('id', Auth::id())->first();
       // dd($staff);
    if (!$doctor) {
        return response()->json(['status' => 0, 'message' => 'User not found.']);
    }

    if (!Hash::check($request->oldpassword, $doctor->password)) {
        return response()->json(['status' => 0, 'message' => 'The current password is incorrect.']);
    }
     // Ensure old password and new password are not the same
     if (Hash::check($request->newpassword, $doctor->password)) {
        return response()->json(['status' => 0, 'message' => 'New password cannot be the same as the old password.']);
    }

    DB::table('doctors')->where('id', Auth::id())->update([
        'password' => Hash::make($request->newpassword),
    ]);

    return response()->json(['status' => 1, 'message' => 'Password changed successfully.']);

    }
}
