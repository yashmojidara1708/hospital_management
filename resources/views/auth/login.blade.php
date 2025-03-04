@extends('layouts.app')
@section('admin-title', 'Admin Login')
@section('content')
    <!-- Content -->
    <div class="container col-lg-4 mt-5">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand">
                            <span class="app-brand-logo demo" style="margin-left: 25%">
                                <img src="{{ asset('assets/admin/theme/img/logo.png') }}" alt="">
                            </span>
                            </a>
                        </div>
                        {{-- <h4 class="mb-3 text-center">Admin Login</h4> --}}

                        <form id="adminloginForm" method="POST" action="{{ url('/check/login') }}" style="margin-top: 5%">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label text-dark">Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Enter your email" autofocus required />
                                @if ($errors->has('email'))
                                    <div class="text-danger">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label text-dark" for="password">Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    {{-- <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span> --}}
                                </div>
                                @if ($errors->has('password'))
                                    <div class="text-danger">{{ $errors->first('password') }}</div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Include toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endsection
