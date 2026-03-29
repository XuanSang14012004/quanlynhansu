<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MyTaskApiController extends Controller
{
    // 1. Lấy danh sách công việc của tôi (Có lọc & Tìm kiếm)
    public function index(Request $request)
    {
        $user = Auth::user();

        // ĐÃ SỬA: Thay assignee_id bằng whereHas('assignees')
        $query = Task::whereHas('assignees', function($q) use ($user) {
            $q->where('users.id', $user->id);
        })->with('creator:id,name');

        // Tìm kiếm theo tên hoặc mô tả
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // LỌC NGÀY THÁNG ĐƯỢC BỔ SUNG Ở ĐÂY
        if ($request->has('date_filter') && $request->date_filter !== 'all') {
            $filter = $request->date_filter;
            $now = Carbon::now();

            if ($filter === 'today') {
                $query->whereDate('start_date', '<=', $now->toDateString())
                      ->whereDate('end_date', '>=', $now->toDateString());
            } elseif ($filter === 'yesterday') {
                $yesterday = $now->copy()->subDay()->toDateString();
                $query->whereDate('start_date', '<=', $yesterday)
                      ->whereDate('end_date', '>=', $yesterday);
            } elseif ($filter === 'tomorrow') {
                $tomorrow = $now->copy()->addDay()->toDateString();
                $query->whereDate('start_date', '<=', $tomorrow)
                      ->whereDate('end_date', '>=', $tomorrow);
            } elseif ($filter === 'thisWeek') {
                $startOfWeek = $now->copy()->startOfWeek()->toDateString();
                $endOfWeek = $now->copy()->endOfWeek()->toDateString();
                // Lấy các task có thời gian bắt đầu hoặc kết thúc trong tuần này
                $query->where(function($q) use ($startOfWeek, $endOfWeek) {
                    $q->whereBetween('start_date', [$startOfWeek, $endOfWeek])
                      ->orWhereBetween('end_date', [$startOfWeek, $endOfWeek]);
                });
            } elseif ($filter === 'custom') {
                // Chỉ chạy từ ngày - đến ngày nếu chọn custom
                if ($request->has('from_date') && $request->from_date) {
                    $query->whereDate('start_date', '>=', $request->from_date);
                }
                if ($request->has('to_date') && $request->to_date) {
                    $query->whereDate('end_date', '<=', $request->to_date);
                }
            }
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($tasks);
    }

    // 2. Xem chi tiết một công việc
    public function show($id)
    {
        // ĐÃ SỬA: Thay assignee_id bằng whereHas('assignees')
        $task = Task::where('id', $id)
                    ->whereHas('assignees', function($q) {
                        $q->where('users.id', Auth::id());
                    })
                    ->with('creator:id,name')
                    ->first();

        if (!$task) {
            return response()->json(['message' => 'Không tìm thấy công việc'], 404);
        }

        return response()->json($task);
    }

    // 3. Báo cáo hoàn thành công việc
    public function markAsComplete(Request $request, $id)
    {
        // ĐÃ SỬA: Thay assignee_id bằng whereHas('assignees')
        $task = Task::where('id', $id)
                    ->whereHas('assignees', function($q) {
                        $q->where('users.id', Auth::id());
                    })
                    ->first();

        if (!$task) {
            return response()->json(['message' => 'Không tìm thấy công việc'], 404);
        }

        $task->update([
            'status' => 1, // 1 là Hoàn thành (Dựa trên data của bạn)
            'completion_note' => $request->note,
            'completed_at' => Carbon::now()
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Đã cập nhật trạng thái thành công!'
        ]);
    }
}