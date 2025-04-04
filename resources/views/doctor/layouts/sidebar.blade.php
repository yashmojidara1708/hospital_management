<?php
$menuItems = [['route' => 'doctor.home', 'icon' => 'fas fa-columns', 'label' => 'Dashboard'], ['route' => 'doctor.appointments', 'icon' => 'fas fa-calendar-check', 'label' => 'Appointments'], ['route' => 'doctor.patients', 'icon' => 'fas fa-user-injured', 'label' => 'My Patients'], ['route' => 'doctor-change-password', 'icon' => 'fas fa-lock', 'label' => 'Change Password'], ['route' => 'doctor.logout', 'icon' => 'fas fa-sign-out-alt', 'label' => 'Logout', 'class' => 'text-danger']];
$imagePath = "assets/admin/theme/img/doctors/" . $DoctorImage;
$defaultImage = asset("assets/admin/theme/img/doctors/default.jpg");
?>

<div class="profile-sidebar">
    <div class="widget-profile pro-widget-content">
        <div class="profile-info-widget">
            <a href="#" class="booking-doc-img">
                <img src="{{ asset($DoctorImage ? $imagePath : 'assets/admin/theme/img/doctors/default.jpg') }}" alt="User Image">
            </a>
            <div class="profile-det-info">
                <h3>{{ isset($DoctorName) ? $DoctorName : 'N/A' }}</h3>

                <div class="patient-details">
                    <h5 class="mb-0">{{ isset($DoctorSpecialization) ? $DoctorSpecialization : 'N/A'}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-widget">
        <nav class="dashboard-menu">
            <ul>
                @foreach ($menuItems as $item)
                    <li class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                        <a class="nav-link {{ $item['class'] ?? '' }}" href="{{ route($item['route']) }}">
                            <i class="{{ $item['icon'] }}"></i> {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</div>
