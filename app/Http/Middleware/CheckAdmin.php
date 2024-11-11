<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role_id == 1) {
            return $next($request);
        }

        // Nếu không phải admin, đăng xuất và chuyển hướng về trang đăng nhập
        Auth::logout();
        return redirect()->route('account.login')->with('errors', [
            'title' => 'Đăng nhập không thành công',
            'content' => 'Bạn không có quyền truy cập vào trang này'
        ]);;
    }
}
