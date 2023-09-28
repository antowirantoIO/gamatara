<?php
    require_once app_path('Helper/Helper.php');
?>
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Light Logo-->
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{asset('assets/images/logo.png')}}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{asset('assets/images/logo.png')}}" width="120px">
                <h3 style="color:#194BFB;">GTS</h3>
                <hr width="200px">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link menu-link {{ set_active(['dashboard']) }}" href="{{ route('dashboard') }}" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/dashboard.svg')}}"></i> <span data-key="t-layouts">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ set_active(['customer']) }}" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i><img src="{{asset('assets/images/document-text.svg')}}"></i> <span data-key="t-layouts">Master Data</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('customer') }}" class="nav-link {{ set_active(['customer']) }}" data-key="t-analytics"> Customer </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('user') }}" class="nav-link {{ set_active(['user']) }}" data-key="t-analytics"> User </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('role') }}" class="nav-link" data-key="t-ecommerce"> Role </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('vendor') }}" class="nav-link {{ set_active(['vendor']) }}" data-key="t-crypto"> Vendor </a>
                            </li>
                        </ul>
                    </div>
                </li> 
                <li class="nav-item">
                    <a class="nav-link menu-link" href="" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/task-squares.svg')}}"></i> <span data-key="t-layouts">On Request</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/notification-statuss.svg')}}"></i><span data-key="t-layouts">On Survey</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/activitys.svg')}}"></i> <span data-key="t-layouts">On Progress</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/chart-successs.svg')}}"></i> <span data-key="t-layouts">Complete</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/clipboard-text.svg')}}"></i> <span data-key="t-layouts">Laporan</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar-background"></div>
    