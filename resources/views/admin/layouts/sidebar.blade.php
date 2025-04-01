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
            'roles' => null,
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
        [
            'route' => 'admin.settings',
            'icon' => 'fa-solid fa-cog',
            'label' => 'Settings',
            'roles' => ['Medical', 'Nurse', 'Admin'],
        ],
        [
            'route' => 'admin.rooms.category',
            'icon' => 'fa-solid fa-layer-group',
            'label' => 'Rooms Category',
            'roles' => ['Admin'],
        ],
        [
            'route' => 'admin.rooms',
            'icon' => 'fa-solid fa-bed',
            'label' => 'Rooms',
            'roles' => ['Admin'],
        ],
        [
            'route' => 'admin.admit-patient',
            'icon' => 'fa-solid fa-user-plus',
            'label' => 'Admit Patients',
            'roles' => ['Admin', 'Staff'],
        ],
    ];

    // Separate the "Dashboard" menu item
    $dashboardItem = array_filter($menuItems, fn($item) => $item['label'] === 'Dashboard');
    $otherItems = array_filter($menuItems, fn($item) => $item['label'] !== 'Dashboard');

    // Sort the remaining items alphabetically by 'label'
    usort($otherItems, fn($a, $b) => strcmp($a['label'], $b['label']));

    // Merge Dashboard first, then the sorted items
    $menuItems = array_merge($dashboardItem, $otherItems);
@endphp


<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <!--admin sidebar-->
                @foreach ($menuItems as $item)
                    @if ($item['roles'] === null || !empty(array_intersect((array) $currentloginRoles, $item['roles'])))
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
