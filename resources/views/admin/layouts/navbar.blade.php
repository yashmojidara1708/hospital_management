<!-- Logo -->
<div class="header-left">
    @if (!empty(get_setting('company_logo')))
        @if (get_setting('company_logo'))
            <a href="{{ route('admin.home') }}" class="logo">
                <img class="" src="{{ asset('uploads/' . get_setting('company_logo')) }}" alt="Hospital Logo">
            </a>
        @endif
    @else
        <a href="{{ route('admin.home') }}" class="logo">
            <img src="{{ asset('assets/admin/theme/img/logo.png') }}" alt="Hospital Logo">
        </a>
    @endif
    {{-- Favicon Icon --}}
    @if (!empty(get_setting('favicon')))
        @if (get_setting('favicon'))
            <a href="{{ route('admin.home') }}" class="logo logo-small">
                <img class="" src="{{ asset('uploads/' . get_setting('favicon')) }}" alt="Hospital Logo">
            </a>
        @endif
    @else
        <a href="{{ route('admin.home') }}" class="logo logo-small">
            <img src="{{ asset('assets/admin/theme/img/logo.png') }}" alt="Hospital Logo">
        </a>
    @endif
    {{-- <a href="index.html" class="logo">
        <img src="{{ asset('assets/admin/theme/img/logo.png') }}" alt="Logo">
    </a>
    <a href="index.html" class="logo logo-small">
        <img src="{{ asset('assets/admin/theme/img/logo-small.png') }}" alt="Logo" width="30" height="30">
    </a> --}}
</div>
<!-- /Logo -->

<a href="javascript:void(0);" id="toggle_btn">
    <i class="fe fe-text-align-left"></i>
</a>

<!-- Mobile Menu Toggle -->
<a class="mobile_btn" id="mobile_btn">
    <i class="fa fa-bars"></i>
</a>
<!-- /Mobile Menu Toggle -->

<!-- Header Right Menu -->
<ul class="nav user-menu">

    <!-- Notifications -->
    {{-- <li class="nav-item dropdown noti-dropdown">
        <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
            <i class="fe fe-bell"></i> <span class="badge badge-pill">3</span>
        </a>
        <div class="dropdown-menu notifications">
            <div class="topnav-dropdown-header">
                <span class="notification-title">Notifications</span>
                <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
            </div>
            <div class="noti-content">
                <ul class="notification-list">
                    <li class="notification-message">
                        <a href="#">
                            <div class="media">
                                <span class="avatar avatar-sm">
                                    <img class="avatar-img rounded-circle" alt="User Image"
                                        src="assets/img/doctors/doctor-thumb-01.jpg">
                                </span>
                                <div class="media-body">
                                    <p class="noti-details"><span class="noti-title">Dr. Ruby Perrin</span>
                                        Schedule <span class="noti-title">her appointment</span></p>
                                    <p class="noti-time"><span class="notification-time">4 mins ago</span>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="notification-message">
                        <a href="#">
                            <div class="media">
                                <span class="avatar avatar-sm">
                                    <img class="avatar-img rounded-circle" alt="User Image"
                                        src="assets/img/patients/patient1.jpg">
                                </span>
                                <div class="media-body">
                                    <p class="noti-details"><span class="noti-title">Charlene Reed</span>
                                        has booked her appointment to <span class="noti-title">Dr. Ruby
                                            Perrin</span></p>
                                    <p class="noti-time"><span class="notification-time">6 mins ago</span>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="notification-message">
                        <a href="#">
                            <div class="media">
                                <span class="avatar avatar-sm">
                                    <img class="avatar-img rounded-circle" alt="User Image"
                                        src="assets/img/patients/patient2.jpg">
                                </span>
                                <div class="media-body">
                                    <p class="noti-details"><span class="noti-title">Travis Trimble</span>
                                        sent a amount of $210 for his <span
                                            class="noti-title">appointment</span></p>
                                    <p class="noti-time"><span class="notification-time">8 mins ago</span>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="notification-message">
                        <a href="#">
                            <div class="media">
                                <span class="avatar avatar-sm">
                                    <img class="avatar-img rounded-circle" alt="User Image"
                                        src="assets/img/patients/patient3.jpg">
                                </span>
                                <div class="media-body">
                                    <p class="noti-details"><span class="noti-title">Carl Kelly</span>
                                        send a message <span class="noti-title"> to his doctor</span></p>
                                    <p class="noti-time"><span class="notification-time">12 mins
                                            ago</span></p>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="topnav-dropdown-footer">
                <a href="#">View all Notifications</a>
            </div>
        </div>
    </li> --}}
    <!-- /Notifications -->

    <!-- User Menu -->
    <li class="nav-item dropdown has-arrow">
        <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
            <span class="user-img"><img class="rounded-circle"
                    src="{{ asset('assets/admin/theme/img/profiles/avatar-05.png') }}" width="31"
                    alt=""></span>
        </a>
        <div class="dropdown-menu">
            <div class="user-header">
                <div class="avatar avatar-sm">
                    <img src="{{ asset('assets/admin/theme/img/profiles/avatar-05.png') }}" alt="User Image"
                        class="avatar-img rounded-circle">
                </div>
                <div class="user-text">
                    <h6>{{ isset($currentloginName) ? $currentloginName : '' }}</h6>
                    <p class="text-muted mb-0">{{ isset($currentloginRole) ? $currentloginRole : '' }}</p>
                </div>
            </div>
            {{-- <a class="dropdown-item" href="profile.html">My Profile</a> --}}
            <a class="dropdown-item" href="{{route('admin.changePassword')}}"><i class="fa-solid fa-key"></i>
                <span style="margin-left: 2%">Change Password</span></a>
            <a class="dropdown-item" href="{{ route('admin.logout') }}"> <i class="fa-solid fa-right-from-bracket"></i>
                <span style="margin-left: 2%">Logout</span></a>
        </div>
    </li>
    <!-- /User Menu -->
</ul>
<!-- /Header Right Menu -->
