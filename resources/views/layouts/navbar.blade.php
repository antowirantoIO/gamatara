<?php
   require_once app_path('Helper/helper.php');
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
                @can('dashboard')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('home*') ? 'active' : ''}}" href="{{ route('dashboard') }}" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/dashboard.svg')}}"></i> <span data-key="t-layouts">Dashboard</span>
                    </a>
                </li>
                @endcan
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('customer*') || request()->is('user*') || request()->is('role*') ||
                        request()->is('vendor*') || request()->is('pekerjaan*')? 'active' : '' || request()->is('karyawan*')? 'active' : '' ||
                        request()->is('kategori*') || request()->is('sub_kategori*')? 'active' : '' || request()->is('setting_pekerjaan*')? 'active' : ''}}" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i><img src="{{asset('assets/images/document-text.svg')}}"></i> <span data-key="t-layouts">Master Data</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            @can('user')
                            <li class="nav-item">
                                <a href="{{ route('user') }}" class="nav-link {{ request()->is('user*') ? 'active' : ''}}" data-key="t-analytics"> User </a>
                            </li>
                            @endcan
                            @can('role')
                            <li class="nav-item">
                                <a href="{{ route('role') }}" class="nav-link {{ request()->is('role*') ? 'active' : ''}}" data-key="t-ecommerce"> Role </a>
                            </li>
                            @endcan
                            @can('customer')
                            <li class="nav-item">
                                <a href="{{ route('customer') }}" class="nav-link {{ request()->is('customer*') ? 'active' : ''}}" data-key="t-analytics"> Customer </a>
                            </li>
                            @endcan
                            @can('karyawan')
                            <li class="nav-item">
                                <a href="{{ route('karyawan') }}" class="nav-link {{ request()->is('karyawan*') ? 'active' : ''}}" data-key="t-analytics"> Karyawan</a>
                            </li>
                            @endcan
                            @can('pekerjaan')
                            <li class="nav-item">
                                <a href="{{ route('pekerjaan') }}" class="nav-link {{ request()->is('pekerjaan*') ? 'active' : ''}}" data-key="t-crypto"> Pekerjaan</a>
                            </li>
                            @endcan
                            @can('kategori')
                            <li class="nav-item">
                                <a href="{{ route('kategori') }}" class="nav-link {{ request()->is('kategori*') ? 'active' : ''}}" data-key="t-analytics"> Kategori</a>
                            </li>
                            @endcan
                            @can('sub_kategori')
                            <li class="nav-item">
                                <a href="{{ route('sub_kategori') }}" class="nav-link {{ request()->is('sub_kategori*') ? 'active' : ''}}" data-key="t-analytics"> Sub Kategori</a>
                            </li>
                            @endcan
                            @can('setting_pekerjaan')
                            <li class="nav-item">
                                <a href="{{ route('setting_pekerjaan') }}" class="nav-link {{ request()->is('setting_pekerjaan*') ? 'active' : ''}}" data-key="t-analytics"> Setting Pekerjaan</a>
                            </li>
                            @endcan
                            @can('vendor')
                            <li class="nav-item">
                                <a href="{{ route('vendor') }}" class="nav-link {{ request()->is('vendor*') ? 'active' : ''}}" data-key="t-crypto"> Vendor </a>
                            </li>
                            @endcan

                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('on_request*') ? 'active' : ''}}" href="{{ route('on_request') }}" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/task-squares.svg')}}"></i> <span data-key="t-layouts">On Request</span>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link menu-link" href="" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/notification-statuss.svg')}}"></i><span data-key="t-layouts">On Survey</span>
                    </a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('on_progress*') ? 'active' : ''}}" href="{{ route('on_progress') }}" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/activitys.svg')}}"></i> <span data-key="t-layouts">On Progress</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('complete*') ? 'active' : ''}}" href="{{ route('complete') }}" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/chart-successs.svg')}}"></i> <span data-key="t-layouts">Complete</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('laporan*') ? 'active' : ''}}" href="#laporandashboard" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="laporandashboard">
                        <i><img src="{{asset('assets/images/clipboard-text.svg')}}"></i> <span data-key="t-layouts">Laporan</span>
                    </a>
                    <div class="collapse menu-dropdown" id="laporandashboard">
                        <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                                <a href="" class="nav-link {{ request()->is('laporan_customer*') ? 'active' : ''}}" data-key="t-analytics"> Laporan Customer </a>
                            </li>

                            <li class="nav-item">
                                <a href="" class="nav-link {{ request()->is('laporan_vendor*') ? 'active' : ''}}" data-key="t-analytics"> Laporan Vendor </a>
                            </li>

                            <li class="nav-item">
                                <a href="" class="nav-link {{ request()->is('laporan_pd*') ? 'active' : ''}}" data-key="t-ecommerce"> Laporan PM </a>
                            </li>

                            <li class="nav-item">
                                <a href="" class="nav-link {{ set_active(['satisfaction_note']) }}" data-key="t-crypto"> Satisfaction Note</a>
                            </li>

                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar-background"></div>
