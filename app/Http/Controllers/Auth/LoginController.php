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
                'email'                 => 'required|email',
                'password'              => 'required|min:8|max:20|regex:/^[A-Za-z0-9 ]+$/|alpha_dash',
            ]);

            $credentials = $request->only('email', 'password');
            //
            // $users = DB::table('users')
            // ->join('roles', 'users.role_id', '=', 'roles.id')
            // ->where('users.email', $request->email)
            // ->select('users.role_id', 'users.email','users.name as username','roles.name as role_name')
            // ->get();
            // $userCount = $users->count();
            if (Auth::attempt($credentials)) {
                // if ($userCount == 1) {
                // $user = $users->first();
                // session([
                //     'user_email' => $user->email,
                //     'user_name' => $user->username,
                //     'user_role_id' => $user->role_id,
                //     'user_role_name' => $user->role_name
                // ]);
                // }
                return redirect()->route('admin.home')->with(['message' => 'You are successfully logged in.', 'type' => 'success']);
                //
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
