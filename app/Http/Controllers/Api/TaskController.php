<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    // 1. Lấy danh sách công việc (Kèm bộ lọc)
    public function index(Request $request)
    {
        // 1. Khởi tạo Query - ĐÃ SỬA: Dùng 'assignees' thay vì 'assignee'
        $query = Task::with(['assignees:id,name', 'creator:id,name']);

        // 2. Lọc theo từ khóa (Tìm cả Tiêu đề và Mô tả)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('tasks.title', 'like', '%' . $keyword . '%')
                  ->orWhere('tasks.description', 'like', '%' . $keyword . '%');
            });
        }

        // 3. Lọc theo người thực hiện - ĐÃ SỬA: Lọc qua quan hệ Nhiều-Nhiều (whereHas)
        if ($request->filled('assignee_id') && $request->assignee_id != 'all') {
            $assigneeId = $request->assignee_id;
            $query->whereHas('assignees', function($q) use ($assigneeId) {
                $q->where('users.id', $assigneeId);
            });
        }

        // 4. Lọc theo trạng thái
        if (isset($request->status) && $request->status !== 'all') {
            $query->where('tasks.status', (int)$request->status);
        }

        // 5. Lọc theo ngày (Date Filter)
        if ($request->filled('date_filter') && $request->date_filter != 'all') {
            $filter = $request->date_filter;

            switch ($filter) {
                case 'yesterday':
                    $query->whereDate('tasks.start_date', Carbon::yesterday());
                    break;
                case 'today':
                    $query->whereDate('tasks.start_date', Carbon::today());
                    break;
                case 'tomorrow':
                    $query->whereDate('tasks.start_date', Carbon::tomorrow());
                    break;
                case 'thisWeek':
                    $query->whereBetween('tasks.start_date', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'custom':
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $query->whereBetween('tasks.start_date', [
                            $request->from_date,
                            $request->to_date
                        ]);
                    } elseif ($request->filled('from_date')) {
                        $query->whereDate('tasks.start_date', '>=', $request->from_date);
                    } elseif ($request->filled('to_date')) {
                        $query->whereDate('tasks.start_date', '<=', $request->to_date);
                    }
                    break;
            }
        }

        // 6. Sắp xếp và trả về
        $tasks = $query->orderBy('tasks.created_at', 'desc')->get();

        return response()->json($tasks);
    }

    // 2. Thêm công việc mới
    public function store(Request $request)
    {
        try {
            // Validate dữ liệu - ĐÃ SỬA: Kiểm tra mảng assignees
            $request->validate([
                'title' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'assignees' => 'required|array', // Phải là một mảng
                'assignees.*' => 'exists:users,id', // Từng ID trong mảng phải tồn tại
            ]);

            // Lấy các dữ liệu cơ bản của Task (Bỏ assignees ra khỏi create)
            $taskData = $request->except(['assignees', 'attachment']);
            $taskData['creator_id'] = Auth::id() ?? 1; // Fallback ID 1 nếu test chưa login
            $taskData['status'] = 0; // Mặc định: Chờ xử lý

            // Upload file (nếu có)
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/tasks'), $filename);
                $taskData['attachment'] = 'uploads/tasks/' . $filename;
            }

            // 1. Tạo Task mới
            $task = Task::create($taskData);

            // 2. Lưu danh sách người thực hiện vào bảng trung gian (sync)
            if ($request->has('assignees')) {
                $task->assignees()->sync($request->assignees);
            }

            return response()->json(['success' => true, 'message' => 'Tạo công việc thành công!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 3. Cập nhật công việc (Dùng cho chức năng Sửa)
    public function update(Request $request, $id)
    {
        try {
            $task = Task::find($id);
            if (!$task) return response()->json(['success' => false, 'message' => 'Không tìm thấy'], 404);

            $request->validate([
                'title' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'assignees' => 'required|array',
                'assignees.*' => 'exists:users,id',
            ]);

            $taskData = $request->except(['assignees', 'attachment', '_method']);

            // Xử lý file mới nếu có upload lại
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/tasks'), $filename);
                $taskData['attachment'] = 'uploads/tasks/' . $filename;
            }

            // 1. Cập nhật Task
            $task->update($taskData);

            // 2. Cập nhật danh sách người thực hiện (sync sẽ tự động xóa người cũ, thêm người mới)
            if ($request->has('assignees')) {
                $task->assignees()->sync($request->assignees);
            }

            return response()->json(['success' => true, 'message' => 'Cập nhật thành công']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 4. Xóa công việc
    public function destroy($id)
    {
        $task = Task::find($id);
        if ($task) {
            // Khi xóa Task, dữ liệu ở bảng trung gian cũng có thể xóa (tùy cấu hình DB)
            // Hoặc có thể xóa thủ công: $task->assignees()->detach();
            $task->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Không tìm thấy'], 404);
    }

    // 5. Hoàn thành công việc
    public function complete(Request $request, $id)
    {
        $task = Task::find($id);
        if ($task) {
            $task->status = 1; // 1 = Hoàn thành
            $task->completion_note = $request->note;
            $task->completed_at = now();
            $task->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
    //Không thực hiện
    public function notDone(Request $request, $id)
{
    $task = Task::findOrFail($id);

    $task->status = 3; // 
    $task->completion_note = $request->note;
    $task->completed_at = now();

    $task->save();

    return response()->json(['success' => true]);
}

    // 6. Chi tiết công việc
    public function show($id)
    {
        // ĐÃ SỬA: Load quan hệ 'assignees' thay vì 'assignee'
        $task = Task::with(['assignees', 'creator'])->find($id);
        return response()->json($task);
    }

    // 7. Lấy danh sách nhân viên (để đổ vào thẻ Select)
    public function getUsers()
    {
        // Chỉ lấy id và name từ bảng users để bảo mật
        $users = User::select('id', 'name')->orderBy('name')->get();
        return response()->json($users);
    }
}
