<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider; // Ensure this class exists in the specified namespace
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {

            $this->validate($request, [
                'email'    => 'required|email',
                'password' => 'required|min:8|max:20|alpha_dash|regex:/^[A-Za-z0-9 ]+$/',
            ]);

            $credentials = $request->only('email', 'password');

            $staff = DB::table('staff')
                ->where('staff.email', $request->email)
                ->select('staff.roles', 'staff.email', 'staff.name as username', 'staff.isdeleted')
                ->first();

            if (!$staff) {
                return redirect()->route('admin.login')->with(['message' => 'No user found with this email.', 'type' => 'error']);
            }

            // Check if the user is marked as deleted
            if ($staff->isdeleted == 1) {
                return redirect()->route('admin.login')
                    ->with(['message' => 'This account has been removed. Please contact support.', 'type' => 'error']);
            }

            $roles = json_decode($staff->roles, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                die('Invalid roles data.');
            }

            // Fetch the matching role name
            $roleName = DB::table('roles')
                ->whereIn('id', $roles) // Match role ID with roles.id
                ->value('name');

            if (!$roleName) {
                die('No matching role found.');
            }

            // Combine staff data with the role name
            $staffData = [
                'roles' => $staff->roles,
                'email' => $staff->email,
                'username' => $staff->username,
                'role_name' => $roleName,
            ];

            if (Auth::guard('staff')->attempt($credentials)) {
                session(['staff_data' => $staffData]);
                session()->save();
                return redirect()->route('admin.home')->with(['message' => 'You are successfully logged in.', 'type' => 'success']);
            } else {
                toastr()->error('Email-Address and Password are wrong.');
                return redirect()->route('admin.login')->with(['message' => 'Invalid credentials.', 'type' => 'error']);
            }
        }
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return redirect()->route('admin.login')->with('success', 'You have been successfully logged out.');
    }
}
