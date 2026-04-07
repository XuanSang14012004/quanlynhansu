@extends('layouts.master')

@section('title', 'Lịch trình công việc')

@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="{{ route('dashboard') }}">Bảng điều khiển</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Lịch trình công việc</span>
                </li>
            </ul>
        </div>

        <h1 class="page-title"> Lịch trình công việc <small>Xem và quản lý tiến độ</small></h1>

        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered" style="border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    <div class="portlet-title" style="padding: 20px; border-bottom: 2px solid #667eea; display: flex; justify-content: space-between; align-items: center;">
                        <div class="caption">
                            <i class="fa fa-calendar" style="color: #667eea; font-size: 20px; margin-right: 10px;"></i>
                            <span style="font-size: 18px; font-weight: 700; color: #2c3e50;">Chi tiết lịch trình</span>
                        </div>

                        <div class="actions" style="display: flex; align-items: center; gap: 10px;">
                            <button class="btn btn-default btn-sm" onclick="changeMonth(-1)" style="border-radius: 50%; width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; background: #fff; border: 1px solid #ced4da; transition: all 0.2s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='#fff'">
                                <i class="fa fa-chevron-left" style="color: #667eea; font-size: 12px;"></i>
                            </button>

                            <span id="currentMonthYear" style="font-size: 15px; font-weight: 700; color: #2c3e50; background: #f8f9fa; padding: 6px 16px; border-radius: 20px; min-width: 140px; text-align: center; display: inline-block;"></span>

                            <button class="btn btn-default btn-sm" onclick="changeMonth(1)" style="border-radius: 50%; width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; background: #fff; border: 1px solid #ced4da; transition: all 0.2s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='#fff'">
                                <i class="fa fa-chevron-right" style="color: #667eea; font-size: 12px;"></i>
                            </button>

                            <button class="btn btn-sm" onclick="goToToday()" style="background: #eef2fe; color: #667eea; font-weight: 600; border: none; border-radius: 6px; padding: 6px 12px; margin-left: 5px;">
                                Hôm nay
                            </button>
                        </div>
                    </div>

                    <div class="portlet-body" style="padding: 25px;">
                        <div id="calendar" class="calendar-container"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- <div class="modal fade" id="taskDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 8px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="border-bottom: 1px solid #f0f0f0; padding: 20px 24px;">
                <h4 class="modal-title" style="font-weight: 600; color: #333; font-size: 18px; margin: 0;">Chi tiết công việc</h4>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="margin-top: -20px; font-size: 24px; color: #999; opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" style="padding: 24px;">
                <input type="hidden" id="modalTaskId">

                <div class="form-group" style="margin-bottom: 24px;">
                    <label style="font-weight: 700; color: #555; font-size: 14px; margin-bottom: 8px; display: block;">Nội dung công việc</label>
                    <div id="modalTaskTitle" style="color: #333; font-size: 15px;"></div>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label style="font-weight: 700; color: #555; font-size: 14px; margin-bottom: 8px; display: block;">Mô tả</label>
                    <div id="modalTaskDescription" style="color: #333; font-size: 15px;"></div>
                </div>

                <div class="row" style="margin-bottom: 24px;">
                    <div class="col-md-6">
                        <label style="font-weight: 700; color: #555; font-size: 14px; margin-bottom: 8px; display: block;">Thời gian</label>
                        <div id="modalTaskTime" style="color: #333; font-size: 15px;"></div>
                    </div>
                    <div class="col-md-6">
                        <label style="font-weight: 700; color: #555; font-size: 14px; margin-bottom: 8px; display: block;">Ngày thực hiện</label>
                        <div id="modalTaskDate" style="color: #333; font-size: 15px;"></div>
                    </div>
                </div>

                <div class="row" style="margin-bottom: 24px;">
                    <div class="col-md-6">
                        <label style="font-weight: 700; color: #555; font-size: 14px; margin-bottom: 8px; display: block;">Người thực hiện</label>
                        <div id="modalTaskAssignee" style="color: #333; font-size: 15px;"></div>
                    </div>
                    <div class="col-md-6">
                        <label style="font-weight: 700; color: #555; font-size: 14px; margin-bottom: 8px; display: block;">Người giao việc</label>
                        <div id="modalTaskCreator" style="color: #333; font-size: 15px;"></div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 10px;">
                    <label style="font-weight: 700; color: #555; font-size: 14px; margin-bottom: 8px; display: block;">Trạng thái</label>
                    <span id="modalTaskStatus" style="padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: 600;"></span>
                </div>
            </div>

            <div class="modal-footer" style="border-top: 1px solid #f0f0f0; padding: 16px 24px; background: #fff; border-radius: 0 0 8px 8px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal" style="background: #6c757d; border: none; padding: 8px 16px; border-radius: 4px; font-weight: 500;">Đóng</button>
                <button type="button" id="btnCompleteTask" class="btn btn-success" onclick="markTaskAsCompleted()" style="background: #198754; border: none; padding: 8px 16px; border-radius: 4px; font-weight: 500;">
                    <i class="fa fa-check-circle" style="margin-right: 5px;"></i> Hoàn thành
                </button>
            </div>
        </div>
    </div>
</div> -->
<div class="modal fade" id="dayTasksModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Danh sách công việc</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nội dung công việc</th>
                            <th>Mô tả</th>
                            <th>Thời gian</th>
                            <th>Ngày thực hiện</th>
                            <th>Người thực hiện</th>
                            <th>Người giao việc</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody id="dayTasksTable"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


<style>
    /* calendar.css */
    .calendar-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .calendar-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .calendar-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #2c3e50;
    }

    .calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
        margin-bottom: 6px;
    }

    .calendar-weekdays .weekday-label {
        text-align: center;
        font-weight: 700;
        font-size: 14px;
        color: #2c3e50;
        padding: 8px 0;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
    }

    .calendar-day {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        min-height: 100px;
        padding: 8px;
        box-sizing: border-box;
    }

    .calendar-day.empty {
        background: #f8f9fa;
        border: 1px dashed #e0e0e0;
    }

    .calendar-day .day-number {
        font-size: 13px;
        font-weight: 600;
        color: #333;
        margin-bottom: 6px;
    }

    .task-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .task-item {
        font-size: 12px;
        padding: 3px 8px;
        border-radius: 4px;
        border-left: 3px solid #4a90e2;
        background: #e8f0fe;
        color: #1a56db;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .task-count {
        font-size: 12px;
        padding: 6px;
        background: #eef2fe;
        color: #667eea;
        border-radius: 6px;
        text-align: center;
        cursor: pointer;
        font-weight: 600;
        transition: 0.2s;
    }

    .task-count:hover {
        background: #dbe4ff;
    }

    .task-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .task-item.status-1 {
        border-left-color: #28a745;
        background: #e6f4ea;
        color: #1e7e34;
    }

    .task-item.status-0 {
        border-left-color: #4a90e2;
        background: #e8f0fe;
        color: #1a56db;
    }

    #dayTasksModal .modal-dialog {
        width: 100%;
        max-width: 100%;
        margin: 10px auto;
    }

    #dayTasksModal td:nth-child(5) {
        white-space: normal;
        max-width: 250px;
        word-break: break-word;
    }
    

    #dayTasksModal td:nth-child(2) {
        white-space: normal;
        max-width: 200px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 13px;
    }

    .status-completed {
        background: #10b981;
        color: #fff;
    }

    .status-pending {
        background: #fbbf24;
        color: #000;
    }

    .status-cancel {
        background: #e5cfcf;
        color: #2c3e50;
    }

    /* 🔥 Modal to hơn */
    #dayTasksModal .modal-dialog {
        max-width: 95%;
    }

    /* 🔥 Body scroll nếu dài */
    #dayTasksModal .modal-body {
        max-height: 75vh;
        overflow-y: auto;
    }

    /* 🔥 Table đẹp hơn */
    #dayTasksModal table {
        font-size: 15px;
        border-radius: 8px;
        overflow: hidden;
    }

    /* Header */
    #dayTasksModal th {
        padding: 14px;
        font-size: 15px;
        font-weight: 700;
        background: #f4f6fb;
        text-align: center;
    }

    /* Cell */
    #dayTasksModal td {
        padding: 14px;
        vertical-align: middle;
    }

    /* Hover */
    #dayTasksModal tbody tr:hover {
        background: #f8f9ff;
        transition: 0.2s;
    }

    /* Fix xuống dòng */


    #dayTasksModal td {
        white-space: nowrap;
    }

    #dayTasksModal td:nth-child(1),
    #dayTasksModal td:nth-child(2) {
        white-space: normal;
    }

    /* Badge to hơn */
    .status-badge {
        padding: 8px 14px;
        font-size: 14px;
        border-radius: 6px;
        display: inline-block;
        min-width: 120px;
        text-align: center;
    }
</style>

<script>
    window.BASE_URL = window.location.origin + '/datatech/public';
    let tasks = [];
    let today = new Date();
    let currentViewMonth = today.getMonth(); // Lưu tháng đang hiển thị trên UI (0 - 11)
    let currentViewYear = today.getFullYear(); // Lưu năm đang hiển thị trên UI

    document.addEventListener('DOMContentLoaded', function() {
        fetchTasks();
    });

    async function fetchTasks() {
        try {
            const response = await fetch(`${window.BASE_URL}/api/calendar-tasks`);
            tasks = await response.json();
        } catch (error) {
            console.error('Lỗi lấy dữ liệu:', error);
            tasks = [];
        }
        renderCalendar();
    }

    // Hàm render lại lịch
    function renderCalendar() {
        const calendar = document.getElementById('calendar');

        // THAY ĐỔI: Sử dụng biến currentViewYear và currentViewMonth thay vì new Date()
        const year = currentViewYear;
        const month = currentViewMonth;

        // Cập nhật nhãn Tháng/Năm trên Header
        const monthYearEl = document.getElementById('currentMonthYear');
        if (monthYearEl) {
            monthYearEl.innerText = `Tháng ${month + 1} / ${year}`;
        }

        const firstDayOfMonth = new Date(year, month, 1);
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const startOffset = firstDayOfMonth.getDay();

        const weekdays = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];

        let html = `
    <div class="calendar-header" style="display: none;"> <h2>Tháng ${month + 1} ${year}</h2>
    </div>
    <div class="calendar-weekdays">
        ${weekdays.map(d => `<div class="weekday-label">${d}</div>`).join('')}
    </div>
    <div class="calendar-grid">`;

        // Vẽ ô trống đầu tháng
        for (let i = 0; i < startOffset; i++) {
            html += `<div class="calendar-day empty"></div>`;
        }

        // Vẽ các ngày trong tháng
        for (let day = 1; day <= daysInMonth; day++) {
            const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            // Kiểm tra xem ngày đang vẽ có phải là "Hôm nay" ngoài đời thực không
            const isToday = (day === today.getDate() && month === today.getMonth() && year === today.getFullYear());
            const dayStyle = isToday ? 'background: #eef2fe; border: 2px solid #667eea;' : '';
            const numberStyle = isToday ? 'background: #667eea; color: #fff; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center;' : '';

            const dayTasks = tasks.filter(t => dateString >= t.startDate && dateString <= t.endDate);

            let tasksHtml = '';

            if (dayTasks.length > 0) {
                tasksHtml = `
        <div class="task-count" onclick="openDayTasks('${dateString}')">
            +${dayTasks.length} công việc
        </div>
    `;
            }

            html += `
        <div class="calendar-day" style="${dayStyle}">
            <div class="day-number" style="${numberStyle}">${day}</div>
            <div class="task-list">${tasksHtml}</div>
        </div>`;
        }

        // Vẽ ô trống cuối tháng
        const totalCells = startOffset + daysInMonth;
        const remainder = totalCells % 7;
        if (remainder !== 0) {
            for (let i = 0; i < 7 - remainder; i++) {
                html += `<div class="calendar-day empty"></div>`;
            }
        }

        html += `</div>`;
        calendar.innerHTML = html;
    }

    // HÀM MỚI: Xử lý chuyển tháng khi bấm nút
    function changeMonth(step) {
        currentViewMonth += step;

        if (currentViewMonth < 0) {
            currentViewMonth = 11; // Quay về tháng 12 năm ngoái
            currentViewYear--;
        } else if (currentViewMonth > 11) {
            currentViewMonth = 0; // Sang tháng 1 năm sau
            currentViewYear++;
        }

        renderCalendar(); // Vẽ lại lịch với tháng/năm mới
    }

    // HÀM MỚI: Xử lý quay về hôm nay
    function goToToday() {
        currentViewMonth = today.getMonth();
        currentViewYear = today.getFullYear();
        renderCalendar();
    }

    function openTaskModal(taskId) {
        const task = tasks.find(t => t.id === taskId);
        if (!task) return;

        // Lưu lại ID để dùng cho nút Hoàn thành
        document.getElementById('modalTaskId').value = task.id;

        // Đổ dữ liệu text
        document.getElementById('modalTaskTitle').innerText = task.title;
        document.getElementById('modalTaskDescription').innerText = task.description || 'Không có mô tả chi tiết.';
        // ĐÃ SỬA: Lấy danh sách tên từ mảng assignees (nhiều người)
        if (task.assignees && task.assignees.length > 0) {
            document.getElementById('modalTaskAssignee').innerText = task.assignees.map(user => user.name).join(', ');
        } else if (task.assignee_name) { // Giữ lại đề phòng API cũ chưa cập nhật
            document.getElementById('modalTaskAssignee').innerText = task.assignee_name;
        } else {
            document.getElementById('modalTaskAssignee').innerText = 'Chưa phân công';
        }
        document.getElementById('modalTaskCreator').innerText = task.creator_name || 'Hệ thống';

        // Xử lý định dạng Thời gian (Cắt bỏ số giây, ví dụ 08:00:00 -> 08:00)
        const formatTime = (timeStr) => timeStr ? timeStr.substring(0, 5) : '--:--';
        document.getElementById('modalTaskTime').innerText = `${formatTime(task.start_time)} - ${formatTime(task.end_time)}`;

        // Xử lý định dạng Ngày (YYYY-MM-DD -> DD/MM/YYYY)
        const formatDate = (dateStr) => {
            if (!dateStr) return '';
            const parts = dateStr.split('-');
            return `${parts[2]}/${parts[1]}/${parts[0]}`;
        };
        document.getElementById('modalTaskDate').innerText = `${formatDate(task.startDate)} - ${formatDate(task.endDate)}`;

        // Xử lý Trạng thái và Nút bấm
        const statusEl = document.getElementById('modalTaskStatus');
        const btnComplete = document.getElementById('btnCompleteTask');

        if (task.status == 1) {
            statusEl.innerText = 'Đã hoàn thành';
            statusEl.style.backgroundColor = '#d1e7dd';
            statusEl.style.color = '#0f5132';
            btnComplete.style.display = 'none'; // Đã xong thì ẩn nút Hoàn thành đi
        } else {
            statusEl.innerText = 'Chờ xử lý';
            statusEl.style.backgroundColor = '#ffc107'; // Màu vàng chuẩn y hệt ảnh
            statusEl.style.color = '#000';
            btnComplete.style.display = 'inline-block'; // Chờ xử lý thì hiện nút
        }

        // Bật Modal lên
        if (typeof $ !== 'undefined') {
            $('#taskDetailModal').modal('show');
        } else if (typeof bootstrap !== 'undefined') {
            const modal = new bootstrap.Modal(document.getElementById('taskDetailModal'));
            modal.show();
        }
    }

    // HÀM XỬ LÝ KHI BẤM NÚT "HOÀN THÀNH"
    // HÀM XỬ LÝ KHI BẤM NÚT "HOÀN THÀNH" THỰC TẾ
    async function markTaskAsCompleted() {
        const taskId = document.getElementById('modalTaskId').value;

        if (confirm('Bạn xác nhận đã hoàn thành công việc này chứ?')) {
            try {
                // Gọi API xuống Laravel để cập nhật database
                const response = await fetch(`/api/calendar-tasks/${taskId}/complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Mã bảo mật bắt buộc của Laravel
                    }
                });

                const result = await response.json();

                if (result.success) {
                    // 1. Ẩn modal đi
                    if (typeof $ !== 'undefined') {
                        $('#taskDetailModal').modal('hide');
                    } else if (typeof bootstrap !== 'undefined') {
                        const modalEl = document.getElementById('taskDetailModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    }

                    // 2. Gọi lại API lấy danh sách việc mới nhất và vẽ lại lịch
                    fetchTasks();

                    // 3. Thông báo cho người dùng
                    alert('Tuyệt vời! Bạn đã hoàn thành công việc.');
                } else {
                    alert('Có lỗi xảy ra: ' + result.message);
                }
            } catch (error) {
                console.error('Lỗi khi cập nhật:', error);
                alert('Không thể kết nối đến máy chủ!');
            }
        }
    }

    function openDayTasks(date) {
        const dayTasks = tasks.filter(t => date >= t.startDate && date <= t.endDate);

        const formatTime = (timeStr) => timeStr ? timeStr.substring(0, 5) : '--:--';

        let html = '';

        dayTasks.forEach(t => {
            let assignee = 'Chưa phân công';

            if (t.assignees && t.assignees.length > 0) {
                assignee = t.assignees.map(u => u.name).join(', ');
            } else if (t.assignee_name) {
                assignee = t.assignee_name;
            }

            let statusClass = '';
            let statusText = '';

            if (t.status == 1) {
                statusClass = 'status-completed';
                statusText = 'Đã hoàn thành';
            } else if (t.status == 2) {
                statusClass = 'status-cancel';
                statusText = 'Không thực hiện';
            } else {
                statusClass = 'status-pending';
                statusText = 'Chờ xử lý';
            }

            html += `
            <tr onclick="openTaskModal(${t.id})" style="cursor:pointer">
                  <td>${t.title}</td>
                  <td>${t.description || ''}</td>
                  <td>${formatTime(t.start_time)} - ${formatTime(t.end_time)}</td>
                  <td>${t.startDate}</td>
                  <td>${assignee}</td>
                  <td>${t.creator_name || ''}</td>
                  <td>
            <span class="status-badge ${statusClass}">
                ${statusText}
            </span>
        </td>
            </tr>
        `;
        });

        document.getElementById('dayTasksTable').innerHTML = html;

        $('#dayTasksModal').modal('show');
    }
</script>