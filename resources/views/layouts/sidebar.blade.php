    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box horizontal-logo">
                <a href="{{ route('dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{asset('assets/assets/images/logo-dark.png')}}" alt="" height="22" width="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('assets/assets/images/logo-dark.png')}}" alt="" height="17">
                    </span>
                </a>

                <a href="{{ route('dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{asset('assets/assets/images/logo-sm.png')}}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('assets/assets/images/logo-light.png')}}" alt="" height="17">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                <span class="hamburger-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </button>
        </div>

        <div class="d-flex align-items-center">
            <!-- <div class="dropdown ms-sm-3 header-item topbar-user"> -->
                <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-flex align-items-center">
                        <img class="rounded-circle header-profile-user" src="{{asset('assets/assets/images/users/avatar-1.jpg')}}" alt="Header Avatar">
                        <span class="text-start ms-xl-2">
                            <!-- <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">Anna Adame</span>
                            <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">Founder</span> -->
                        </span>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <h6 class="dropdown-header">Welcome, {{ Auth::user()->karyawan->name ?? '' }}</h6>
                    <div class="dropdown-divider"></div>
                        <!-- <form action="{{ route('logout') }}" method="POST">
                            @csrf -->
                            <button type="submit" class="dropdown-item logout">
                                <i class="mdi mdi-logout text-muted fs-16 align-middle me-1 logout"></i>
                                <span class="align-middle" data-key="t-logout">Logout</span>
                            </button>
                        <!-- </form> -->
                        <form id="logoutForm" method="POST">
                        @csrf
                    </form>
                </div>
        </div>
    </div>
