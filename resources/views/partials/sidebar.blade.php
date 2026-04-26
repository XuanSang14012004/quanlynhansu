<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu page-header-fixed" data-keep-expanded="false" data-auto-scroll="true"
            data-slide-speed="200" style="padding-top: 20px">

            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler"><span></span></div>
            </li>

            @can('read-dashboard')
            <li class="nav-item start {{ Route::currentRouteName() == 'dashboard' ? 'active open' : '' }}">

                <a href="{{ route('dashboard') }}" class="nav-link">

                    <i class="fa fa-dashboard"></i>

                    <span class="title">Bảng điều khiển</span>

                    <span class="selected"></span>

                </a>

            </li>
            @endcan


            @if (Auth::guard('web')->check())
            <li class="heading">
                <h3 class="uppercase">Quản trị hệ thống</h3>
            </li>
            @endif

            @can('read-users')
            <li class="nav-item {{ Route::currentRouteName() == 'user.index' ? 'active open' : '' }}">
                <a href="{{ route('user.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-users"></i>
                    <span class="title">Danh sách người dùng</span>
                    <span class="selected"></span>
                </a>
            </li>
            @endcan

            @can('create-users')
            <li class="nav-item {{ Route::currentRouteName() == 'user.add.get' ? 'active open' : '' }}">
                <a href="{{ route('user.add.get') }}" class="nav-link nav-toggle">
                    <i class="fa fa-user-plus"></i>
                    <span class="title">Thêm người dùng</span>
                    <span class="selected"></span>
                </a>
            </li>
            @endcan

            @can('update-password')
            <li class="nav-item {{ Route::currentRouteName() == 'password.change' ? 'active open' : '' }}">
                <a href="{{ route('password.change') }}" class="nav-link nav-toggle">
                    <i class="fa fa-key"></i>
                    <span class="title">Đổi mật khẩu</span>
                    <span class="selected"></span>
                </a>
            </li>
            @endcan

            @can('read-profile')
            <li class="nav-item {{ Route::currentRouteName() == 'profile.index' ? 'active open' : '' }}">
                <a href="{{ route('profile.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-user"></i>
                    <span class="title">Hồ sơ cá nhân</span>
                    <span class="selected"></span>
                </a>
            </li>
            @endcan

            @can('update-profile')
            <li class="nav-item {{ Route::currentRouteName() == 'profile.edit' ? 'active open' : '' }}">
                <a href="{{ route('profile.edit') }}" class="nav-link nav-toggle">
                    <i class="fa fa-user"></i>
                    <span class="title">Chỉnh sửa thông tin </span>
                    <span class="selected"></span>
                </a>
            </li>
            @endcan

            @can('read-acl')
            <li class="nav-item {{ Request::is('roles*') ? 'active open' : '' }}">
                <a href="{{ route('role.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-shield"></i>
                    <span class="title">Phân quyền vai trò</span>
                    <span class="selected"></span>
                </a>
            </li>
            @endcan


            @can('manage-feature-group')
            <li class="nav-item {{ Request::is('groupPermissions*') ? 'active open' : '' }}">
                <a href="{{ route('feature-group.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-object-group"></i>
                    <span class="title">Phân quyền nhóm </span>
                    <span class="selected"></span>
                </a>
            </li>
            @endcan

            {{-- 2. MODULE PHÂN QUYỀN CHI TIẾT --}}
            {{-- Kiểm tra quyền: manage-user-feature --}}
            @can('manage-user-feature')
            <li class="nav-item {{ Request::is('userPermissions*') ? 'active open' : '' }}">
                <a href="{{ route('user-feature-group.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-user-plus"></i>
                    <span class="title">Phân quyền chi tiết</span>
                    <span class="selected"></span>
                </a>
            </li>
            @endcan


            @can('read-nhan-su')
            <li class="nav-item {{ Request::is('staffs*') ? 'active open' : '' }}">
                <a href="{{ route('nhan_su.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-user-md"></i>
                    <span class="title">Danh sách nhân sự</span>
                    <span class="selected"></span>
                </a>
            </li>
            @endcan


            @if (Auth::guard('web')->check())
            <li class="heading">
                <h3 class="uppercase">Quản lý tin tức</h3>
            </li>
            @endif

            @can('read-news')
            <li class="nav-item {{ Route::currentRouteName() == 'news.index' ? 'active open' : '' }}">
                <a href="{{ route('news.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-list-alt"></i>
                    <span class="title">Danh sách tin tức</span>
                    <span class="selected"></span>
                </a>
            </li>
            @endcan



            @can('create-news')
            <li class="nav-item {{ Route::currentRouteName() == 'news.create' ? 'active open' : '' }}">
                <a href="{{ route('news.create') }}" class="nav-link nav-toggle">
                    <i class="fa fa-pencil-square-o"></i>
                    <span class="title">Thêm mới tin tức</span>
                    <span class="selected"></span>
                </a>
            </li>
            @endcan

            @can('update-file-manager')
            <li class="nav-item {{ Request::is('file-manager*') ? 'active open' : '' }}">
                <a href="{{ route('file-manager.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-folder-open"></i>
                    <span class="title">Quản lý tập tin</span>
                    <span class="selected"></span>
                </a>
            </li>
            @endcan

            @can('update-company')
            <li class="nav-item {{ Route::currentRouteName() == 'company.index' ? 'active open' : '' }}">
                <a href="{{ route('company.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-building"></i>
                    <span class="title">Cấu hình công ty</span>
                    <span class="selected"></span>
                </a>
            </li>
            @endcan
            @can('login_history')
            <li class="nav-item {{ Route::currentRouteName() == 'login-history.index' ? 'active open' : '' }}">
                <a href="{{ route('login-history.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-history"></i>
                    <span class="title">Nhật ký hoạt động</span>
                    <span class="selected"></span>
                </a>
            </li>
            @endcan
            {{-- Tìm đoạn li của menu Tra cứu gói thầu --}}
            @can('bid-search')
            <li
                class="nav-item {{ Request::routeIs('goithau.search') || Request::routeIs('customer.goithau.search') ? 'active open' : '' }}">

                {{-- ĐOẠN CODE THÔNG MINH: Tự chọn link dựa trên người đăng nhập --}}
                <a href="{{ Auth::guard('customer')->check() ? route('customer.goithau.search') : route('goithau.search') }}"
                    class="nav-link nav-toggle">

                    <i class="fa fa-search"></i>
                    <span class="title">Tra cứu Gói thầu</span>

                    @if (Request::routeIs('goithau.search') || Request::routeIs('customer.goithau.search'))
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            @endcan

            @if (Auth::guard('web')->check())
            <li class="heading">
                <h3 class="uppercase">Quản lý công việc</h3>
            </li>
            @endif


            <!-- {{-- @can('read-tasks') --}}  -->
            <li class="nav-item {{ Route::currentRouteName() == 'tasks.index' ? 'active open' : '' }}">
                <a href="{{ route('tasks.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-tasks"></i> {{-- Đổi icon bi-clipboard thành fa-tasks cho đồng bộ --}}
                    <span class="title">Quản lý công việc</span>

                    {{-- Mũi tên chỉ thị đang chọn --}}
                    @if (Route::currentRouteName() == 'tasks.index')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            <!-- {{-- @endcan --}} -->

            <li class="nav-item {{ Route::currentRouteName() == 'my-tasks.index' ? 'active open' : '' }}">
                {{-- Lưu ý: Đảm bảo bạn đã đặt tên route là 'my-tasks.index' trong web.php --}}
                <a href="{{ route('my-tasks.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-check-square-o"></i> {{-- Icon khác để dễ phân biệt --}}
                    <span class="title">Công việc của tôi</span>

                    {{-- Mũi tên chỉ thị đang chọn --}}
                    @if (Route::currentRouteName() == 'my-tasks.index')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            <li class="nav-item {{ Route::currentRouteName() == 'tasks.calendar' ? 'active open' : '' }}">
                {{-- Đảm bảo bạn đặt tên route là 'tasks.calendar' trong web.php --}}
                <a href="{{ route('tasks.calendar') }}" class="nav-link nav-toggle">
                    <i class="fa fa-calendar"></i> {{-- Icon lịch để dễ nhận diện --}}
                    <span class="title">Lịch trình của tôi</span>

                    {{-- Mũi tên chỉ thị đang chọn --}}
                    @if (Route::currentRouteName() == 'tasks.calendar')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            <li class="nav-item {{ Route::currentRouteName() == 'notification-config' ? 'active open' : '' }}">
                {{-- Đảm bảo bạn đặt tên route là 'notification-config' trong web.php --}}
                <a href="{{ route('notification-config') }}" class="nav-link nav-toggle">
                    <i class="fa fa-bell"></i> {{-- Icon lịch để dễ nhận diện --}}
                    <span class="title">Cấu hình thông báo</span>

                    {{-- Mũi tên chỉ thị đang chọn --}}
                    @if (Route::currentRouteName() == 'notification-config')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
         
             <li class="nav-item {{ Route::currentRouteName() == 'holiday.index' ? 'active open' : '' }}">
                {{-- Đảm bảo bạn đặt tên route là 'holiday-config' trong web.php --}}
                <a href="{{ route('holiday.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-bell"></i> {{-- Icon lịch để dễ nhận diện --}}
                    <span class="title">Cấu hình ngày nghỉ</span>

                    {{-- Mũi tên chỉ thị đang chọn --}}
                    @if (Route::currentRouteName() == 'holiday.index')
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
        </ul>
    </div>
</div>