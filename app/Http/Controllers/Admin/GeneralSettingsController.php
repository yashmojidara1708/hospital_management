<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class GeneralSettingsController extends Controller
{
    //
    public function index()
    {
        return view('admin.dashboard.generalSettings');
    }
}
