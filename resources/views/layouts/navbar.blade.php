    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Light Logo-->
        <a href="{{ route('dashboard') }}" class="logo logo-light">
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
        <div class="container-fluid" style="height: 100%">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                @can('dashboard')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('home*') ? 'active' : ''}}" href="{{ route('dashboard') }}" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/dashboard.svg')}}"></i> <span data-key="t-layouts">Dashboard</span>
                    </a>
                </li>
                @endcan
                @if (Gate::allows('karyawan-view') || Gate::allows('user-view') || Gate::allows('role-view') || 
                Gate::allows('customer-view') || Gate::allows('setting_pekerjaan-view') || Gate::allows('lokasi_project-view') || 
                Gate::allows('jenis_kapal-view') || Gate::allows('pekerjaan-view') || Gate::allows('kategori-view') || Gate::allows('sub_kategori-view') ||
                Gate::allows('project_manager-view') || Gate::allows('vendor-view'))
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('customer*') || request()->is('user*') || request()->is('role*') ||
                        request()->is('vendor*') || request()->is('pekerjaan*')? 'active' : '' || request()->is('karyawan*')? 'active' : '' ||
                        request()->is('kategori*') || request()->is('sub_kategori*')? 'active' : '' || request()->is('setting_pekerjaan*')? 'active' : ''}}" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i><img src="{{asset('assets/images/document-text.svg')}}"></i> <span data-key="t-layouts">Master Data</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            @can('karyawan-view')
                            <li class="nav-item">
                                <a href="{{ route('karyawan') }}" class="nav-link {{ request()->is('karyawan*') ? 'active' : ''}}" data-key="t-analytics"> Employee</a>
                            </li>
                            @endcan
                            @can('user-view')
                            <li class="nav-item">
                                <a href="{{ route('user') }}" class="nav-link {{ request()->is('user*') ? 'active' : ''}}" data-key="t-analytics"> User </a>
                            </li>
                            @endcan
                            @can('role-view')
                            <li class="nav-item">
                                <a href="{{ route('role') }}" class="nav-link {{ request()->is('role*') ? 'active' : ''}}" data-key="t-ecommerce"> Role </a>
                            </li>
                            @endcan
                            @can('project_manager-view')
                            <li class="nav-item">
                                <a href="{{ route('project_manager') }}" class="nav-link {{ request()->is('project_manager*') ? 'active' : ''}}" data-key="t-analytics"> Project Manager</a>
                            </li>
                            @endcan
                            @can('customer-view')
                            <li class="nav-item">
                                <a href="{{ route('customer') }}" class="nav-link {{ request()->is('customer*') ? 'active' : ''}}" data-key="t-analytics"> Customer </a>
                            </li>
                            @endcan
                            @can('lokasi_project-view')
                            <li class="nav-item">
                                <a href="{{ route('lokasi_project') }}" class="nav-link {{ request()->is('lokasi_project*') ? 'active' : ''}}" data-key="t-analytics"> Project Location</a>
                            </li>
                            @endcan
                            @can('jenis_kapal-view')
                            <li class="nav-item">
                                <a href="{{ route('jenis_kapal') }}" class="nav-link {{ request()->is('jenis_kapal*') ? 'active' : ''}}" data-key="t-analytics"> Ship Type</a>
                            </li>
                            @endcan
                            @can('pekerjaan-view')
                            <li class="nav-item">
                                <a href="{{ route('pekerjaan') }}" class="nav-link {{ request()->is('pekerjaan*') ? 'active' : ''}}" data-key="t-crypto"> Job</a>
                            </li>
                            @endcan
                            @can('kategori-view')
                            <li class="nav-item">
                                <a href="{{ route('kategori') }}" class="nav-link {{ request()->is('kategori*') ? 'active' : ''}}" data-key="t-analytics"> Category</a>
                            </li>
                            @endcan
                            @can('sub_kategori-view')
                            <li class="nav-item">
                                <a href="{{ route('sub_kategori') }}" class="nav-link {{ request()->is('sub_kategori*') ? 'active' : ''}}" data-key="t-analytics"> Sub Category</a>
                            </li>
                            @endcan
                            <!-- @can('setting_pekerjaan-view')
                            <li class="nav-item">
                                <a href="{{ route('setting_pekerjaan') }}" class="nav-link {{ request()->is('setting_pekerjaan*') ? 'active' : ''}}" data-key="t-analytics"> Job Setting</a>
                            </li>
                            @endcan -->
                            @can('vendor-view')
                            <li class="nav-item">
                                <a href="{{ route('vendor') }}" class="nav-link {{ request()->is('vendor*') ? 'active' : ''}}" data-key="t-crypto"> Vendor </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endif
                @can('on_request-view')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('on_request*') ? 'active' : ''}}" href="{{ route('on_request') }}" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/task-squares.svg')}}"></i> <span data-key="t-layouts">On Request</span>
                    </a>
                </li>
                @endcan
                <!-- <li class="nav-item">
                    <a class="nav-link menu-link" href="" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/notification-statuss.svg')}}"></i><span data-key="t-layouts">On Survey</span>
                    </a>
                </li> -->
                @can('on_progress-view')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('on_progress*') ? 'active' : ''}}" href="{{ route('on_progress') }}" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/activitys.svg')}}"></i> <span data-key="t-layouts">On Progress</span>
                    </a>
                </li>
                @endcan
                @can('complete-view')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('complete*') ? 'active' : ''}}" href="{{ route('complete') }}" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i><img src="{{asset('assets/images/chart-successs.svg')}}"></i> <span data-key="t-layouts">Complete</span>
                    </a>
                </li>
                @endcan
                @if (Gate::allows('laporan_customer-view') || Gate::allows('laporan_vendor-view') || Gate::allows('laporan_project_manager-view') || 
                Gate::allows('satisfaction_note-view') || Gate::allows('laporan_lokasi_project-view'))
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('laporan_customer*') ? 'active' : '' || request()->is('laporan_vendor*') ? 'active' : '' || request()->is('laporan_project_manager*') ? 'active' : '' }}" href="#laporandashboard" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="laporandashboard">
                        <i><img src="{{asset('assets/images/clipboard-text.svg')}}"></i> <span data-key="t-layouts">Report</span>
                    </a>
                    <div class="collapse menu-dropdown" id="laporandashboard">
                        <ul class="nav nav-sm flex-column">
                            @can('laporan_customer-view')
                            <li class="nav-item">
                                <a href="{{ route('laporan_customer') }}" class="nav-link {{ request()->is('laporan_customer*') ? 'active' : ''}}" data-key="t-analytics"> Report Customer </a>
                            </li>
                            @endcan
                            @can('laporan_vendor-view')
                            <li class="nav-item">
                                <a href="{{ route('laporan_vendor') }}" class="nav-link {{ request()->is('laporan_vendor*') ? 'active' : ''}}" data-key="t-analytics"> Report Vendor </a>
                            </li>
                            @endcan
                            @can('laporan_project_manager-view')
                            <li class="nav-item">
                                <a href="{{ route('laporan_project_manager') }}" class="nav-link {{ request()->is('laporan_project_manager*') ? 'active' : ''}}" data-key="t-ecommerce"> Report Project Manager </a>
                            </li>
                            @endcan
                            @can('laporan_lokasi_project-view')
                            <li class="nav-item">
                                <a href="{{ route('laporan_lokasi_project') }}" class="nav-link {{ request()->is('laporan_lokasi_project*') ? 'active' : ''}}" data-key="t-ecommerce"> Report Project Location </a>
                            </li>
                            @endcan
                            <!-- @can('satisfaction_note-view')
                            <li class="nav-item">
                                <a href="{{ route('satisfaction_note') }}" class="nav-link {{  request()->is('satisfaction_note') }}" data-key="t-crypto"> Satisfaction Note</a>
                            </li>
                            @endcan -->
                        </ul>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="sidebar-background"></div>
