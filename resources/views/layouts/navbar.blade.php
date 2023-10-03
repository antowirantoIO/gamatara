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
                @can('dashboard')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ set_active(['dashboard']) }}" href="{{ route('dashboard') }}" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/dashboard.svg')}}"></i> <span data-key="t-layouts">Dashboard</span>
                    </a>
                </li>
                @endcan
                <li class="nav-item">
                    <a class="nav-link menu-link {{ set_active(['customer']) }}" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i><img src="{{asset('assets/images/document-text.svg')}}"></i> <span data-key="t-layouts">Master Data</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            @can('customer')
                            <li class="nav-item">
                                <a href="{{ route('customer') }}" class="nav-link {{ set_active(['customer']) }}" data-key="t-analytics"> Customer </a>
                            </li>
                            @endcan
                            @can('user')
                            <li class="nav-item">
                                <a href="{{ route('user') }}" class="nav-link {{ set_active(['user']) }}" data-key="t-analytics"> User </a>
                            </li>
                            @endcan
                            @can('role')
                            <li class="nav-item">
                                <a href="{{ route('role') }}" class="nav-link {{ set_active(['role']) }}" data-key="t-ecommerce"> Role </a>
                            </li>
                            @endcan
                            @can('vendor')
                            <li class="nav-item">
                                <a href="{{ route('vendor') }}" class="nav-link {{ set_active(['vendor']) }}" data-key="t-crypto"> Vendor </a>
                            </li>
                            @endcan
                            @can('pekerjaan')
                            <li class="nav-item">
                                <a href="{{ route('pekerjaan') }}" class="nav-link {{ set_active(['pekerjaan']) }}" data-key="t-crypto"> Pekerjaan</a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li> 
                <li class="nav-item">
                    <a class="nav-link menu-link {{ set_active(['on_request']) }}" href="{{ route('on_request') }}" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/task-squares.svg')}}"></i> <span data-key="t-layouts">On Request</span>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link menu-link" href="" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/notification-statuss.svg')}}"></i><span data-key="t-layouts">On Survey</span>
                    </a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ set_active(['on_progress']) }}" href="{{ route('on_progress') }}" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/activitys.svg')}}"></i> <span data-key="t-layouts">On Progress</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ set_active(['complete']) }}" href="{{ route('complete') }}" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/chart-successs.svg')}}"></i> <span data-key="t-layouts">Complete</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ set_active(['laporan']) }}" href="#laporandashboard" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="laporandashboard">
                        <i><img src="{{asset('assets/images/clipboard-text.svg')}}"></i> <span data-key="t-layouts">Laporan</span>
                    </a>
                    <div class="collapse menu-dropdown" id="laporandashboard">
                        <ul class="nav nav-sm flex-column">
                            
                            <li class="nav-item">
                                <a href="" class="nav-link {{ set_active(['laporan_customer']) }}" data-key="t-analytics"> Laporan Customer </a>
                            </li>
                       
                            <li class="nav-item">
                                <a href="" class="nav-link {{ set_active(['laporan_vendor']) }}" data-key="t-analytics"> Laporan Vendor </a>
                            </li>
                         
                            <li class="nav-item">
                                <a href="" class="nav-link {{ set_active(['laporan_pm']) }}" data-key="t-ecommerce"> Laporan PM </a>
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
    