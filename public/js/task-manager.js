/**
 * TASK MANAGER LOGIC - FINAL FIXED VERSION
 */
window.BASE_URL = window.location.origin + '/datatech/public';
document.addEventListener('DOMContentLoaded', function () {
    loadUsers();
    loadTasks();
});

// =============================================================
// 1. CÁC HÀM XỬ LÝ SỰ KIỆN (UI)
// =============================================================

// Hàm cầu nối (HTML gọi hàm này)
function applyFilters() {
    loadTasks();
}
function applyMyFilters() {
    loadTasks(); // ĐÃ SỬA: Gọi về loadTasks chung
}

// Xử lý ẩn/hiện ô chọn ngày của TAB CHUNG
function handleDateFilterChange() {
    const customDiv = document.getElementById('customDateRange');
    const customRadio = document.getElementById('custom');

    if (customRadio && customRadio.checked) {
        if (customDiv) customDiv.classList.remove('d-none');
    } else {
        if (customDiv) customDiv.classList.add('d-none');
        loadTasks();
    }
}

// Xử lý ẩn/hiện ô chọn ngày của TAB CỦA TÔI
function handleMyDateFilterChange() {
    const customDiv = document.getElementById('myCustomDateRange');
    const customRadio = document.getElementById('myCustom');

    if (customRadio && customRadio.checked) {
        if (customDiv) customDiv.classList.remove('d-none');
    } else {
        if (customDiv) customDiv.classList.add('d-none');

        // --- ĐÃ SỬA LỖI Ở ĐÂY: Gọi loadTasks() thay vì loadMyTasks() ---
        loadTasks();
    }
}

// Xóa trắng bộ lọc - ✅ HỖ TRỢ FLATPICKR
function clearFilters() {
    // Reset ô tìm kiếm chung
    const searchEl = document.getElementById('searchText');
    if (searchEl) searchEl.value = '';

    // Reset Tab Chung
    if (document.getElementById('assigneeFilter')) document.getElementById('assigneeFilter').value = 'all';
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = 'all';
    if (document.getElementById('all')) document.getElementById('all').checked = true;
    if (document.getElementById('customDateRange')) document.getElementById('customDateRange').classList.add('d-none');

    // ✅ XÓA FLATPICKR - Kiểm tra xem có dùng Flatpickr không
    const fromDateEl = document.getElementById('fromDate');
    const toDateEl = document.getElementById('toDate');

    if (fromDateEl) {
        // Kiểm tra xem có Flatpickr instance không
        if (fromDateEl._flatpickr) {
            fromDateEl._flatpickr.clear(); // ✅ Xóa bằng method của Flatpickr
        } else {
            fromDateEl.value = ''; // Xóa input thường
        }
    }

    if (toDateEl) {
        if (toDateEl._flatpickr) {
            toDateEl._flatpickr.clear(); // ✅ Xóa bằng method của Flatpickr
        } else {
            toDateEl.value = '';
        }
    }

    // Reset Tab Của Tôi (nếu có)
    if (document.getElementById('myAll')) document.getElementById('myAll').checked = true;
    if (document.getElementById('myCustomDateRange')) document.getElementById('myCustomDateRange').classList.add('d-none');

    const myFromDateEl = document.getElementById('myFromDate');
    const myToDateEl = document.getElementById('myToDate');

    if (myFromDateEl) {
        if (myFromDateEl._flatpickr) {
            myFromDateEl._flatpickr.clear();
        } else {
            myFromDateEl.value = '';
        }
    }

    if (myToDateEl) {
        if (myToDateEl._flatpickr) {
            myToDateEl._flatpickr.clear();
        } else {
            myToDateEl.value = '';
        }
    }

    // Tải lại
    loadTasks();
}

// =============================================================
// 2. HÀM TẢI DỮ LIỆU & RENDER (QUAN TRỌNG NHẤT)
// =============================================================
function loadTasks() {

    // 1. XÁC ĐỊNH ĐANG Ở TAB NÀO?
    const myTasksTab = document.getElementById('myTasksTab');
    const isMyTab = myTasksTab && window.getComputedStyle(myTasksTab).display !== 'none';

    // 2. THIẾT LẬP CÁC BIẾN DỰA TRÊN TAB
    let dateRadioName, fromDateId, toDateId, tbodyId, assigneeId;

    if (isMyTab) {
        // --- CẤU HÌNH CHO TAB "CỦA TÔI" ---
        dateRadioName = 'myDateFilter';
        fromDateId = 'myFromDate';
        toDateId = 'myToDate';
        tbodyId = 'tasksTableBody';
        assigneeId = (typeof CURRENT_USER_ID !== 'undefined') ? CURRENT_USER_ID : 0;
    } else {
        // --- CẤU HÌNH CHO TAB "QUẢN LÝ CHUNG" ---
        dateRadioName = 'dateFilter';
        fromDateId = 'fromDate';
        toDateId = 'toDate';
        tbodyId = 'tasksTableBody';
        assigneeId = document.getElementById('assigneeFilter')?.value || 'all';
    }

    // 3. LẤY DỮ LIỆU CHUNG
    let keyword = document.getElementById('searchText')?.value || '';
    let status = document.getElementById('statusFilter')?.value || 'all';

    // 4. XỬ LÝ LOGIC NGÀY - ✅ ĐÃ SỬA
    let dateFilter = 'all';
    let fromDate = '';
    let toDate = '';

    if (keyword.length > 0) {
        // Có tìm kiếm -> Bỏ qua bộ lọc ngày
        dateFilter = 'all';
    } else {
        // Lấy giá trị từ Radio button tương ứng với Tab đang mở
        const checkedRadio = document.querySelector(`input[name="${dateRadioName}"]:checked`);

        if (checkedRadio) {
            dateFilter = checkedRadio.value;

            // ✅ CHỈ LẤY NGÀY KHI CHỌN "CUSTOM"
            if (dateFilter === 'custom') {
                fromDate = document.getElementById(fromDateId)?.value || '';
                toDate = document.getElementById(toDateId)?.value || '';
            }
            // Nếu không phải custom -> fromDate và toDate vẫn là rỗng
        }
    }

    // 5. GỌI API
    let url = `${BASE_URL}/api/tasks?keyword=${encodeURIComponent(keyword)}&assignee_id=${assigneeId}&status=${status}&date_filter=${dateFilter}&from_date=${fromDate}&to_date=${toDate}`;
    console.log(url);
    let tbody = document.getElementById(tbodyId);
    if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-muted">Đang tải dữ liệu...</td></tr>`;

    fetch(url)
        .then(res => res.json())
        .then(tasks => {
            if (!tbody) return;
            tbody.innerHTML = '';

            if (tasks.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-muted"><i>Không tìm thấy kết quả nào.</i></td></tr>`;
                return;
            }

            tasks.forEach(task => {
                let statusBadge = getStatusBadge(task.status);


                let assigneeHtml = '';

                // Kiểm tra nếu mảng assignees có tồn tại và có ít nhất 1 người
                if (task.assignees && task.assignees.length > 0) {
                    // Lấy ra tên của tất cả những người thực hiện và ghép lại bằng dấu phẩy
                    let names = task.assignees.map(user => user.name).join(', ');

                    // Nếu ở tab "Của tôi", có thể ghi thêm chữ "Tôi" nếu muốn, nhưng ghép tên là chuẩn nhất
                    assigneeHtml = `<span class="fw-bold text-dark">${names}</span>`;
                } else {
                    assigneeHtml = '<span class="text-muted fst-italic">Chưa giao</span>';
                }

                let buttons = '';
                buttons += `<button class="btn btn-sm btn-primary me-1" onclick="editTask(${task.id})"><i class="bi bi-pencil"></i></button>`;
                buttons += `<button class="btn btn-sm btn-danger me-1" onclick="openDeleteModal(${task.id})"><i class="bi bi-trash"></i></button>`;
                if (task.status == 0) {
                    buttons += `<button class="btn btn-sm btn-success" onclick="openCompleteModal(${task.id}, '${task.title.replace(/'/g, "\\'")}')" title="Xác nhận hoàn thành"><i class="bi bi-check-lg"></i></button>`;
                    
                }

                let timeRange = (task.start_time ? task.start_time.substring(0, 5) : '--') + ' - ' + (task.end_time ? task.end_time.substring(0, 5) : '--');

                let row = `
                    <tr>
                        <td>
                            <div class="fw-bold text-primary">${task.title}</div>
                            <small class="text-muted">${task.description ? task.description.substring(0, 50) + '...' : ''}</small>
                        </td>
                        <td><small>${timeRange}</small></td>
                        <td>${formatDate(task.start_date)} <br> ${formatDate(task.end_date)}</td>
                        <td>${assigneeHtml}</td>
                        <td>${statusBadge}</td>
                        <td class="text-center text-nowrap">${buttons}</td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        })
        .catch(err => {
            console.error(err);
            if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Lỗi API: ${err.message}</td></tr>`;
        });
}

function switchTab(tabName) {
    document.querySelectorAll('.module-tab').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));

    const targetTab = document.getElementById(tabName + 'Tab');
    if (targetTab) targetTab.style.display = 'block';

    // GỌI HÀM LOAD LẠI DỮ LIỆU KHI CHUYỂN TAB
    loadTasks();
}

function loadUsers() {
    fetch(BASE_URL + '/api/users-list')
        .then(res => res.json())
        .then(users => {
            let filterSelect = document.getElementById('assigneeFilter');
            let formSelect = document.getElementById('taskAssignee');
            let html = '';
            users.forEach(u => html += `<option value="${u.id}">${u.name}</option>`);
            if (filterSelect) filterSelect.innerHTML = '<option value="all">Tất cả nhân viên</option>' + html;
            if (formSelect) formSelect.innerHTML = html; // Đã bỏ "Chọn nhân viên" vì Select2 tự xử lý placeholder
        })
        .catch(err => console.error(err));
}

// =============================================================
// ĐÃ SỬA: Hàm Lưu Công Việc (Gửi mảng ID)
// =============================================================
function saveTask() {
    let id = document.getElementById('taskId').value;
    let form = document.getElementById('taskForm');

    if (!document.getElementById('taskContent').value) { alert('Vui lòng nhập tên công việc'); return; }
    if (!document.getElementById('taskStartDate').value) { alert('Vui lòng chọn ngày bắt đầu'); return; }

    let formData = new FormData(form);
    formData.append('title', document.getElementById('taskContent').value);
    formData.append('description', document.getElementById('taskDescription').value);
    formData.append('start_date', document.getElementById('taskStartDate').value);
    formData.append('end_date', document.getElementById('taskEndDate').value);
    formData.append('start_time', document.getElementById('taskStartTime').value);
    formData.append('end_time', document.getElementById('taskEndTime').value);
    formData.append('status', document.getElementById('taskStatus').value);

    // Lấy mảng ID người thực hiện từ Select2
    let assignees = $('#taskAssignee').val();
    if (assignees && assignees.length > 0) {
        assignees.forEach(function (userId) {
            formData.append('assignees[]', userId);
        });
    } else {
        alert('Vui lòng chọn ít nhất 1 người thực hiện');
        return;
    }

    let url = id
        ? `${BASE_URL}/api/tasks/${id}`
        : `${BASE_URL}/api/tasks`;
    if (id) formData.append('_method', 'PUT');

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                hideModalSafely('taskModal');
                loadTasks();
                alert('Thành công!');
            } else {
                alert('Lỗi: ' + (data.message || 'Kiểm tra lại dữ liệu'));
            }
        });
}

function executeDelete() {
    let id = document.getElementById('deleteTaskId').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    fetch(`${BASE_URL}/api/tasks/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    }).then(res => res.json()).then(data => {
        if (data.success) { hideModalSafely('deleteModal'); loadTasks(); }
    });
}

function confirmComplete() {
    let id = document.getElementById('completeTaskId').value;
    let note = document.getElementById('completionNote').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    fetch(`${BASE_URL}/api/tasks/${id}/complete`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ note: note })
    }).then(res => res.json()).then(data => {
        if (data.success) { hideModalSafely('completeModal'); loadTasks(); }
    });
}
function confirmNotDone() {
    let id = document.getElementById("completeTaskId").value;
    let note = document.getElementById("completionNote").value.trim();
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    // Bắt buộc nhập ghi chú
    if (note === "") {
        alert("Vui lòng nhập ghi chú khi chọn 'Không thực hiện'");
        return;
    }

    fetch(`${BASE_URL}/api/tasks/${id}/not-done`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ note: note })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                hideModalSafely('completeModal');
                loadTasks();
            }
        });
}

function getStatusBadge(status) {
    if (status == 0) return '<span class="badge bg-warning text-dark">Chờ xử lý</span>';
    if (status == 1) return '<span class="badge bg-success">Đã hoàn thành</span>';
    if (status == 2) return '<span class="badge bg-secondary">Hết hạn</span>';
    if (status == 3) return '<span class="badge bg-danger">Không thực hiện</span>';
    return '';
}

function formatDate(dateString) {
    // 1. Kiểm tra dữ liệu đầu vào
    if (!dateString) return '';

    // 2. Tạo đối tượng Date (Lưu ý: dateString từ Laravel thường là YYYY-MM-DD)
    const date = new Date(dateString);

    // 3. Kiểm tra xem ngày có hợp lệ không
    if (isNaN(date.getTime())) return dateString;

    // 4. Lấy ngày, tháng, năm và thêm số 0 ở đầu nếu nhỏ hơn 10
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Tháng trong JS bắt đầu từ 0
    const year = date.getFullYear();

    // 5. Trả về chuỗi định dạng dd/mm/yyyy
    return `${day}/${month}/${year}`;
}

// =============================================================
// ĐÃ SỬA: Khởi tạo Dialog Thêm mới (Xóa trắng Select2)
// =============================================================
function openTaskDialog() {
    document.getElementById('taskForm').reset();
    document.getElementById('taskId').value = '';
    document.getElementById('taskStatus').value = '0';

    // Reset Select2
    $('#taskAssignee').val(null).trigger('change');

    document.getElementById('taskModalTitle').innerText = "Thêm công việc mới";

    //  set ngày hôm nay
    const today = new Date().toISOString().split('T')[0];

    document.getElementById('taskStartDate').value = today;
    document.getElementById('taskEndDate').value = today;

    showModalSafely('taskModal');
}

// =============================================================
// ĐÃ SỬA: Nạp thông tin lúc Sửa Công Việc (Set Select2)
// =============================================================
function editTask(id) {
    fetch(`${BASE_URL}/api/tasks/${id}`).then(res => res.json()).then(task => {
        document.getElementById('taskId').value = task.id;
        document.getElementById('taskContent').value = task.title;
        document.getElementById('taskDescription').value = task.description || '';
        document.getElementById('taskStartDate').value = task.start_date;
        document.getElementById('taskEndDate').value = task.end_date;
        document.getElementById('taskStartTime').value = task.start_time || '';
        document.getElementById('taskEndTime').value = task.end_time || '';
        document.getElementById('taskStatus').value = task.status ?? 0;

        // Load danh sách người thực hiện lên Select2
        if (task.assignees && task.assignees.length > 0) {
            let assigneeIds = task.assignees.map(user => user.id);
            $('#taskAssignee').val(assigneeIds).trigger('change');
        } else if (task.assignee_id) { // Fallback dự phòng
            $('#taskAssignee').val([task.assignee_id]).trigger('change');
        } else {
            $('#taskAssignee').val(null).trigger('change');
        }

        document.getElementById('taskModalTitle').innerText = "Cập nhật công việc";
        showModalSafely('taskModal');
    });
}

function viewTask(id) {
    fetch(`${BASE_URL}/api/tasks/${id}`).then(res => res.json()).then(task => {
        document.getElementById('viewContent').innerText = task.title;
        document.getElementById('viewDescription').innerText = task.description || '';
        showModalSafely('viewModal');
    });
}

function openDeleteModal(id) {
    document.getElementById('deleteTaskId').value = id;
    showModalSafely('deleteModal');
}
function openCompleteModal(id, title) {
    document.getElementById('completeTaskId').value = id;
    document.getElementById('completeTaskContent').innerText = title;
    showModalSafely('completeModal');
}

function showModalSafely(modalId) {
    const el = document.getElementById(modalId);
    if (typeof bootstrap !== 'undefined') { const m = bootstrap.Modal.getOrCreateInstance(el); m.show(); }
    else if (typeof $ !== 'undefined') { $(el).modal('show'); }
}
function hideModalSafely(modalId) {
    const el = document.getElementById(modalId);
    if (typeof bootstrap !== 'undefined') { const m = bootstrap.Modal.getInstance(el); if (m) m.hide(); }
    else if (typeof $ !== 'undefined') { $(el).modal('hide'); }
}

// Validation ngày tháng
document.addEventListener('DOMContentLoaded', function () {
    // Cho tab chung
    const fromDateInput = document.getElementById('fromDate');
    const toDateInput = document.getElementById('toDate');

    if (fromDateInput && toDateInput) {
        fromDateInput.addEventListener('change', function () {
            if (toDateInput.value && this.value > toDateInput.value) {
                alert('Ngày bắt đầu không được lớn hơn ngày kết thúc!');
                this.value = '';
            } else {
                applyFilters();
            }
        });

        toDateInput.addEventListener('change', function () {
            if (fromDateInput.value && this.value < fromDateInput.value) {
                alert('Ngày kết thúc không được nhỏ hơn ngày bắt đầu!');
                this.value = '';
            } else {
                applyFilters();
            }
        });
    }

    // Tương tự cho tab "Của tôi" (nếu có)
    const myFromDate = document.getElementById('myFromDate');
    const myToDate = document.getElementById('myToDate');

    if (myFromDate && myToDate) {
        myFromDate.addEventListener('change', function () {
            if (myToDate.value && this.value > myToDate.value) {
                alert('Ngày bắt đầu không được lớn hơn ngày kết thúc!');
                this.value = '';
            } else {
                applyFilters();
            }
        });

        myToDate.addEventListener('change', function () {
            if (myFromDate.value && this.value < myFromDate.value) {
                alert('Ngày kết thúc không được nhỏ hơn ngày bắt đầu!');
                this.value = '';
            } else {
                applyFilters();
            }
        });
    }
});
$(document).ready(function () {
    $('#taskAssignee').select2({
        placeholder: "Chọn nhân viên",
        width: '100%'
    });
});