<!-- /.modal -->
<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="#" class="logo mt-4 text-white fw-bold logo-dark">
            <h5 class="text-white">Absensi</h5>
        </a>
        <!-- Light Logo-->
        <a href="#" class="logo mt-4 fw-bold logo-light">
            <h5 class="text-white">Absensi</h5>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.dashboard') }}">
                        <i class="mdi mdi-speedometer"></i>
                        <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>
                <!-- end Dashboard Menu -->
                <li class="menu-title">
                    <i class="mdi mdi-dots-horizontal"></i>
                    <span data-key="t-pages">Masters</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.employees.index')}}">
                        <i class="mdi mdi-account"></i>
                        <span data-key="t-dashboards">Data Karyawan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.positions.index')}}">
                        <i class="mdi mdi-briefcase"></i>
                        <span data-key="t-dashboards">Data Jabatan</span>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.shifts.index')}}">
                        <i class="mdi mdi-clock"></i>
                        <span data-key="t-dashboards">Data Jam Kerja</span>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.buildings.index')}}">
                        <i class="mdi mdi-map-marker"></i>
                        <span data-key="t-dashboards">Data Lokasi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.checkpoint.index') }}">
                        <i class="mdi mdi-map"></i>
                        <span data-key="t-dashboards">Data Checkpoint</span>
                    </a>
                </li>
                <li class="menu-title">
                    <i class="mdi mdi-dots-horizontal"></i>
                    <span data-key="t-pages">Applications</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.leaves.index')}}">
                        <i class="mdi mdi-file-document"></i>
                        <span data-key="t-dashboards">Pengajuan Cuti </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.presences.index')}}">
                        <i class="mdi mdi-calendar-check"></i>
                        <span data-key="t-dashboards">Absensi </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.patrols.index')}}">
                        <i class="mdi mdi-shield-check"></i>
                        <span data-key="t-dashboards">Patrols </span>
                    </a>
                </li>
                <li class="menu-title">
                    <i class="mdi mdi-dots-horizontal"></i>
                    <span data-key="t-pages">Settings</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.users.index')}}">
                        <i class="mdi mdi-account-group"></i>
                        <span data-key="t-dashboards">User/Admin </span>
                    </a>
                </li>
            </ul>

        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
