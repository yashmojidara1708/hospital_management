@php
    $currentRouteName = \Route::currentRouteName();

@endphp

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <!--admin sidebar-->
                <li class="menu-title">
                    <span>
                        <h5>{{ session('user_role_name', 'N/A') }}</h5>
                    </span>
                </li>

                <li class="menu-item  {{ $currentRouteName == 'admin.home' ? 'active' : '' }}">
                    <a href="{{ route('admin.home') }}" class="menu-link">
                        <i class='menu-icon fa-solid fa-home'></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="menu-item  {{ $currentRouteName == 'admin.role' ? 'active' : '' }}">
                    <a href="{{ route('admin.role') }}" class="menu-link">
                        <i class='menu-icon fa-solid fa-people-arrows'></i>
                        <span>HMS Role</span>
                    </a>
                </li>

                <li class="menu-item  {{ $currentRouteName == 'admin.patients' ? 'active' : '' }}">
                    <a href="{{ route('admin.patients') }}" class="menu-link">
                        <i class="menu-icon fa-solid fa-hospital-user"></i>
                        <span>Patients</span>
                    </a>
                </li>
                <li>
                    <a href="appointment-list.html"><i class="fe fe-layout"></i> <span>Appointments</span></a>
                </li>

                <li class="menu-item  {{ $currentRouteName == 'admin.medicines' ? 'active' : '' }}">
                    <a href="{{ route('admin.medicines') }}" class="menu-link">
                        <i class="menu-icon fa-solid fa-pills"></i>
                        <span>Medicines</span>
                    </a>
                </li>
                <li class="menu-item  {{ $currentRouteName == 'admin.staff' ? 'active' : '' }}">
                    <a href="{{ route('admin.staff') }}" class="menu-link">
                        <i class="menu-icon fa-solid fa-users"></i>
                        <span>Staff</span>
                    </a>
                </li>
                <li class="menu-item  {{ $currentRouteName == 'admin.specialities' ? 'active' : '' }}">
                    <a href="{{ route('admin.specialities') }}" class="menu-link">
                        <i class="menu-icon fa-solid fa-user"></i>
                        <span>Specialities</span>
                    </a>
                </li>
                <li class="menu-item  {{ $currentRouteName == 'admin.doctors' ? 'active' : '' }}">
                    <a href="{{ route('admin.doctors') }}" class="menu-link">
                        <i class="menu-icon fa-solid fa-user-doctor"></i>
                        <span>Doctors</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
