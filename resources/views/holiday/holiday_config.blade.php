@extends('layouts.master')

@section('title', 'Cấu hình ngày nghỉ')

@section('content')

<style>
    .page-title {
        background: #2b78c5;
        color: white;
        padding: 15px 20px;
        font-size: 20px;
        font-weight: 600;
    }

    .card-box {
        background: white;
        padding: 20px;
        margin-top: 20px;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-add {
        background: #2b78c5;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
    }

    .table th {
        background: #f5f5f5;
        padding: 12px;
    }

    .table td {
        padding: 12px;
        border-top: 1px solid #eee;
    }

    .action i {
        margin-right: 10px;
        cursor: pointer;
    }

    .edit {
        color: #2b78c5;
    }

    .delete {
        color: red;
    }

    .table-footer {
        display: flex;
        justify-content: flex-end;
        gap: 20px;
        margin-top: 15px;
    }
</style>

<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="#"><i class="fa fa-home"></i> Trang chủ</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span class="active">Cấu hình ngày nghỉ</span>
                </li>
            </ul>
        </div>
        @if(session('success'))
        <div class="alert alert-success" style="margin: 15px 0;">
            {{ session('success') }}
        </div>
        @endif
        <!-- BUTTON -->
        <div class="card-box" style="display:flex; justify-content:space-between; align-items:center;">

            <!-- BUTTON -->
            <button class="btn-add" data-toggle="modal" data-target="#addHolidayModal">
                <i class="fa fa-plus"></i> THÊM MỚI
            </button>

            <!-- FILTER NĂM -->
            <form method="GET" style="display:flex; align-items:center; gap:10px;">
                <label style="margin:0;">Năm</label>

                <select name="year" onchange="this.form.submit()" style="padding:5px 10px;">
                    @for($i = now()->year - 5; $i <= now()->year + 5; $i++)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                        @endfor
                </select>

                <!-- giữ số dòng -->
                <input type="hidden" name="per_page" value="{{ $perPage }}">
            </form>

        </div>

        <!-- TABLE -->
        <div class="card-box">

            <table class="table">
                <thead>
                    <tr>
                        <th>Ngày nghỉ</th>
                        <th>Thời gian</th>
                        <th>Số ngày</th>
                        <th>Người tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($holidays as $item)
                    <tr>
                        <td>
                            {{ $item->name }}

                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }}
                            -
                            {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($item->start_date)->diffInDays($item->end_date) + 1 }} ngày
                        </td>

                        <td>
                            {{ $item->user->name ?? '...' }}
                        </td>

                        <td class="action">
                            <i class="fa fa-pencil edit"
                                onclick='editHoliday({{ $item->id }}, "{{ $item->name }}", "{{ $item->start_date }}", "{{ $item->end_date }}")'>
                            </i>

                            <form action="{{ route('holiday.delete',$item->id) }}" method="POST"
                                style="display:inline"
                                onsubmit="return confirm('Bạn có chắc muốn xoá không?')">
                                @csrf
                                @method('DELETE')
                                <button style="border:none;background:none">
                                    <i class="fa fa-trash delete"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- PAGINATION -->
            <div class="table-footer">

                <div class="page-size">
                    <form method="GET">
                        Số dòng mỗi trang:
                        <select name="per_page" onchange="this.form.submit()">
                            <option value="5" {{ $perPage==5?'selected':'' }}>5</option>
                            <option value="10" {{ $perPage==10?'selected':'' }}>10</option>
                            <option value="20" {{ $perPage==20?'selected':'' }}>20</option>
                            <option value="50" {{ $perPage==50?'selected':'' }}>50</option>
                        </select>
                    </form>
                </div>

                <div class="page-info">
                    {{ $holidays->firstItem() ?? 0 }}
                    -
                    {{ $holidays->lastItem() ?? 0 }}
                    trong tổng số
                    {{ $holidays->total() }}
                </div>

                <div class="page-links">
                    {{ $holidays->links() }}
                </div>

            </div>

        </div>

    </div>
</div>

<!-- MODAL THÊM -->
<!-- Modal -->
<div class="modal fade" id="addHolidayModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="{{ route('holiday.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Thêm ngày nghỉ mới</h5>
                </div>

                <div class="modal-body">

                    <!-- Mô tả -->
                    <div class="mb-3">
                        <label>Mô tả ngày nghỉ *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <!-- Từ ngày -->
                    <div class="mb-3">
                        <label>Từ ngày *</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>

                    <!-- Đến ngày -->
                    <div class="mb-3">
                        <label>Đến ngày *</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" required>
                    </div>

                    <!-- Số ngày -->
                    <div class="mb-3">
                        <label>Số ngày nghỉ</label>
                        <input type="number" name="total_days" id="total_days" class="form-control" readonly>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        BỎ QUA
                    </button>
                    <button type="submit" class="btn btn-primary">
                        THÊM
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- MODAL SỬA -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Sửa ngày nghỉ</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <!-- Mô tả -->
                    <div class="mb-3">
                        <label>Mô tả</label>
                        <input type="text" name="name" id="edit_name" class="form-control">
                    </div>

                    <!-- Từ ngày -->
                    <div class="mb-3">
                        <label>Từ ngày</label>
                        <input type="date" name="start_date" id="edit_start" class="form-control">
                    </div>

                    <!-- Đến ngày -->
                    <div class="mb-3">
                        <label>Đến ngày</label>
                        <input type="date" name="end_date" id="edit_end" class="form-control">
                    </div>

                    <!-- Số ngày -->
                    <div class="mb-3">
                        <label>Số ngày</label>
                        <input type="number" id="edit_total_days" class="form-control" readonly>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button class="btn btn-primary">Cập nhật</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        function calculateDays() {
            let start = document.getElementById('start_date').value;
            let end = document.getElementById('end_date').value;

            if (start && end) {
                let s = new Date(start);
                let e = new Date(end);

                let days = (e - s) / (1000 * 60 * 60 * 24) + 1;
                document.getElementById('total_days').value = days > 0 ? days : 0;
            }
        }

        function calculateEditDays() {
            let start = document.getElementById('edit_start').value;
            let end = document.getElementById('edit_end').value;

            if (start && end) {
                let s = new Date(start);
                let e = new Date(end);

                let days = (e - s) / (1000 * 60 * 60 * 24) + 1;
                document.getElementById('edit_total_days').value = days > 0 ? days : 0;
            }
        }

        // ADD EVENT SAFE
        let startInput = document.getElementById('start_date');
        let endInput = document.getElementById('end_date');

        if (startInput && endInput) {
            startInput.addEventListener('change', calculateDays);
            endInput.addEventListener('change', calculateDays);
        }

        let editStart = document.getElementById('edit_start');
        let editEnd = document.getElementById('edit_end');

        if (editStart && editEnd) {
            editStart.addEventListener('change', calculateEditDays);
            editEnd.addEventListener('change', calculateEditDays);
        }

        // GLOBAL FUNCTION
        window.editHoliday = function(id, name, start, end) {

            $('#editModal').modal('show');

            $('#editForm').attr('action', "{{ url('holiday/update') }}/" + id);

            $('#edit_name').val(name);
            $('#edit_start').val(start);
            $('#edit_end').val(end);

            calculateEditDays();
        }

    });
</script>

@endsection