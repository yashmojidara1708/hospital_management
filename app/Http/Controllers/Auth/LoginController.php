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
        if (Auth::guard('staff')->check()) {
            return redirect()->route('admin.home');
        }

        if (Auth::guard('doctor')->check()) {
            return redirect()->route('doctor.home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {

            $this->validate($request, [
                'email' => 'required|email',
                'password' => [
                    'required',
                    'min:8',
                    'max:20',
                    // 'regex:/^[A-Za-z0-9-_]+$/'
                ],
            ], [
                'email.required' => 'Email is required.',
                'email.email' => 'Please enter a valid email address.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.max' => 'Password must not exceed 20 characters.',
                // 'password.regex' => 'Password must contain only letters, numbers, hyphens, and underscores.',
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
                ->pluck('name');

            $roleNamesString = implode(', ', $roleName->toArray());

            if (!$roleName) {
                die('No matching role found.');
            }

            // Combine staff data with the role name
            $staffData = [
                'roles' => $staff->roles,
                'email' => $staff->email,
                'username' => $staff->username,
                'role_name' => $roleNamesString,
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
    public function doctorloginlogin(Request $request)
    {
        if ($request->isMethod('post')) {

            $this->validate($request, [
                'email' => 'required|email',
                'password' => [
                    'required',
                    'min:8',
                    'max:20',
                    // 'regex:/^[A-Za-z0-9-_]+$/'
                ],
            ], [
                'email.required' => 'Email is required.',
                'email.email' => 'Please enter a valid email address.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.max' => 'Password must not exceed 20 characters.',
                // 'password.regex' => 'Password must contain only letters, numbers, hyphens, and underscores.',
            ]);


            $credentials = $request->only('email', 'password');

            $Doctor = DB::table('doctors')
                ->where('doctors.email', $request->email)
                ->select('*')
                ->first();

            if (!$Doctor) {
                return redirect()->route('admin.login')->with(['message' => 'No doctor found with this email.', 'type' => 'error']);
            }

            // Check if the user is marked as deleted
            if ($Doctor->isdeleted == 1) {
                return redirect()->route('admin.login')
                    ->with(['message' => 'This doctor has been removed. Please contact support.', 'type' => 'error']);
            }

            // Combine staff data with the role name
            $DoctorData = [
                'role' => $Doctor->role,
                'id' => $Doctor->id,
                'email' => $Doctor->email,
                'name' => $Doctor->name,
                'image' => $Doctor->image,
            ];

            if (Auth::guard('doctor')->attempt($credentials)) {
                session(['doctors_data' => $DoctorData]);
                session()->save();
                return redirect()->route('doctor.home')->with(['message' => 'You are successfully logged in.', 'type' => 'success']);
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
        return redirect()->route('admin.login')->with(['message' => 'You have been successfully logged out.', 'type' => 'success']);
    }
}
