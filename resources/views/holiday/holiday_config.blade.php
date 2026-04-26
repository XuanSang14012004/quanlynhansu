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

.edit { color: #2b78c5; }
.delete { color: red; }

.table-footer {
    display:flex;
    justify-content:flex-end;
    gap:20px;
    margin-top:15px;
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

    <!-- BUTTON -->
    <div class="card-box">
        <button class="btn-add" data-toggle="modal" data-target="#holidayModal">
            <i class="fa fa-plus"></i> THÊM MỚI
        </button>
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
                        {{ \Carbon\Carbon::parse($item->start_date)->format('d/m') }}
                        -
                        {{ \Carbon\Carbon::parse($item->end_date)->format('d/m') }}
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
                           onclick='editHoliday({{ $item->id }}, "{{ $item->start_date }}", "{{ $item->end_date }}")'>
                        </i>

                        <form action="{{ route('holiday.delete',$item->id) }}" method="POST" style="display:inline">
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
            {{ $holidays->links() }}
        </div>

    </div>

</div>
</div>

<!-- MODAL THÊM -->
<div class="modal fade" id="holidayModal">
<div class="modal-dialog modal-md modal-dialog-centered">
<div class="modal-content">

    <div class="modal-header">
        <h5>Thêm ngày nghỉ</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <form action="{{ route('holiday.store') }}" method="POST">
        @csrf

        <div class="modal-body">

            <label>Ngày bắt đầu</label>
            <input type="date" name="start_date" class="form-control" required>

            <br>

            <label>Ngày kết thúc</label>
            <input type="date" name="end_date" class="form-control" required>

        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" data-dismiss="modal">Hủy</button>
            <button class="btn btn-primary">Thêm</button>
        </div>

    </form>

</div>
</div>
</div>

<!-- MODAL SỬA -->
<div class="modal fade" id="editModal">
<div class="modal-dialog modal-md modal-dialog-centered">
<div class="modal-content">

    <div class="modal-header">
        <h5>Sửa ngày nghỉ</h5>
        <button class="close" data-dismiss="modal">&times;</button>
    </div>

    <form id="editForm" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-body">

            <label>Ngày bắt đầu</label>
            <input type="date" name="start_date" id="edit_start" class="form-control">

            <br>

            <label>Ngày kết thúc</label>
            <input type="date" name="end_date" id="edit_end" class="form-control">

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
function editHoliday(id, start, end) {

    $('#editModal').modal('show');

    $('#editForm').attr('action', "/holiday/update/" + id);

    $('#edit_start').val(start);
    $('#edit_end').val(end);
}
</script>

@endsection