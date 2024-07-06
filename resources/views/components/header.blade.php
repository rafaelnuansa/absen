<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->

                <button type="button"
                    class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger shadow-none"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

            </div>

            <div class="d-flex align-items-center">

                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn shadow-none" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <!-- Menggunakan inisial pengguna sebagai avatar -->
                            <div class="rounded-circle header-profile-user bg-primary text-white"
                                style="width: 40px; height: 40px; line-height: 40px; font-size: 20px; text-align: center;">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-bold user-name-text">
                                    {{ auth()->user()->name }}
                                </span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header">Welcome {{ auth()->user()->name }}!</h6>
                        <a class="dropdown-item" href="{{ route('admin.users.profile')}}"><i
                                class="mdi mdi-account text-muted fs-16 align-middle me-1"></i>
                            <span class="align-middle" data-key="t-profile">Profile</span></a>
                        <a class="dropdown-item" href="{{ route('admin.logout')}}"><i
                                class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                            <span class="align-middle" data-key="t-logout">Logout</span></a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</header>
