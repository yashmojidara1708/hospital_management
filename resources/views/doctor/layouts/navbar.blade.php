<nav class="navbar navbar-expand-lg header-nav">
    <div class="navbar-header">
        <a id="mobile_btn" href="javascript:void(0);">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>
        <a href="index-2.html" class="navbar-brand logo">
            <img src="assets/img/logo.png" class="img-fluid" alt="Logo">
        </a>
    </div>
    <div class="main-menu-wrapper">
        <div class="menu-header">
            <a href="index-2.html" class="menu-logo">
                <img src="assets/img/logo.png" class="img-fluid" alt="Logo">
            </a>
            <a id="menu_close" class="menu-close" href="javascript:void(0);">
                <i class="fas fa-times"></i>
            </a>
        </div>
        <ul class="main-nav">
            <li>
                <a href="index-2.html">Home</a>
            </li>
            <li class="has-submenu active">
                <a href="#">Doctors <i class="fas fa-chevron-down"></i></a>
                <ul class="submenu">
                    <li class="active"><a href="doctor-dashboard.html">Doctor Dashboard</a></li>
                    <li><a href="appointments.html">Appointments</a></li>
                    <li><a href="schedule-timings.html">Schedule Timing</a></li>
                    <li><a href="my-patients.html">Patients List</a></li>
                    <li><a href="patient-profile.html">Patients Profile</a></li>
                    <li><a href="chat-doctor.html">Chat</a></li>
                    <li><a href="invoices.html">Invoices</a></li>
                    <li><a href="doctor-profile-settings.html">Profile Settings</a></li>
                    <li><a href="reviews.html">Reviews</a></li>
                    <li><a href="doctor-register.html">Doctor Register</a></li>
                </ul>
            </li>	
            <li class="has-submenu">
                <a href="#">Patients <i class="fas fa-chevron-down"></i></a>
                <ul class="submenu">
                    <li><a href="search.html">Search Doctor</a></li>
                    <li><a href="doctor-profile.html">Doctor Profile</a></li>
                    <li><a href="booking.html">Booking</a></li>
                    <li><a href="checkout.html">Checkout</a></li>
                    <li><a href="booking-success.html">Booking Success</a></li>
                    <li><a href="patient-dashboard.html">Patient Dashboard</a></li>
                    <li><a href="favourites.html">Favourites</a></li>
                    <li><a href="chat.html">Chat</a></li>
                    <li><a href="profile-settings.html">Profile Settings</a></li>
                    <li><a href="change-password.html">Change Password</a></li>
                </ul>
            </li>	
            <li class="has-submenu">
                <a href="#">Pages <i class="fas fa-chevron-down"></i></a>
                <ul class="submenu">
                    <li><a href="voice-call.html">Voice Call</a></li>
                    <li><a href="video-call.html">Video Call</a></li>
                    <li><a href="search.html">Search Doctors</a></li>
                    <li><a href="calendar.html">Calendar</a></li>
                    <li><a href="components.html">Components</a></li>
                    <li class="has-submenu">
                        <a href="invoices.html">Invoices</a>
                        <ul class="submenu">
                            <li><a href="invoices.html">Invoices</a></li>
                            <li><a href="invoice-view.html">Invoice View</a></li>
                        </ul>
                    </li>
                    <li><a href="blank-page.html">Starter Page</a></li>
                    <li><a href="login.html">Login</a></li>
                    <li><a href="register.html">Register</a></li>
                    <li><a href="forgot-password.html">Forgot Password</a></li>
                </ul>
            </li>
            <li>
                <a href="admin/index.html" target="_blank">Admin</a>
            </li>
            <li class="login-link">
                <a href="login.html">Login / Signup</a>
            </li>
        </ul>	 
    </div>		 
    <ul class="nav header-navbar-rht">
        <li class="nav-item contact-item">
            <div class="header-contact-img">
                <i class="far fa-hospital"></i>							
            </div>
            <div class="header-contact-detail">
                <p class="contact-header">Contact</p>
                <p class="contact-info-header"> +1 315 369 5943</p>
            </div>
        </li>
        
        <!-- User Menu -->
        <li class="nav-item dropdown has-arrow logged-item">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                <span class="user-img">
                    <img class="rounded-circle" src="assets/img/doctors/doctor-thumb-02.jpg" width="31" alt="Darren Elder">
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="user-header">
                    <div class="avatar avatar-sm">
                        <img src="assets/img/doctors/doctor-thumb-02.jpg" alt="User Image" class="avatar-img rounded-circle">
                    </div>
                    <div class="user-text">
                        <h6>Darren Elder</h6>
                        <p class="text-muted mb-0">Doctor</p>
                    </div>
                </div>
                <a class="dropdown-item" href="doctor-dashboard.html">Dashboard</a>
                <a class="dropdown-item" href="doctor-profile-settings.html">Profile Settings</a>
                <a class="dropdown-item" href="login.html">Logout</a>
            </div>
        </li>
        <!-- /User Menu -->
        
    </ul>
</nav>