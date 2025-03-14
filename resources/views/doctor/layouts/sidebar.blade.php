<div class="profile-sidebar">
    <div class="widget-profile pro-widget-content">
        <div class="profile-info-widget">
            <a href="#" class="booking-doc-img">
                <img src="{{ asset('assets/doctor/theme/img/doctors/doctor-thumb-02.jpg')}}" alt="User Image">
            </a>
            <div class="profile-det-info">
                <h3>{{ isset($DoctorName) ? $DoctorName : 'N/A'}}</h3>
                
                <div class="patient-details">
                    <h5 class="mb-0">BDS, MDS - Oral & Maxillofacial Surgery</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-widget">
        <nav class="dashboard-menu">
            <ul>
                <li class="{{ request()->routeIs('doctor.home') ? 'active' : '' }}">
                    <a href="{{route('doctor.home')}}">
                        <i class="fas fa-columns"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                 <li class="{{ request()->routeIs('doctor.appointments') ? 'active' : '' }}">
                    <a href="{{route('doctor.appointments')}}">
                        <i class="fas fa-calendar-check"></i>
                        <span>Appointments</span>
                    </a>
                </li>
               <li class="{{ request()->routeIs('doctor.patients') ? 'active' : '' }}">
                    <a href="{{route('doctor.patients')}}">
                        <i class="fas fa-user-injured"></i>
                        <span>My Patients</span>
                    </a>
                </li>
               <li class="{{ request()->routeIs('doctor.prescription') ? 'active' : '' }}">
                    <a href="{{route('doctor.prescription')}}">
                        <i class="fas fa-prescription"></i>
                        <span>Prescription</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="invoices.html">
                        <i class="fas fa-file-invoice"></i>
                        <span>Invoices</span>
                    </a>
                </li>
                <li>
                    <a href="reviews.html">
                        <i class="fas fa-star"></i>
                        <span>Reviews</span>
                    </a>
                </li>
                <li>
                    <a href="chat-doctor.html">
                        <i class="fas fa-comments"></i>
                        <span>Message</span>
                        <small class="unread-msg">23</small>
                    </a>
                </li>
                <li class="{{ request()->routeIs('doctor.profile') ? 'active' : '' }}">
                    <a href="{{route('doctor.profile')}}">
                        <i class="fas fa-user-cog"></i>
                        <span>Profile Settings</span>
                    </a>
                </li>
                <li>
                    <a href="social-media.html">
                        <i class="fas fa-share-alt"></i>
                        <span>Social Media</span>
                    </a>
                </li>--}}
                
                <li class="{{ request()->routeIs('doctor-change-password') ? 'active' : '' }}">
                    <a href="{{route('doctor-change-password')}}">
                        <i class="fas fa-lock"></i>
                        <span>Change Password</span>
                    </a>
                </li> 
                <li class="{{ request()->routeIs('doctor.logout') ? 'active' : '' }}">
                    <a href="{{ route('doctor.logout') }}">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>