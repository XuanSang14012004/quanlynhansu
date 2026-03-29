<div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner" style="width: 100%;">
        
        <div class="page-logo">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('/images/Datatech.png') }}" alt="logo" class="logo-default" width="140" />
            </a>
            <div class="menu-toggler sidebar-toggler">
                <span></span>
            </div>
        </div>
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
            data-target=".navbar-collapse">
            <span></span>
        </a>
        <div class='time-frame hidden-xs'
            style="float:left; font-size: 12px; color: #fff; padding: 16px; display: -webkit-box;">
            <i class="fa fa-clock-o" style="margin-right: 5px;"></i>
            <div id='datetime-part'></div>
        </div>

        <div class="top-menu">

            {{-- ======================================================= --}}
            {{-- ĐOẠN PHP XỬ LÝ LOGIC ẢNH VÀ TÊN (SỬA LỖI NULL + PATH) --}}
            {{-- ======================================================= --}}
            @php
                // 1. Khởi tạo giá trị mặc định (tránh lỗi null)
                $avatarDisplay = asset('images/default-avatar.jpg');
                $nameDisplay = 'Khách';
                $isCustomer = false; // Biến cờ để ẩn hiện menu

                // 2. Kiểm tra nếu là ADMIN / NHÂN VIÊN (Guard: web)
                if (Auth::guard('web')->check()) {
                    $user = Auth::guard('web')->user();
                    $nameDisplay = $user->name;

                    // Logic ảnh của Admin (Thường lưu trong storage)
                    if ($user->avatar && $user->avatar != 'default-avatar.jpg') {
                        $avatarDisplay = asset('storage/' . $user->avatar);
                    }
                } 
                // 3. Kiểm tra nếu là KHÁCH HÀNG (Guard: customer)
                elseif (Auth::guard('customer')->check()) {
                    $user = Auth::guard('customer')->user();
                    $nameDisplay = $user->name;
                    $isCustomer = true;

                    // Logic ảnh của Khách hàng
                    if ($user->avatar) {
                        // Nếu là link Google (bắt đầu bằng http) -> Dùng luôn
                        if (strpos($user->avatar, 'http') === 0) {
                            $avatarDisplay = $user->avatar;
                        } else {
                            // Nếu khách sau này tự upload ảnh -> Dùng storage
                            $avatarDisplay = asset('storage/' . $user->avatar);
                        }
                    }
                }
            @endphp
            {{-- ======================================================= --}}

            <ul class="nav navbar-nav pull-right">
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                        data-close-others="true">
                        
                        {{-- Hiển thị Ảnh (Đã xử lý ở trên) --}}
                        <img src="{{ $avatarDisplay }}" class="img-circle" alt="avatar" 
                             style="width: 29px; height: 29px; object-fit: cover;" />
                        
                        {{-- Hiển thị Tên (Đã xử lý ở trên) --}}
                        <span class="username username-hide-on-mobile"> 
                            {{ $nameDisplay }}
                        </span>
                        
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        
                        {{-- Menu Trang cá nhân: Chỉ hiện cho Admin/Nhân viên --}}
                        @if(!$isCustomer)
                        <li>
                            <a href="{{ route('profile.index') }}">
                                <i class="icon-user"></i> Trang Cá Nhân
                            </a>
                        </li>
                        <li class="divider"> </li>
                        @endif

                        {{-- Nút Đăng xuất: Xử lý riêng cho 2 loại tài khoản --}}
                        <li>
                            @if($isCustomer)
                                {{-- Logout cho Khách hàng (POST) --}}
                                <a href="#" onclick="event.preventDefault(); document.getElementById('customer-logout-form').submit();">
                                    <i class="icon-key"></i> Đăng Xuất
                                </a>
                                <form id="customer-logout-form" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            @else
                                {{-- Logout cho Admin (GET) --}}
                                <a href="{{ route('logout.get') }}">
                                    <i class="icon-key"></i> Đăng Xuất
                                </a>
                            @endif
                        </li>

                    </ul>
                </li>
                </ul>
        </div>
        </div>
    </div>
<div class="clearfix"> </div>