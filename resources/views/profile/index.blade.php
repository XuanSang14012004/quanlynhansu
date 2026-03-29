@extends('layouts.master')

@section('title', 'Trang cá nhân')

@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Bảng điều khiển</a><i class="fa fa-circle"></i></li>
                    <li><span>Hồ sơ cá nhân</span></li>
                </ul>
            </div>

            <h1 class="page-title"> Hồ sơ cá nhân <small>Thông tin chi tiết</small></h1>

            <div class="row">
                <div class="col-md-4">
                    <!-- Profile Card -->
                    <div class="portlet light bordered"
                        style="border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden;">
                        <!-- Cover Image -->
                        <div
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 120px; position: relative;">
                            <div
                                style="position: absolute; bottom: -60px; left: 50%; transform: translateX(-50%); z-index: 10;">
                                <div style="position: relative;">
                                    <img src="{{ $user->avatar && $user->avatar != 'default-avatar.jpg' ? asset('storage/' . $user->avatar) : asset('images/default-avatar.jpg') }}"
                                        class="img-circle" alt=""
                                        style="width: 120px; height: 120px; object-fit: cover; border: 5px solid #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                    <div
                                        style="position: absolute; bottom: 5px; right: 5px; width: 20px; height: 20px; background: #10c469; border: 3px solid #fff; border-radius: 50%;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Info -->
                        <div style="padding: 70px 20px 20px; text-align: center;">
                            <h3 style="margin: 10px 0 5px; font-size: 24px; font-weight: 700; color: #2c3e50;">
                                {{ $user->name }}
                            </h3>
                            <p style="color: #95a5a6; font-size: 14px; margin-bottom: 5px;">
                                <i class="fa fa-briefcase" style="margin-right: 5px;"></i>
                                {{ $user->boPhan->ten ?? 'Chưa cập nhật bộ phận' }}
                            </p>
                            <p style="color: #3498db; font-size: 13px; margin-bottom: 20px;">
                                <i class="fa fa-envelope" style="margin-right: 5px;"></i>
                                {{ $user->email }}
                            </p>

                            <!-- Action Buttons -->
                            <div style="display: flex; gap: 10px; justify-content: center; margin-top: 20px;">
                                <a href="{{ route('profile.edit') }}" class="btn btn-circle green-haze"
                                    style="flex: 1; padding: 10px 20px;">
                                    <i class="fa fa-edit"></i> Chỉnh sửa
                                </a>
                                <a href="{{ route('password.change') }}" class="btn btn-circle btn-danger"
                                    style="flex: 1; padding: 10px 20px;">
                                    <i class="fa fa-lock"></i> Đổi mật khẩu
                                </a>
                            </div>
                        </div>

                        <!-- Social Links Section -->
                        <div
                            style="display: flex; gap: 10px; padding: 20px; border-top: 1px solid #ecf0f1; background: #f8f9fa; justify-content: center;">
                            <a href="#"
                                style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #3b5998 0%, #8b9dc3 100%); display: flex; align-items: center; justify-content: center; transition: transform 0.3s;"
                                onmouseover="this.style.transform='translateY(-5px)'"
                                onmouseout="this.style.transform='translateY(0)'">
                                <i class="fa fa-facebook" style="color: white; font-size: 18px;"></i>
                            </a>
                            <a href="#"
                                style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #0077b5 0%, #00a0dc 100%); display: flex; align-items: center; justify-content: center; transition: transform 0.3s;"
                                onmouseover="this.style.transform='translateY(-5px)'"
                                onmouseout="this.style.transform='translateY(0)'">
                                <i class="fa fa-linkedin" style="color: white; font-size: 18px;"></i>
                            </a>
                            <a href="#"
                                style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #E1306C 0%, #fd1d1d 100%); display: flex; align-items: center; justify-content: center; transition: transform 0.3s;"
                                onmouseover="this.style.transform='translateY(-5px)'"
                                onmouseout="this.style.transform='translateY(0)'">
                                <i class="fa fa-instagram" style="color: white; font-size: 18px;"></i>
                            </a>
                            <a href="#"
                                style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #1DA1F2 0%, #0d8bd9 100%); display: flex; align-items: center; justify-content: center; transition: transform 0.3s;"
                                onmouseover="this.style.transform='translateY(-5px)'"
                                onmouseout="this.style.transform='translateY(0)'">
                                <i class="fa fa-twitter" style="color: white; font-size: 18px;"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <!-- Information Cards -->
                    <div class="portlet light bordered"
                        style="border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin-bottom: 20px;">
                        <div class="portlet-title" style="padding: 20px; border-bottom: 2px solid #667eea;">
                            <div class="caption">
                                <i class="fa fa-info-circle"
                                    style="color: #667eea; font-size: 20px; margin-right: 10px;"></i>
                                <span style="font-size: 18px; font-weight: 700; color: #2c3e50;">Thông tin cơ bản</span>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 25px;">
                            <div class="row">
                                <div class="col-md-6" style="margin-bottom: 20px;">
                                    <div
                                        style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%); padding: 20px; border-radius: 8px; border-left: 4px solid #667eea;">
                                        <div style="display: flex; align-items: center;">
                                            <div
                                                style="background: #667eea; width: 45px; height: 45px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                                <i class="fa fa-envelope" style="color: white; font-size: 20px;"></i>
                                            </div>
                                            <div>
                                                <p
                                                    style="margin: 0; color: #7f8c8d; font-size: 12px; text-transform: uppercase;">
                                                    Email</p>
                                                <p
                                                    style="margin: 5px 0 0; color: #2c3e50; font-size: 15px; font-weight: 600;">
                                                    {{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" style="margin-bottom: 20px;">
                                    <div
                                        style="background: linear-gradient(135deg, #f093fb15 0%, #f5576c15 100%); padding: 20px; border-radius: 8px; border-left: 4px solid #f093fb;">
                                        <div style="display: flex; align-items: center;">
                                            <div
                                                style="background: #f093fb; width: 45px; height: 45px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                                <i class="fa fa-phone" style="color: white; font-size: 20px;"></i>
                                            </div>
                                            <div>
                                                <p
                                                    style="margin: 0; color: #7f8c8d; font-size: 12px; text-transform: uppercase;">
                                                    Số điện thoại</p>
                                                <p
                                                    style="margin: 5px 0 0; color: #2c3e50; font-size: 15px; font-weight: 600;">
                                                    {{ $user->phone ?? 'Chưa cập nhật' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" style="margin-bottom: 20px;">
                                    <div
                                        style="background: linear-gradient(135deg, #4facfe15 0%, #00f2fe15 100%); padding: 20px; border-radius: 8px; border-left: 4px solid #4facfe;">
                                        <div style="display: flex; align-items: center;">
                                            <div
                                                style="background: #4facfe; width: 45px; height: 45px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                                <i class="fa fa-building" style="color: white; font-size: 20px;"></i>
                                            </div>
                                            <div>
                                                <p
                                                    style="margin: 0; color: #7f8c8d; font-size: 12px; text-transform: uppercase;">
                                                    Phòng ban</p>
                                                <p
                                                    style="margin: 5px 0 0; color: #2c3e50; font-size: 15px; font-weight: 600;">
                                                    {{ $user->phongBan->ten ?? 'Chưa phân phòng' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" style="margin-bottom: 20px;">
                                    <div
                                        style="background: linear-gradient(135deg, #fa709a15 0%, #fee14015 100%); padding: 20px; border-radius: 8px; border-left: 4px solid #fa709a;">
                                        <div style="display: flex; align-items: center;">
                                            <div
                                                style="background: #fa709a; width: 45px; height: 45px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                                <i class="fa fa-users" style="color: white; font-size: 20px;"></i>
                                            </div>
                                            <div>
                                                <p
                                                    style="margin: 0; color: #7f8c8d; font-size: 12px; text-transform: uppercase;">
                                                    Bộ phận/Đội</p>
                                                <p
                                                    style="margin: 5px 0 0; color: #2c3e50; font-size: 15px; font-weight: 600;">
                                                    {{ $user->boPhan->ten ?? 'Chưa phân bộ phận' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div
                                        style="background: linear-gradient(135deg, #43e97b15 0%, #38f9d715 100%); padding: 20px; border-radius: 8px; border-left: 4px solid #43e97b;">
                                        <div style="display: flex; align-items: center;">
                                            <div
                                                style="background: #43e97b; width: 45px; height: 45px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                                <i class="fa fa-calendar" style="color: white; font-size: 20px;"></i>
                                            </div>
                                            <div>
                                                <p
                                                    style="margin: 0; color: #7f8c8d; font-size: 12px; text-transform: uppercase;">
                                                    Ngày tham gia</p>
                                                <p
                                                    style="margin: 5px 0 0; color: #2c3e50; font-size: 15px; font-weight: 600;">
                                                    {{ $user->created_at->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <!-- Activity Timeline Card -->
                    <div class="portlet light bordered"
                        style="border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                        <div class="portlet-title" style="padding: 20px; border-bottom: 2px solid #764ba2;">
                            <div class="caption">
                                <i class="fa fa-clock-o" style="color: #764ba2; font-size: 20px; margin-right: 10px;"></i>
                                <span style="font-size: 18px; font-weight: 700; color: #2c3e50;">Nhật ký đăng nhập</span>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 25px;">

                            @if ($activities->count() > 0)
                                @foreach ($activities as $key => $activity)
                                    @php
                                        // Cấu hình hiển thị dựa trên Login hay Logout
                                        $isLogin = $activity->action == 'login';

                                        // Màu sắc: Login màu xanh tím, Logout màu cam/hồng
                                        $colorStart = $isLogin ? '#667eea' : '#f093fb';
                                        $colorEnd = $isLogin ? '#764ba2' : '#f5576c';
                                        $badgeText = $isLogin ? 'ĐĂNG NHẬP' : 'ĐĂNG XUẤT';
                                        $titleText = $isLogin ? 'Đăng nhập hệ thống' : 'Đăng xuất hệ thống';

                                        // Delay animation để hiệu ứng xuất hiện lần lượt
                                        $delay = ($key + 1) * 0.1;
                                    @endphp

                                    <div
                                        style="display: flex; gap: 15px; margin-bottom: 20px; position: relative; padding-bottom: 20px; {{ !$loop->last ? 'border-bottom: 1px dashed #ecf0f1;' : '' }}">
                                        <div style="position: relative;">
                                            <div
                                                style="width: 10px; height: 10px; background: {{ $colorStart }}; border-radius: 50%; position: absolute; left: 50%; top: 5px; transform: translateX(-50%); box-shadow: 0 0 0 4px {{ $colorStart }}30;">
                                            </div>
                                            @if (!$loop->last)
                                                <div
                                                    style="width: 2px; height: 100%; background: #ecf0f1; position: absolute; left: 50%; top: 15px; transform: translateX(-50%);">
                                                </div>
                                            @endif
                                        </div>

                                        <div
                                            style="flex: 1; animation: slideIn 0.5s ease both; animation-delay: {{ $delay }}s;">
                                            <div
                                                style="display: flex; align-items: center; gap: 10px; margin-bottom: 5px;">
                                                <span
                                                    style="background: linear-gradient(135deg, {{ $colorStart }} 0%, {{ $colorEnd }} 100%); color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                                    {{ $badgeText }}
                                                </span>
                                                <span style="color: #95a5a6; font-size: 12px;">
                                                    {{ $activity->created_at->format('H:i - d/m/Y') }}
                                                </span>
                                            </div>
                                            <p style="margin: 0; color: #2c3e50; font-weight: 600;">{{ $titleText }}
                                            </p>
                                            <p style="margin: 5px 0 0; color: #7f8c8d; font-size: 13px;">
                                                IP: {{ $activity->ip_address }}
                                                @if ($key == 0 && $isLogin)
                                                    <span
                                                        style="color: #27ae60; font-weight: bold; margin-left: 5px;">(Phiên
                                                        hiện tại)</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p style="text-align: center; color: #95a5a6; padding: 20px;">Chưa có lịch sử hoạt động
                                    nào.</p>
                            @endif

                        </div>
                    </div>

                    <style>
                        @keyframes slideIn {
                            from {
                                opacity: 0;
                                transform: translateX(-20px);
                            }

                            to {
                                opacity: 1;
                                transform: translateX(0);
                            }
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
@endsection
