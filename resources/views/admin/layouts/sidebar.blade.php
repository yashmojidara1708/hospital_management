@php
    $currentRouteName = \Route::currentRouteName();
@endphp
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-item  {{ $currentRouteName == 'admin.home' ? 'active' : '' }}">
                    <a href="{{ route('admin.home') }}" class="menu-link">
                        <i class='menu-icon fa-solid fa-home'></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="menu-item  {{ $currentRouteName == 'admin.specialities' ? 'active' : '' }}">
                    <a href="{{ route('admin.specialities') }}" class="menu-link">
                        <i class='menu-icon fa-solid fa-stethoscope'></i>
                        <span>Specialities</span>
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
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
