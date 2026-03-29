<?php
namespace App\Http\Controllers;

use App\Models\Customer; // <--- Nhớ đổi model
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // 1. Tìm trong bảng customers
            $customer = Customer::where('google_id', $googleUser->id)->first();

            if (!$customer) {
                // 2. Nếu chưa có thì tìm theo email
                $customer = Customer::where('email', $googleUser->email)->first();

                if ($customer) {
                    // Cập nhật google_id nếu trùng email
                    $customer->google_id = $googleUser->id;
                    $customer->save();
                } else {
                    // 3. Tạo mới customer
                    $customer = Customer::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'password' => null, // Không cần pass
                    ]);
                }
            }

            // 4. ĐĂNG NHẬP (QUAN TRỌNG: Dùng guard 'customer')
            Auth::guard('customer')->login($customer);

            // 5. Chuyển hướng về trang dành riêng cho khách hàng
            return redirect()->route('customer.goithau.search');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Lỗi đăng nhập!');
        }
    }
}
