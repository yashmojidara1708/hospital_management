<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class doctorPasswordController extends Controller
{
    //
    public function index()
    {
        return view('doctor.dashboard.ChangePassword');
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'oldpassword' => 'required|min:6',
            'newpassword' => 'required|min:8',
            'confirmpassword' => 'required|same:newpassword',
        ]);

        $doctor = Auth::guard('doctor')->user(); // Ensure doctor is authenticated

        if (!Hash::check($request->oldpassword, $doctor->password)) {
            return response()->json(['status' => 'error', 'message' => 'Old password is incorrect.'], 400);
        }

        $doctor->update([
            'password' => Hash::make($request->newpassword)
        ]);

        return response()->json(['status' => 'success', 'message' => 'Password changed successfully.']);
    }
}

