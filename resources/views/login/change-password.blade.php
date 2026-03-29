@extends('layouts.master')

@section('title', 'Đổi mật khẩu')

@section('content')
<style>
    .password-change-container {
        /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
        min-height: calc(100vh - 200px);
        padding: 40px 0;
    }
    
    .password-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    
    .password-card:hover {
        transform: translateY(-5px);
    }
    
    .card-header-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        text-align: center;
        color: white;
    }
    
    .card-header-custom i {
        font-size: 48px;
        margin-bottom: 15px;
        display: block;
    }
    
    .card-header-custom h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }
    
    .card-header-custom p {
        margin: 5px 0 0 0;
        opacity: 0.9;
        font-size: 14px;
    }
    
    .card-body-custom {
        padding: 40px;
    }
    
    .form-group-custom {
        margin-bottom: 25px;
        position: relative;
    }
    
    .form-group-custom label {
        display: block;
        margin-bottom: 8px;
        color: #555;
        font-weight: 500;
        font-size: 14px;
    }
    
    .input-wrapper {
        position: relative;
    }
    
    .input-wrapper i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #667eea;
        font-size: 16px;
    }
    
    .form-control-custom {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border: 2px solid #e1e8ed;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
    
    .form-control-custom:focus {
        outline: none;
        border-color: #667eea;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .form-group-custom.has-error .form-control-custom {
        border-color: #e74c3c;
    }
    
    .help-block {
        color: #e74c3c;
        font-size: 13px;
        margin-top: 5px;
        display: block;
    }
    
    .alert-success-custom {
        background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        color: #fff;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .alert-success-custom .close {
        background: none;
        border: none;
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        opacity: 0.8;
        transition: opacity 0.2s;
    }
    
    .alert-success-custom .close:hover {
        opacity: 1;
    }
    
    .form-actions-custom {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }
    
    .btn-custom {
        flex: 1;
        padding: 14px 30px;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }
    
    .btn-secondary-custom {
        background: #f8f9fa;
        color: #555;
        border: 2px solid #e1e8ed;
    }
    
    .btn-secondary-custom:hover {
        background: #e1e8ed;
    }
    
    .security-tips {
        background: #f8f9fa;
        border-left: 4px solid #667eea;
        padding: 15px;
        border-radius: 8px;
        margin-top: 25px;
    }
    
    .security-tips h4 {
        margin: 0 0 10px 0;
        color: #667eea;
        font-size: 14px;
        font-weight: 600;
    }
    
    .security-tips ul {
        margin: 0;
        padding-left: 20px;
        font-size: 13px;
        color: #666;
    }
    
    .security-tips li {
        margin-bottom: 5px;
    }
</style>

<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="{{ route('dashboard') }}">Bảng điều khiển</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Đổi mật khẩu</span>
                </li>
            </ul>
        </div>
        
        <div class="password-change-container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="password-card">
                        <div class="card-header-custom">
                            <i class="fa fa-shield"></i>
                            <h3>Đổi Mật Khẩu</h3>
                            <p>Cập nhật thông tin bảo mật cá nhân</p>
                        </div>
                        
                        <div class="card-body-custom">
                            {{-- Hiển thị thông báo thành công --}}
                            @if (session('status'))
                                <div class="alert-success-custom">
                                    <span><i class="fa fa-check-circle"></i> {{ session('status') }}</span>
                                    <button class="close" data-close="alert">&times;</button>
                                </div>
                            @endif

                            <form role="form" action="{{ route('password.update') }}" method="POST">
                                @csrf
                                
                                {{-- Mật khẩu hiện tại --}}
                                <div class="form-group-custom @error('current_password') has-error @enderror">
                                    <label>Mật khẩu hiện tại</label>
                                    <div class="input-wrapper">
                                        <i class="fa fa-lock"></i>
                                        <input type="password" name="current_password" class="form-control-custom" placeholder="Nhập mật khẩu đang dùng" autocomplete="current-password">
                                    </div>
                                    @error('current_password')
                                        <span class="help-block"><i class="fa fa-exclamation-circle"></i> {{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Mật khẩu mới --}}
                                <div class="form-group-custom @error('new_password') has-error @enderror">
                                    <label>Mật khẩu mới</label>
                                    <div class="input-wrapper">
                                        <i class="fa fa-key"></i>
                                        <input type="password" name="new_password" class="form-control-custom" placeholder="Nhập mật khẩu mới" autocomplete="new-password">
                                    </div>
                                    @error('new_password')
                                        <span class="help-block"><i class="fa fa-exclamation-circle"></i> {{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Xác nhận mật khẩu mới --}}
                                <div class="form-group-custom">
                                    <label>Xác nhận mật khẩu mới</label>
                                    <div class="input-wrapper">
                                        <i class="fa fa-check"></i>
                                        <input type="password" name="new_password_confirmation" class="form-control-custom" placeholder="Nhập lại mật khẩu mới" autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="form-actions-custom">
                                    <button type="submit" class="btn-custom btn-primary-custom">
                                        <i class="fa fa-check"></i> Xác nhận đổi
                                    </button>
                                    <button type="button" class="btn-custom btn-secondary-custom" onclick="window.history.back()">
                                        <i class="fa fa-times"></i> Quay lại
                                    </button>
                                </div>
                            </form>
                            
                            <div class="security-tips">
                                <h4><i class="fa fa-info-circle"></i> Lưu ý bảo mật:</h4>
                                <ul>
                                    <li>Mật khẩu nên có ít nhất 8 ký tự</li>
                                    <li>Không sử dụng mật khẩu dễ đoán</li>
                                    <li>Thay đổi mật khẩu định kỳ để bảo mật tốt hơn</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
// Tự động đóng thông báo sau 5 giây
document.addEventListener('DOMContentLoaded', function() {
    const closeButtons = document.querySelectorAll('[data-close="alert"]');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.parentElement.style.animation = 'slideDown 0.3s ease reverse';
            setTimeout(() => {
                this.parentElement.remove();
            }, 300);
        });
        
        // Tự động đóng sau 5 giây
        setTimeout(() => {
            if (button.parentElement) {
                button.click();
            }
        }, 5000);
    });
});
</script>
@endsection