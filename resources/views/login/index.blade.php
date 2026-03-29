@extends('layouts.login')

@section('title', 'Đăng nhập hệ thống')

<style>
    .btn-social {
        position: relative;
        text-align: left;
        color: white !important;
        padding: 10px 15px;
        border: none;
        font-weight: 600;
        border-radius: 4px;
        /* Bo nhẹ góc */
        display: flex;
        align-items: center;
        text-decoration: none !important;
        transition: opacity 0.2s;
    }

    .btn-social:hover {
        opacity: 0.9;
    }

    .icon-wrapper {
        width: 30px;
        text-align: center;
        border-right: 1px solid rgba(255, 255, 255, 0.2);
        margin-right: 15px;
        font-size: 16px;
    }

    .text-wrapper {
        flex-grow: 1;
        text-align: center;
        font-size: 14px;
        text-transform: uppercase;
        /* Chữ in hoa cho đẹp */
    }

    /* Màu thương hiệu */
    .btn-google {
        background-color: #db4437;
    }

    .btn-facebook {
        background-color: #4267b2;
    }

    .btn-zalo {
        background-color: #0068ff;
    }
</style>

@section('content')
    <!-- BEGIN LOGO -->
    <div class="logo">
        <!-- <a href="#">
                                            <img src="{{ asset('/uploads/logos') . '/' . setting('company.logo', '') }}" alt="HRM - Thinh Phong Co., Ltd" />
                                        </a> -->
        <h3 class="font-green">PHẦN MỀM NHÂN SỰ (Bản thử nghiệm)</h3>
    </div>
    <!-- END LOGO -->
    <!-- BEGIN LOGIN -->
    <div class="content" style="margin-top: 0px;">
        <!-- BEGIN LOGIN FORM -->
        <form class="login-form" action="{{ route('login.post') }}" method="post">
            @csrf()
            <h3 class="form-title font-green">Đăng Nhập</h3>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <button class="close" data-close="alert"></button>
                    @foreach ($errors->all() as $error)
                        <p> {{ $error }} </p>
                    @endforeach
                </div>
            @endif

            <!-- MESSAGE -->
            @include('partials.flash-message')

            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label visible-ie8 visible-ie9">Email</label>
                <input value="" class="form-control form-control-solid placeholder-no-fix" type="text"
                    autocomplete="off" placeholder="Email" name="email" />
            </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Mật khẩu</label>
                <input value="" class="form-control form-control-solid placeholder-no-fix" type="password"
                    autocomplete="off" placeholder="Mật khẩu" name="password" />
            </div>

            <div class="form-group" style="margin-top: 15px; margin-bottom: 15px;">
                <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
                @if ($errors->has('g-recaptcha-response'))
                    <span class="help-block" style="color: red; font-size: 13px;">
                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                    </span>
                @endif
                @if ($errors->has('captcha'))
                    <span class="help-block" style="color: red; font-size: 13px;">
                        <strong>{{ $errors->first('captcha') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn green uppercase">Đăng nhập</button>
                <label class="rememberme check mt-checkbox mt-checkbox-outline">
                    <input type="checkbox" name="remember" value="1" />Nhớ mật khẩu?
                    <span></span>
                </label>
            </div>

            <div class="social-auth-links">
                <p style="text-align: center; color: #777; font-size: 13px; margin-bottom: 15px;">— Hoặc đăng nhập qua mạng
                    xã hội —</p>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="{{ route('login.google') }}" class="btn btn-block btn-social btn-google">
                        <span class="icon-wrapper"><i class="fa fa-google"></i></span>
                        <span class="text-wrapper">Google</span>
                    </a>

                    <a href="{{ route('login.facebook') }}" class="btn btn-block btn-social btn-facebook">
                        <span class="icon-wrapper"><i class="fa fa-facebook"></i></span>
                        <span class="text-wrapper">Facebook</span>
                    </a>

                    <a href="{{ route('login.zalo') }}" class="btn btn-block btn-social btn-zalo">
                        <span class="icon-wrapper" style="font-weight: 900; font-family: Arial;">Z</span>
                        <span class="text-wrapper">Zalo</span>
                    </a>
                </div>
            </div>

        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Mật khẩu</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>superadministrator@app.com</td>
                    <td>password</td>
                    <td><a class="copy-account" href="#" data-account="superadministrator@app.com"
                            data-password="password">copy</a></td>
                </tr>
                <tr>
                    <td>administrator@app.com</td>
                    <td>password</td>
                    <td><a class="copy-account" href="#" data-account="administrator@app.com"
                            data-password="password">copy</a></td>
                </tr>
                <tr>
                    <td>user@app.com</td>
                    <td>password</td>
                    <td><a class="copy-account" href="#" data-account="user@app.com" data-password="password">copy</a>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- END LOGIN FORM -->
    </div>
    <div class="copyright"> v1.0.0-beta - 2018 © <a href="{{ setting('company.website', '') }}"
            target="_blank">{{ setting('company.name', '') }}</a></div>

    <!--[if lt IE 9]>
                                    <script src="../assets/global/plugins/respond.min.js"></script>
                                    <script src="../assets/global/plugins/excanvas.min.js"></script>
                                    <script src="../assets/global/plugins/ie8.fix.min.js"></script>
                                    <![endif]-->
@endsection

@section('script')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        $(document).ready(function() {
            $('.copy-account').on('click', function(e) {
                e.preventDefault();
                var $account = $(this).data('account');
                var $password = $(this).data('password');
                // console.log($account + $password);
                $('.login-form input[name="email"]').val($account);
                $('.login-form input[name="password"]').val($password);
            });
        });
    </script>
@endsection()
