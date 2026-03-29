<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    // Kiểm tra nếu đã đăng nhập và tài khoản chưa kích hoạt (active == 0)
    if (auth()->check() && auth()->user()->active == 0) {
        // Đăng xuất hoặc báo lỗi
        auth()->logout();
        return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị khóa.');
    }

    return $next($request);
}
}
