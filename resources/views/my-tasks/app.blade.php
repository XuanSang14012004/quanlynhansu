@extends('layouts.master')

@section('title', 'Công việc của tôi')

@section('style')
    {{-- CSS cho Datepicker và Select2 nếu cần --}}
    <link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        /* Custom UI giống hình ảnh thiết kế */
        .custom-page-container {
            padding: 20px;
        }
        
        /* Cụm bộ lọc Date Buttons */
        .filter-btn-group {
            display: flex;
            border: 1px solid #3b82f6;
            border-radius: 4px;
            overflow: hidden;
            width: fit-content;
            margin-bottom: 15px;
            background: white;
        }
        .filter-btn-group button {
            background: white;
            border: none;
            border-right: 1px solid #3b82f6;
            color: #3b82f6;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            outline: none;
            transition: all 0.2s ease;
        }
        .filter-btn-group button:last-child {
            border-right: none;
        }
        .filter-btn-group button:hover {
            background: #eff6ff;
        }
        .filter-btn-group button.active {
            font-weight: 500;
            background: #f0f9ff;
        }

        /* Dòng Input Tìm kiếm */
        .filter-input-row {
            display: flex;
            gap: 15px;
            align-items: center;
            background: white;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .filter-input-row .input-search {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            outline: none;
            font-size: 14px;
        }
        .filter-input-row .select-status {
            width: 250px;
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            outline: none;
            font-size: 14px;
        }
        .filter-input-row .btn-clear {
            display: flex;
            align-items: center;
            gap: 5px;
            background: white;
            border: 1px solid #cbd5e1;
            padding: 8px 16px;
            border-radius: 4px;
            color: #475569;
            cursor: pointer;
            font-size: 14px;
        }
        .filter-input-row .btn-clear:hover {
            background: #f8fafc;
        }

        /* Form chọn ngày tuỳ chỉnh (Ẩn mặc định) */
        .custom-date-inputs {
            display: none;
            gap: 15px;
            align-items: center;
            margin-bottom: 20px;
            background: white;
            padding: 10px 20px;
            border-radius: 6px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            width: fit-content;
        }

        /* Bảng dữ liệu */
        .custom-table-wrapper {
            background: white;
            border-radius: 6px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0 !important;
        }
        .custom-table thead {
            background-color: #dceafe;
        }
        .custom-table th {
            text-align: left;
            padding: 15px 20px !important;
            font-weight: 600;
            color: #1e293b;
            border: none !important;
            white-space: nowrap;
        }
        .custom-table td {
            padding: 15px 20px !important;
            border-top: 1px solid #f1f5f9 !important;
            border-bottom: 1px solid #f1f5f9 !important;
            vertical-align: middle !important;
            color: #334155;
        }
        
        /* Badge & Actions */
        .status-badge {
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            display: inline-block;
        }
        .status-badge.pending { background-color: #fbbf24; color: #000; }
        .status-badge.completed { background-color: #10b981; color: #fff; }
        .status-badge.default { background-color: #94a3b8; color: #fff; }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 4px;
            background: transparent;
            margin-right: 5px;
            cursor: pointer;
            transition: 0.2s;
        }
        .action-btn.view { border: 1px solid #38bdf8; color: #38bdf8; }
        .action-btn.view:hover { background: #e0f2fe; }
        .action-btn.check { border: 1px solid #10b981; color: #10b981; }
        .action-btn.check:hover { background: #d1fae5; }
    </style>
@endsection

@section('content')

<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> Trang chủ</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span class="active">Công việc của tôi</span>
                </li>
            </ul>
        </div>

        <div class="custom-page-container">
            <div class="filter-btn-group" id="dateButtons">
                <button type="button" class="btn active" data-filter="all">Tất cả</button>
                <button type="button" class="btn" data-filter="yesterday">Hôm qua</button>
                <button type="button" class="btn" data-filter="today">Hôm nay</button>
                <button type="button" class="btn" data-filter="tomorrow">Ngày mai</button>
                <button type="button" class="btn" data-filter="thisWeek">Tuần này</button>
                <button type="button" class="btn" data-filter="custom">Từ ngày - Đến ngày</button>
            </div>
            
            <div id="customDateRange" class="custom-date-inputs">
                <span style="font-weight: 500; color: #475569;">Từ ngày:</span>
                <input type="date" id="fromDate" class="input-search" style="width: 150px; padding: 6px 12px;" onchange="applyFilters()">
                
                <span style="font-weight: 500; color: #475569;">Đến ngày:</span>
                <input type="date" id="toDate" class="input-search" style="width: 150px; padding: 6px 12px;" onchange="applyFilters()">
            </div>

            <div class="filter-input-row">
                <input type="text" class="input-search" id="searchInput" placeholder="Nhập nội dung, mô tả công việc" oninput="applyFilters()">
                
                <select class="select-status" id="statusFilter" onchange="applyFilters()">
                    <option value="all">Tất cả trạng thái</option>
                    <option value="0">Chờ xử lý</option>
                    <option value="1">Đã hoàn thành</option>
                </select>
                
                <button class="btn-clear" onclick="clearFilters()">
                    <i class="fa fa-times-circle" style="color: #94a3b8;"></i> Xóa bộ lọc
                </button>
            </div>

            <div class="custom-table-wrapper">
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th> Nội dung công việc </th>
                                <th> Thời gian </th>
                                <th> Ngày thực hiện </th>
                                <th> Người giao việc </th>
                                <th> Trạng thái </th>
                                <th> Thao tác </th>
                            </tr>
                        </thead>
                        <tbody id="taskTableBody">
                            <tr><td colspan="6" class="text-center">Đang tải dữ liệu...</td></tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="row" style="padding: 15px 20px;">
                    <div class="col-md-12 text-right" id="pagination"></div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title bold font-blue">Chi tiết công việc</h4>
            </div>
            <div class="modal-body">
                <div class="row static-info">
                    <div class="col-md-4 name bold"> Tên công việc: </div>
                    <div class="col-md-8 value" id="mTitle"> </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-4 name bold"> Thời gian: </div>
                    <div class="col-md-8 value" id="mTime"> </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-4 name bold"> Người giao: </div>
                    <div class="col-md-8 value" id="mCreator"> </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-4 name bold"> Mô tả: </div>
                    <div class="col-md-8 value" id="mDesc"> </div>
                </div>
                <hr>
                <div id="completionArea" class="display-none">
                    <h5 class="bold">Báo cáo kết quả:</h5>
                    <div class="form-group">
                        <textarea id="completionNote" class="form-control" rows="3" placeholder="Nhập ghi chú hoàn thành công việc..."></textarea>
                    </div>
                </div>
                <div id="completedInfo" class="alert alert-success display-none" style="margin-top: 10px;">
                    <strong><i class="fa fa-check-circle"></i> Đã hoàn thành!</strong><br>
                    Ghi chú: <span id="mNote"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn green" id="btnSubmitComplete" onclick="submitCompletion()">
                    <i class="fa fa-check"></i> Xác nhận hoàn thành
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    window.BASE_URL = "{{ url('') }}";
</script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    const API_URL = window.BASE_URL + '/api/my-tasks';
    let currentTaskId = null;
    let activeDateFilter = 'all'; // Đổi mặc định thành Tất cả

    $(document).ready(function() {
        fetchTasks();

        // Xử lý logic khi click vào các nút lọc ngày
        $('#dateButtons .btn').on('click', function() {
            // Đổi màu nút active
            $('#dateButtons .btn').removeClass('active');
            $(this).addClass('active');
            
            // Lấy giá trị filter
            activeDateFilter = $(this).data('filter');

            // Ẩn/hiện form chọn ngày tuỳ chỉnh
            if (activeDateFilter === 'custom') {
                $('#customDateRange').css('display', 'flex');
            } else {
                $('#customDateRange').hide();
                $('#fromDate').val('');
                $('#toDate').val('');
                applyFilters(); // Gọi API khi chọn nút
            }
        });
    });

    // Hàm áp dụng bộ lọc chung (Có dùng debounce để chống spam khi gõ chữ)
    const applyFilters = _.debounce(function() {
        fetchTasks(1);
    }, 500);

    // Hàm Xóa bộ lọc
    function clearFilters() {
        $('#searchInput').val('');
        $('#statusFilter').val('all');
        $('#fromDate').val('');
        $('#toDate').val('');
        
        // Reset nút ngày về "Tất cả"
        $('#dateButtons .btn').removeClass('active');
        $('[data-filter="all"]').addClass('active');
        activeDateFilter = 'all';
        $('#customDateRange').hide();
        
        fetchTasks(1);
    }

    // 1. HÀM GỌI API LẤY DANH SÁCH
    function fetchTasks(page = 1) {
        const search = $('#searchInput').val();
        const status = $('#statusFilter').val();
        const fromDate = $('#fromDate').val();
        const toDate = $('#toDate').val();

        App.blockUI({ target: '#taskTableBody', animate: true });

        axios.get(API_URL, {
            params: {
                page: page,
                search: search,
                status: status,
                date_filter: activeDateFilter,
                from_date: fromDate,
                to_date: toDate
            }
        })
        .then(function (response) {
            renderTable(response.data);
            App.unblockUI('#taskTableBody');
        })
        .catch(function (error) {
            console.error(error);
            $('#taskTableBody').html('<tr><td colspan="6" class="text-center font-red bold">Lỗi kết nối API!</td></tr>');
            App.unblockUI('#taskTableBody');
        });
    }

    // 2. HÀM RENDER BẢNG
    function renderTable(data) {
        const tasks = data.data; 
        const tbody = $('#taskTableBody');
        tbody.empty();

        if (tasks.length === 0) {
            tbody.html('<tr><td colspan="6" class="text-center text-muted" style="padding: 20px !important;">Không tìm thấy công việc nào phù hợp.</td></tr>');
            return;
        }

        tasks.forEach(task => {
            let statusLabel = '';
            if(task.status == 0) {
                statusLabel = '<span class="status-badge pending">Chờ xử lý</span>';
            } else if(task.status == 1) {
                statusLabel = '<span class="status-badge completed">Đã hoàn thành</span>';
            } else {
                statusLabel = '<span class="status-badge default">Khác</span>';
            }

            const row = `
                <tr>
                    <td>
                        <div style="font-weight: 600; color: #1e293b; margin-bottom: 4px;">${task.title}</div>
                        <div style="color: #64748b; font-size: 13px;">${task.description ? task.description : ''}</div>
                    </td>
                    <td><span style="color: #475569;">08:00 - 17:00</span></td>
                    <td>
                        <span style="color: #475569;">${formatDate(task.start_date)}</span><br>
                        <span style="color: #64748b; font-size: 13px;">đến ${formatDate(task.end_date)}</span>
                    </td>
                    <td>${task.creator ? task.creator.name : '<span class="text-muted">N/A</span>'}</td>
                    <td>${statusLabel}</td>
                    <td>
                        <button type="button" class="action-btn view" onclick="openDetail(${task.id})" title="Xem chi tiết">
                            <i class="fa fa-eye"></i>
                        </button>
                        ${task.status == 0 ? `
                        <button type="button" class="action-btn check" onclick="openDetail(${task.id})" title="Xác nhận hoàn thành">
                            <i class="fa fa-check-circle-o"></i>
                        </button>
                        ` : ''}
                    </td>
                </tr>
            `;
            tbody.append(row);
        });

        renderPagination(data);
    }

    // 3. HÀM PHÂN TRANG
    function renderPagination(data) {
        const pagDiv = $('#pagination');
        let html = '';
        if (data.prev_page_url) {
            html += `<button class="btn btn-sm default" onclick="fetchTasks(${data.current_page - 1})"><i class="fa fa-angle-left"></i> Trước</button>`;
        } else {
            html += `<button class="btn btn-sm default disabled"><i class="fa fa-angle-left"></i> Trước</button>`;
        }
        html += `<span class="bold font-dark" style="margin: 0 10px;"> Trang ${data.current_page} / ${data.last_page} </span>`;
        if (data.next_page_url) {
            html += `<button class="btn btn-sm default" onclick="fetchTasks(${data.current_page + 1})">Sau <i class="fa fa-angle-right"></i></button>`;
        } else {
            html += `<button class="btn btn-sm default disabled">Sau <i class="fa fa-angle-right"></i></button>`;
        }
        pagDiv.html(html);
    }

    // 4. HÀM MỞ CHI TIẾT
    function openDetail(id) {
        App.blockUI({ target: '.custom-table-wrapper', animate: true });
        axios.get(`${API_URL}/${id}`).then(function (response) {
            const task = response.data;
            currentTaskId = task.id;
            $('#mTitle').text(task.title);
            $('#mTime').text(`${formatDate(task.start_date)} - ${formatDate(task.end_date)}`);
            $('#mCreator').text(task.creator ? task.creator.name : 'N/A');
            $('#mDesc').text(task.description || 'Không có mô tả');
            $('#mNote').text(task.completion_note || '');

            if (task.status == 1) { 
                $('#completionArea').hide();
                $('#btnSubmitComplete').hide(); 
                $('#completedInfo').show();
            } else { 
                $('#completionArea').show();
                $('#btnSubmitComplete').show(); 
                $('#completedInfo').hide();
                $('#completionNote').val(''); 
            }
            $('#detailModal').modal('show');
            App.unblockUI('.custom-table-wrapper');
        }).catch(function (error) {
            alert('Không thể tải chi tiết công việc.');
            App.unblockUI('.custom-table-wrapper');
        });
    }

    // 5. HÀM GỬI API HOÀN THÀNH
   // 5. HÀM GỬI API HOÀN THÀNH
    function submitCompletion() {
        const note = $('#completionNote').val();
        if (!confirm('Bạn có chắc chắn muốn báo cáo hoàn thành công việc này không?')) return;
        
        App.blockUI({ target: '#detailModal .modal-content', animate: true });

        axios.post(`${API_URL}/${currentTaskId}/complete`, { note: note })
        .then(function (response) {
            // 1. Đóng popup ngay lập tức
            $('#detailModal').modal('hide');
            
            // 2. Xoá nội dung ghi chú sau khi hoàn thành
            $('#completionNote').val('');
            
            // 3. Tải lại bảng ngay lập tức
            fetchTasks(1); 
            
            // 4. Hiển thị thông báo (An toàn kiểm tra thư viện)
            if (typeof toastr !== 'undefined') {
                toastr.success('Đã cập nhật trạng thái thành công!', 'Thành công');
            } else {
                alert('Đã cập nhật trạng thái thành công!');
            }
        })
        .catch(function (error) {
            console.error('Lỗi API Hoàn thành:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error('Có lỗi xảy ra khi cập nhật.', 'Lỗi');
            } else {
                alert('Có lỗi xảy ra khi cập nhật. Vui lòng nhấn F12 chọn tab Console để xem chi tiết.');
            }
        })
        .finally(function() {
            App.unblockUI('#detailModal .modal-content');
        });
    }

    // Hàm fomat ngày tháng
    function formatDate(dateString) {
        if (!dateString) return '';
        const parts = dateString.split('-'); 
        if (parts.length === 3) return `${parts[2]}/${parts[1]}/${parts[0]}`;
        return dateString;
    }
</script>
@endsection