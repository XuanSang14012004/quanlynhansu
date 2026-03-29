<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http; // Dùng để gọi API Google
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function getLogin(){
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('login.index');
    }

    public function postLogin(Request $request){
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required|min:6|max:32' 
        ],[
            'email.required'    => 'Bạn chưa nhập "Email"',
            'email.email'       => '"Email" không đúng định dạng',
            'password.required' => 'Bạn chưa nhập "Mật khẩu"',
            'password.min'      => '"Mật khẩu" phải ít nhất 6 ký tự',
            'password.max'      => '"Mật khẩu" không quá 32 ký tự'
        ]);
        $remember = $request->has('remember') ? true : false;
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)){
            
            Log::info('Email: '.$request->email.' đăng nhập thành công');
            return redirect()->route('dashboard');
            
        }else{
            Log::error('Email: '.$request->email.' đăng nhập thất bại');
            return redirect()->route('login')->with('status_error', 'Đăng nhập thất bại, vui lòng thử lại!');
        }
    }

    public function getLogout(){
        Auth::logout();
        return redirect()->route('login');
    }


   public function login(Request $request)
{
    // 1. Validate dữ liệu đầu vào (Bắt buộc luôn cả Captcha)
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'g-recaptcha-response' => 'required' // Luôn bắt buộc
    ], [
        'g-recaptcha-response.required' => 'Vui lòng xác nhận bạn không phải là người máy.'
    ]);

    // 2. Gửi token lên Google để xác minh (Luôn thực hiện)
    try {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$response->json()['success']) {
             return back()->withErrors(['captcha' => 'Xác minh CAPTCHA thất bại.']);
        }
    } catch (\Exception $e) {
        // Trường hợp lỗi mạng không gọi được Google thì có thể cho qua hoặc báo lỗi tùy bạn
        return back()->withErrors(['captcha' => 'Lỗi kết nối xác minh CAPTCHA.']);
    }

    // 3. Tiến hành đăng nhập
    $credentials = $request->only('email', 'password');
    $remember = $request->has('remember');

    if (Auth::attempt($credentials, $remember)) {
        return redirect()->intended(route('dashboard'));
    }

    // Đăng nhập thất bại
    return back()->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.']);
}
}
