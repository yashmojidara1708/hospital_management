@php
    // dd(session('staff_data'));
    $currentloginRoles = is_string($currentloginRole)
        ? array_map('trim', explode(',', $currentloginRole))
        : (array) $currentloginRole;
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
            'route' => 'admin.appointments',
            'icon' => 'fa-solid fa-calendar',
            'label' => 'Appointments',
            'roles' => ['Admin', 'Staff'], // Only for Admins
        ],
        [
            'route' => 'admin.patients',
            'icon' => 'fa-solid fa-hospital-user',
            'label' => 'Patients',
            'roles' => ['Admin', 'Staff'],
        ],
        [
            'route' => 'admin.specialities',
            'icon' => 'fa-solid fa-stethoscope',
            'label' => 'Specialities',
            'roles' => ['Admin'],
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
            'roles' => ['Medical', 'Nurse', 'Admin'],
        ],
    ];
@endphp


<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <!--admin sidebar-->
                @foreach ($menuItems as $item)
                    @if (!empty(array_intersect((array) $currentloginRoles, $item['roles'])))
                        <li class="{{ $currentRouteName == $item['route'] ? 'active' : '' }}">
                            <a href="{{ route($item['route']) }}">
                                <i class="{{ $item['icon'] }}"></i>
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
