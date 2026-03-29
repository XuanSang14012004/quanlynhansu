@extends('layouts.master')

@section('title', 'Chỉnh sửa thông tin cá nhân')

{{-- 1. CHÈN CSS VÀO @yield('style') CỦA LAYOUT --}}
@section('style')
    <link rel="stylesheet" href="{{ asset('css/profile/edit.css') }}">
@endsection

@section('content')


<div class="page-content-wrapper">
    <div class="page-content">
        
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="{{ route('dashboard') }}">Bảng điều khiển</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Chỉnh sửa thông tin</span>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                
                {{-- Header --}}
                <div class="profile-header-box">
                    <h1>Chỉnh sửa thông tin cá nhân</h1>
                </div>

                {{-- Form Card --}}
                <div class="profile-main-card">
                    <div class="card-top-decoration"></div>

                    <div class="profile-content">
                        @if (session('status'))
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i> {{ session('status') }}
                            </div>
                        @endif

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            {{-- Avatar Section --}}
                            <div class="avatar-section">
                                <label for="avatarInput" class="avatar-container">
                                    <img id="avatarPreview"
                                        src="{{ $user->avatar && $user->avatar != 'default-avatar.jpg' ? asset('storage/' . $user->avatar) : asset('images/default-avatar.jpg') }}"
                                        class="avatar-circle" alt="Avatar">
                                    <div class="camera-button">
                                        <i class="fa fa-camera"></i>
                                    </div>
                                </label>
                                <input type="file" id="avatarInput" name="avatar" accept="image/*">
                                <div class="avatar-hint">Nhấn vào ảnh để thay đổi</div>
                                @error('avatar') 
                                    <span class="text-danger">
                                        <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                    </span> 
                                @enderror
                            </div>

                            {{-- Thông tin cơ bản --}}
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="fa fa-user"></i> Thông tin cơ bản
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label class="form-label-custom">Họ và tên</label>
                                            <div class="input-group-custom">
                                                <i class="fa fa-user input-icon-left"></i>
                                                <input type="text" name="name" class="form-input-custom" value="{{ old('name', $user->name) }}" placeholder="Nhập họ tên">
                                            </div>
                                            @error('name') 
                                                <span class="text-danger">
                                                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                                </span> 
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label class="form-label-custom">Email đăng nhập</label>
                                            <div class="input-group-custom">
                                                <i class="fa fa-envelope input-icon-left"></i>
                                                <input type="email" name="email" class="form-input-custom" value="{{ old('email', $user->email) }}" placeholder="email@example.com">
                                            </div>
                                            @error('email') 
                                                <span class="text-danger">
                                                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                                </span> 
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group-custom">
                                            <label class="form-label-custom">Số điện thoại</label>
                                            <div class="input-group-custom">
                                                <i class="fa fa-phone input-icon-left"></i>
                                                <input type="text" name="phone" class="form-input-custom" value="{{ old('phone', $user->phone ?? '') }}" placeholder="Nhập số điện thoại">
                                            </div>
                                            @error('phone') 
                                                <span class="text-danger">
                                                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                                </span> 
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Thông tin công việc --}}
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="fa fa-building"></i> Thông tin công việc
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label class="form-label-custom">Phòng ban</label>
                                            <div class="input-group-custom">
                                                <i class="fa fa-briefcase input-icon-left"></i>
                                                <select name="phongban_id" class="form-input-custom" id="phongban_select">
                                                    <option value="">-- Chọn phòng ban --</option>
                                                    @foreach ($phongBans as $pb)
                                                        <option value="{{ $pb->id }}" {{ old('phongban_id', $user->phongban_id) == $pb->id ? 'selected' : '' }}>{{ $pb->ten }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label class="form-label-custom">Bộ phận</label>
                                            <div class="input-group-custom">
                                                <i class="fa fa-sitemap input-icon-left"></i>
                                                <select name="bophan_id" class="form-input-custom" id="bophan_select">
                                                    <option value="">-- Chọn bộ phận --</option>
                                                    @foreach ($boPhans as $bp)
                                                        <option value="{{ $bp->id }}" data-phongban="{{ $bp->phongban_id }}" {{ old('bophan_id', $user->bophan_id) == $bp->id ? 'selected' : '' }}>{{ $bp->ten }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="action-buttons">
                                <a href="{{ route('dashboard') }}" class="btn-secondary-custom">
                                    <i class="fa fa-times"></i> Quay lại
                                </a>
                                <button type="submit" class="btn-primary-custom">
                                    <i class="fa fa-save"></i> Lưu thay đổi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview avatar khi chọn file
    document.getElementById('avatarInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) { 
                document.getElementById('avatarPreview').src = e.target.result; 
            }
            reader.readAsDataURL(file);
        }
    });

    // Lọc bộ phận theo phòng ban
    document.getElementById('phongban_select').addEventListener('change', function() {
        const phongbanId = this.value;
        const bophanSelect = document.getElementById('bophan_select');
        const options = bophanSelect.querySelectorAll('option');
        
        options.forEach(option => {
            if (option.value === '') { 
                option.style.display = 'block'; 
                return; 
            }
            const optionPhongban = option.getAttribute('data-phongban');
            if (!phongbanId || optionPhongban === phongbanId) { 
                option.style.display = 'block'; 
            } else { 
                option.style.display = 'none'; 
            }
        });
        bophanSelect.value = '';
    });
</script>
@endsection