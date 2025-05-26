<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">

        <!-- Sidebar Menu -->
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">

                <li class="menu-title">Main</li>

                <!-- Admin Dashboard -->
                @if(auth()->user()->hasRole('admin'))
                    <li @if (request()->is('admin')) class="mm-active" @endif>
                        <a href="{{ route('admin') }}"
                           class="waves-effect {{ request()->is('admin') || request()->is('admin/*') ? 'mm active' : '' }}">
                            <i class="ti-home"></i>
                            <span> Admin Dashboard </span>
                        </a>
                    </li>
                @else
                    <!-- User Dashboard -->
                    <li @if (request()->is('user')) class="mm-active" @endif>
                        <a href="{{ route('user.index') }}"
                           class="waves-effect {{ request()->is('user') ? 'mm active' : '' }}">
                            <i class="ti-home"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>
                @endif

                @auth
                    @if(auth()->user()->hasRole('admin'))

                        <!-- ========== ADMIN MENU ITEMS ========== -->
                        <li>
                            <a href="/employees"
                               class="waves-effect {{ request()->is('employees') || request()->is('employees/*') ? 'mm active' : '' }}">
                                <i class="ti-user"></i>
                                <span> Employees </span>
                            </a>
                        </li>

                        <li class="menu-title">Management</li>
                        <li>
                            <a href="{{ route('admin.leave.index') }}"
                               class="waves-effect {{ request()->is('admin/leave') || request()->is('admin/leave/*') ? 'mm active' : '' }}">
                                <i class="ti-clipboard"></i> <span> Leave Requests </span>
                            </a>
                        </li>

                        <li>
                            <a href="/schedule"
                               class="waves-effect {{ request()->is('schedule') || request()->is('schedule/*') ? 'mm active' : '' }}">
                                <i class="ti-time"></i> <span> Schedule </span>
                            </a>
                        </li>

                        <li>
                            <a href="/check"
                               class="waves-effect {{ request()->is('check') || request()->is('check/*') ? 'mm active' : '' }}">
                                <i class="dripicons-to-do"></i> <span> Attendance Sheet </span>
                            </a>
                        </li>

                        <li>
                            <a href="/sheet-report"
                               class="waves-effect {{ request()->is('sheet-report') || request()->is('sheet-report/*') ? 'mm active' : '' }}">
                                <i class="dripicons-to-do"></i> <span> Sheet Report </span>
                            </a>
                        </li>

                        <li>
                            <a href="/attendance"
                               class="waves-effect {{ request()->is('attendance') || request()->is('attendance/*') ? 'mm active' : '' }}">
                                <i class="ti-calendar"></i> <span> Attendance Logs </span>
                            </a>
                        </li>

                        <!-- <li class="menu-title">Tools</li>

                        <li>
                            <a href="{{ route('finger_device.index') }}"
                               class="waves-effect {{ request()->is('finger_device') || request()->is('finger_device/*') ? 'mm active' : '' }}">
                                <i class="fas fa-fingerprint"></i>
                                <span> Biometric Device </span>
                            </a>
                        </li> -->

                    @else

                        <!-- ========== USER MENU ITEMS ========== -->
                        <li class="menu-title">User Panel</li>

                        <li>
                            <a href="{{ route('user.leave.request') }}"
                               class="waves-effect {{ request()->is('user/leave/request') ? 'mm active' : '' }}">
                                <i class="ti-calendar"></i> <span> Request Leave </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.leave.index') }}"
                               class="waves-effect {{ request()->is('user/leave/index') ? 'mm active' : '' }}">
                                <i class="ti-list"></i> <span> My Leave Requests </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('user.leave.attendance') }}"
                               class="waves-effect {{ request()->is('user/leave/attendance') ? 'mm active' : '' }}">
                                <i class="ti-bar-chart"></i> <span> My Attendance </span>
                            </a>
                        </li>

                        <li class="menu-title">Settings</li>

                        <li>
                            <a href="{{ route('profile.edit') }}"
                               class="waves-effect {{ request()->is('profile') ? 'mm active' : '' }}">
                                <i class="ti-settings"></i> <span> Edit Profile </span>
                            </a>
                        </li>

                    @endif
                @endauth

            </ul>
        </div>

        <div class="clearfix"></div>
    </div>
</div>
<!-- ========== Left Sidebar End ========== -->
