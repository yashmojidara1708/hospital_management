@php
    $currentRouteName = \Route::currentRouteName();
@endphp
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main</span>
                </li>
                <li class="menu-item  {{ $currentRouteName == 'admin.home' ? 'active' : '' }}">
                    <a href="{{ route('admin.home') }}" class="menu-link">
                        <i class='fe fe-home'></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="appointment-list.html"><i class="fe fe-layout"></i> <span>Appointments</span></a>
                </li>
                <li class="menu-item  {{ $currentRouteName == 'admin.specialities' ? 'active' : '' }}">
                    <a href="{{ route('admin.specialities') }}" class="menu-link">
                        <i class='fe fe-users'></i>
                        <span>Specialities</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
