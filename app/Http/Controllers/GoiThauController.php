<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class GoiThauController extends Controller
{
    public function index(Request $request)
    {
        $result = null;
        $error = null;

        // 1. Lấy trang hiện tại từ URL (ví dụ: ?page=2), mặc định là 1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 25; 

        // Chỉ gọi API khi có POST hoặc có tham số search trên URL (khi bấm chuyển trang)
        if ($request->isMethod('post') || $request->has('search')) {
            try {
                $searchInput = $request->input('search', '');
                $searchArray = array_map('trim', explode(',', $searchInput));
                
                // 2. Gửi đúng số trang ($currentPage) lên API
                $payload = [
                    'tenGoiThau'           => $searchArray,
                    'skip_direct_contract' => $request->input('skip_direct_contract'),
                    'sort_price_desc'      => $request->input('sort_price_desc') ? '1' : null,
                    'theo_tomtatcv'        => $request->input('theo_tomtatcv') ? '1' : null,
                    'pageSize'             => $perPage,
                    'pageNumber'           => $currentPage, // QUAN TRỌNG: Gửi số trang động
                ];

                // Lấy Token
                $tokenResponse = Http::get('http://dauthau360.com/api/createtoken.php');
                if ($tokenResponse->failed()) throw new \Exception('Lỗi kết nối lấy Token.');
                $token = $tokenResponse->object()->token ?? null;

                // Gọi API tìm kiếm
                $searchResponse = Http::withToken($token)
                    ->post('http://dauthau360.com/api/search_goithau.php', $payload);

                if ($searchResponse->successful()) {
                    $items = $searchResponse->object(); // Dữ liệu của trang hiện tại
                    
                    // Vì API bên thứ 3 KHÔNG TRẢ VỀ tổng số bản ghi (Total)
                    // Ta phải "giả lập" một con số lớn để hiện nút phân trang
                    // Nếu số lượng item trả về ít hơn perPage => Đã đến trang cuối
                    $fakeTotal = 10000; 
                    if (count($items) < $perPage) {
                        $fakeTotal = ($currentPage - 1) * $perPage + count($items);
                    }

                    // 3. Tạo phân trang chuẩn Laravel
                    $result = new LengthAwarePaginator(
                        $items,
                        $fakeTotal,
                        $perPage,
                        $currentPage,
                        [
                            'path' => $request->url(),
                            'query' => $request->all() // QUAN TRỌNG: Giữ lại các tham số search khi chuyển trang
                        ]
                    );

                } else {
                    $error = 'Lỗi API: ' . $searchResponse->status();
                }

            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return view('goithau.search', compact('result', 'error'));
    }
}