<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class ZaloController extends Controller
{
    // 1. Chuyển hướng sang Zalo
    public function redirectToZalo()
    {
        return Socialite::driver('zalo')->redirect();
    }

    // 2. Xử lý khi Zalo trả về
    public function handleZaloCallback()
    {
        try {
            $user = Socialite::driver('zalo')->user();
            
            // Debug: Xem dữ liệu Zalo trả về (nếu cần)
            // dd($user);

            // Tìm user theo zalo_id
            $finduser = Customer::where('zalo_id', $user->id)->first();

            if($finduser){
                Auth::guard('customer')->login($finduser);
                return redirect()->route('customer.goithau.search');
            }else{
                // Lưu ý: Zalo có thể KHÔNG trả về Email nếu User không chia sẻ quyền đó.
                // Ta cần xử lý trường hợp không có email.
                
                // Kiểm tra email (nếu Zalo trả về email)
                $email = $user->email ?? $user->id . '@zalo.me'; // Tạo email giả nếu không có
                
                $checkEmail = Customer::where('email', $email)->first();

                if($checkEmail) {
                    $checkEmail->zalo_id = $user->id;
                    $checkEmail->save();
                    Auth::guard('customer')->login($checkEmail);
                } else {
                    $newUser = Customer::create([
                        'name' => $user->name,
                        'email' => $email,
                        'zalo_id'=> $user->id,
                        'password' => Hash::make('123456dummy'),
                        'avatar' => $user->avatar,
                        'role' => 'customer'
                    ]);
    
                    Auth::guard('customer')->login($newUser);
                }
                return redirect()->route('customer.goithau.search');
            }
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Lỗi đăng nhập Zalo: ' . $e->getMessage());
        }
    }
}