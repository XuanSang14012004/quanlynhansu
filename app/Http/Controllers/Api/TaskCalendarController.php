<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task; // Dùng Model thay vì DB facade
use Illuminate\Support\Facades\Auth;

class TaskCalendarController extends Controller
{
    public function index()
    {
        // Trả về file giao diện Blade
        return view('calendar.index'); 
    }

    // Lấy danh sách công việc đổ vào lịch
    public function getTasks()
    {
        $currentUserId = Auth::id(); 

        // Lấy các task có ID của user đang đăng nhập trong bảng trung gian (task_user)
       $tasks = Task::with(['assignees', 'creator'])->get();

        // Format lại dữ liệu cho giống hệt với cấu trúc Javascript cũ của bạn đang dùng
        $formattedTasks = $tasks->map(function($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'start_time' => $task->start_time,
                'end_time' => $task->end_time,
                'startDate' => $task->start_date, // Chữ D viết hoa theo JS của bạn
                'endDate' => $task->end_date,     // Chữ D viết hoa theo JS của bạn
                'status' => $task->status,
                // Lấy tên tất cả người thực hiện ghép lại bằng dấu phẩy
                'assignee_name' => $task->assignees->pluck('name')->implode(', '),
                'creator_name' => $task->creator ? $task->creator->name : 'Hệ thống',
                // Truyền thêm mảng assignees để lỡ JS cần dùng
                'assignees' => $task->assignees 
            ];
        });

        return response()->json($formattedTasks);
    }

    // Hàm cập nhật trạng thái công việc
    public function completeTask($id)
    {
        // Dùng Eloquent để cập nhật cho chuẩn form
        $task = Task::find($id);

        if ($task) {
            $task->update([
                'status' => 1,
                'completed_at' => now() // Ghi nhận thời gian hoàn thành
            ]);

            return response()->json(['success' => true, 'message' => 'Cập nhật thành công']);
        }

        return response()->json(['success' => false, 'message' => 'Không tìm thấy công việc'], 404);
    }
}