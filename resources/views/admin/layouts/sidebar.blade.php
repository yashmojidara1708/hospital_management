@php
    $currentRouteName = \Route::currentRouteName();
      
@endphp

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                @if(session('user_role_name')=='Admin' || session('user_role_name') == 'Doctor' 
                || session('user_role_name') == 'Receptionist')
                <!--admin sidebar-->
                        <li class="menu-title">
                           <span><h5>{{ session('user_role_name', 'N/A') }}</h5>
                           </span>
                        </li>
                        <li class="menu-item  {{ $currentRouteName == 'admin.home' ? 'active' : '' }}">
                            <a href="{{ route('admin.home') }}" class="menu-link">
                                <i class='fe fe-home'></i>
                                <span>Dashboard</span>
                                
                            </a>
                        </li>
                
                <li class="menu-item  {{ $currentRouteName == 'admin.specialities' ? 'active' : '' }}">
                    <a href="{{ route('admin.specialities') }}" class="menu-link">
                        <i class='fa-solid fa-stethoscope'></i>
                        <span>Specialities</span>
                    </a>
                </li>
               
                <li class="menu-item  {{ $currentRouteName == 'admin.patients' ? 'active' : '' }}">
                    <a href="{{ route('admin.patients') }}" class="menu-link">
                        <i class="fa-solid fa-hospital-user"></i>
                        <span>Patients</span>
                    </a>
                    </li>
                    <li>
                        <a href="appointment-list.html"><i class="fe fe-layout"></i> <span>Appointments</span></a>
                    </li>
              @endif 
                <!--admin sidebar-->   
          @if(session('user_role_name')== 'Admin') 
                <li class="menu-item  {{ $currentRouteName == 'admin.role' ? 'active' : '' }}">
                    <a href="{{ route('admin.role') }}" class="menu-link">
                        <i class='fe fe-users'></i>
                        <span>HMS Role</span>
                    </a>
                </li>
        @endif
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->