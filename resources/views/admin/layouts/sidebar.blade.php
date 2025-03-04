@php
    $currentRouteName = \Route::currentRouteName();
    // dd(session('staff_data'));

    // Define menu items dynamically
    $menuItems = [
        [
            'route' => 'admin.home',
            'icon' => 'fa-solid fa-home',
            'label' => 'Dashboard',
            'roles' => ['Admin', 'Staff'],
        ],
        [
            'route' => 'admin.role',
            'icon' => 'fa-solid fa-people-arrows',
            'label' => 'HMS Role',
            'roles' => ['Admin'], // Only for Admins
        ],
        [
            'route' => 'admin.patients',
            'icon' => 'fa-solid fa-hospital-user',
            'label' => 'Patients',
            'roles' => ['Admin', 'Staff'],
        ],
        [
            'route' => 'admin.medicines',
            'icon' => 'fa-solid fa-pills',
            'label' => 'Medicines',
            'roles' => ['Admin', 'Staff'],
        ],
        [
            'route' => 'admin.staff',
            'icon' => 'fa-solid fa-users',
            'label' => 'Staff',
            'roles' => ['Admin'],
        ],
        [
            'route' => 'admin.doctors',
            'icon' => 'fa-solid fa-user-doctor',
            'label' => 'Doctors',
            'roles' => ['Admin'],
        ],
    ];
@endphp


<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <!--admin sidebar-->
                {{-- <li class="menu-title">
                    <span>
                        <h5>{{ isset($currentloginRole) ? $currentloginRole : '' }}</h5>
                    </span>
                </li> --}}
                @foreach ($menuItems as $item)
                    @if (in_array($currentloginRole, $item['roles']))
                        <li class="menu-item {{ $currentRouteName == $item['route'] ? 'active' : '' }}">
                            <a href="{{ route($item['route']) }}" class="menu-link">
                                <i class="menu-icon {{ $item['icon'] }}"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
