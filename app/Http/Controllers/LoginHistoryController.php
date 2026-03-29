<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoginHistory;

class LoginHistoryController extends Controller
{
   public function index(Request $request)
    {
        // 1. Khởi tạo query
        $query = LoginHistory::with('user');

        // 2. Kiểm tra nếu có từ khóa tìm kiếm
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            
            // Tìm trong bảng users (liên kết qua user_id)
            // Lọc theo Tên HOẶC Email chứa từ khóa
            $query->whereHas('user', function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        }


        if ($request->has('date_from') && $request->date_from != '') {
            // Lấy từ 00:00:00 của ngày bắt đầu
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            // Lấy đến 23:59:59 của ngày kết thúc
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // 3. Lấy dữ liệu và phân trang 
        $histories = $query->latest()->paginate(10)->appends(['keyword' => $request->keyword,'date_from' => $request->date_from,
                'date_to' => $request->date_to]);
        
        return view('login_history.index', compact('histories'));
    }
}