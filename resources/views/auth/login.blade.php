@extends('layouts.app')
@section('admin-title', 'Login')
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
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded nav-justified">
                                <li class="nav-item"><a class="nav-link active" href="#solid-rounded-justified-tab1"
                                        data-toggle="tab">Admin</a></li>
                                <li class="nav-item"><a class="nav-link" href="#solid-rounded-justified-tab2"
                                        data-toggle="tab">Doctor</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <!-- Admin Tab Pane -->
                                <div class="tab-pane show active" id="solid-rounded-justified-tab1" role="tabpanel"
                                    aria-labelledby="admin-tab">
                                    <form id="adminLoginForm" method="POST" action="{{ url('/check/login') }}"
                                        style="margin-top: 5%">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="admin-email" class="form-label text-dark">Email</label>
                                            <input type="text" class="form-control" id="admin-email" name="email"
                                                placeholder="Enter your email" autofocus required />
                                            @if ($errors->has('email'))
                                                <div class="text-danger">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>
                                        <div class="mb-3 form-password-toggle">
                                            <label class="form-label text-dark" for="admin-password">Password</label>
                                            <div class="input-group input-group-merge">
                                                <input type="password" id="admin-password" class="form-control"
                                                    name="password" placeholder="••••••••••••"
                                                    aria-describedby="password" />
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

                                <!-- Doctor Tab Pane -->
                                <div class="tab-pane" id="solid-rounded-justified-tab2" role="tabpanel"
                                    aria-labelledby="doctor-tab">
                                    <form id="adminLoginForm" method="POST" action="{{ url('/check/doctorlogin') }}"
                                        style="margin-top: 5%">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="admin-email" class="form-label text-dark">Email</label>
                                            <input type="text" class="form-control" id="admin-email" name="email"
                                                placeholder="Enter your email" autofocus required />
                                            @if ($errors->has('email'))
                                                <div class="text-danger">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>
                                        <div class="mb-3 form-password-toggle">
                                            <label class="form-label text-dark" for="admin-password">Password</label>
                                            <div class="input-group input-group-merge">
                                                <input type="password" id="admin-password" class="form-control"
                                                    name="password" placeholder="••••••••••••"
                                                    aria-describedby="password" />
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
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the active tab from localStorage
            var activeTab = localStorage.getItem('activeTab');

            if (activeTab) {
                // Show the stored active tab
                var tabElement = document.querySelector(`.nav-link[href="${activeTab}"]`);
                var tabPaneElement = document.querySelector(activeTab);

                if (tabElement && tabPaneElement) {
                    // Remove active classes from all tabs and panes
                    document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
                    document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('show', 'active'));

                    // Add active class to the stored tab and pane
                    tabElement.classList.add('active');
                    tabPaneElement.classList.add('show', 'active');
                }
            }

            // Set the active tab on click and store it in localStorage
            document.querySelectorAll('.nav-link').forEach(function(tab) {
                tab.addEventListener('click', function() {
                    var tabId = this.getAttribute('href');
                    localStorage.setItem('activeTab', tabId);
                });
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Include toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endsection
