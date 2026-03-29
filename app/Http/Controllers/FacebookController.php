<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Customer; // Sử dụng Model Customer
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class FacebookController extends Controller
{
    // 1. Chuyển hướng sang Facebook
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    // 2. Xử lý khi Facebook trả về
    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();

            // Tìm xem khách hàng này đã tồn tại chưa (theo facebook_id)
            $finduser = Customer::where('facebook_id', $user->id)->first();

            if($finduser){
                // Nếu có rồi thì đăng nhập luôn
                Auth::guard('customer')->login($finduser);
                
                // Chuyển hướng thẳng vào trang Tra cứu (theo yêu cầu cũ của bạn)
                return redirect()->route('customer.goithau.search');
            }else{
                // Nếu chưa có facebook_id, kiểm tra xem email đã tồn tại chưa
                // (Tránh trùng email với người đăng ký Google hoặc đăng ký thường)
                $checkEmail = Customer::where('email', $user->email)->first();

                if($checkEmail) {
                    // Nếu email đã có, cập nhật thêm facebook_id cho tài khoản đó
                    $checkEmail->facebook_id = $user->id;
                    $checkEmail->save();
                    Auth::guard('customer')->login($checkEmail);
                } else {
                    // Nếu chưa có gì cả -> Tạo khách hàng mới
                    $newUser = Customer::create([
                        'name' => $user->name,
                        'email' => $user->email,
                        'facebook_id'=> $user->id,
                        'password' => Hash::make('123456dummy'), // Mật khẩu ngẫu nhiên
                        'avatar' => $user->avatar, // Link ảnh đại diện từ Facebook
                        'role' => 'customer' // Gán quyền mặc định
                    ]);
    
                    Auth::guard('customer')->login($newUser);
                }

                // Chuyển hướng thẳng vào trang Tra cứu
                return redirect()->route('customer.goithau.search');
            }
        } catch (Exception $e) {
            // Nếu lỗi thì quay về trang login và báo lỗi
            return redirect()->route('login')->with('error', 'Lỗi đăng nhập Facebook: ' . $e->getMessage());
        }
    }
}