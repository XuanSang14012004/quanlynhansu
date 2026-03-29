@extends('layouts.master')

@section('title', 'Cấu Hình Thông Báo')

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
        display: inline-block;
    }

    .btn-add i {
        margin-right: 5px;
    }

    .table {
        width: 100%;
        margin-top: 20px;
    }

    .table th {
        background: #f5f5f5;
        text-align: left;
        padding: 12px;
    }

    .table td {
        padding: 12px;
        border-top: 1px solid #eee;
    }

    .badge-email {
        border: 1px solid #2b78c5;
        color: #2b78c5;
        padding: 5px 10px;
        border-radius: 20px;
    }

    .badge-zalo {
        border: 1px solid #2e8b57;
        color: #2e8b57;
        padding: 5px 10px;
        border-radius: 20px;
    }

    .badge-phone {
        border: 1px solid #f39c12;
        color: #f39c12;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 13px;
        display: inline-block;
    }

    .action i {
        font-size: 16px;
        margin-right: 10px;
        cursor: pointer;
    }

    .edit {
        color: #2b78c5;
    }

    .delete {
        color: red;
    }

    /* form thêm */
    .form-add {
        background: #fff;
        padding: 25px;
        margin-top: 20px;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: none;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .btn-submit {
        background: #2b78c5;
        color: #fff;
        padding: 8px 18px;
        border: none;
        border-radius: 5px;
    }

    .btn-cancel {
        border: 1px solid #2b78c5;
        background: #fff;
        color: #2b78c5;
        padding: 8px 18px;
        border-radius: 5px;
    }
    .table-footer{
display:flex;
justify-content:flex-end;
align-items:center;
gap:20px;
margin-top:15px;
font-size:14px;
}

.page-size select{
padding:3px 6px;
border:1px solid #ccc;
border-radius:3px;
}

.page-links svg{
width:18px;
}
</style>

<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> Trang chủ</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span class="active">Cấu hình thông báo nhắc việc</span>
                </li>
            </ul>
        </div>




        <div class="card-box">

            <button class="btn-add" data-toggle="modal" data-target="#notificationModal">
                <i class="fa fa-plus"></i> THÊM MỚI
            </button>
        </div>



        <div class="card-box">

            <table class="table">

                <thead>
                    <tr>
                        <th>Lịch thông báo</th>
                        <th>Thời gian thông báo</th>
                        <th>Loại hình thông báo</th>
                        <th>Người tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($notifications as $item)

                    <tr>

                        <td>
                            {{ $item->schedule_type == 'before_day' ? 'Trước 1 ngày' : 'Trong ngày' }}
                        </td>

                        <td>
                            {{ date('H:i', strtotime($item->notify_time)) }}
                        </td>

                        <td>

                            @if($item->email)
                            <span class="badge-email">
                                <i class="fa fa-envelope"></i> Email
                            </span>
                            @endif

                            @if($item->zalo)
                            <span class="badge-zalo">
                                Zalo
                            </span>
                            @endif
                            @if($item->phone)
                            <span class="badge-phone">
                                Phone
                            </span>
                            @endif

                        </td>

                        <td>
                            {{ $item->user->name ?? '...' }}
                        </td>

                        <td class="action">

                            <!-- Nút sửa -->
                            <i class="fa fa-pencil edit"
                                onclick='editNotification({{ $item->id }}, @json($item->schedule_type), @json($item->notify_time), {{ $item->email ?? 0 }}, {{ $item->zalo ?? 0 }}, {{ $item->phone ?? 0 }})'>
                            </i>


                            <!-- Nút xóa -->
                            <form action="{{ route('notification.delete',$item->id) }}"
                                method="POST"
                                style="display:inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    style="border:none;background:none"
                                    onclick="return confirm('Bạn có chắc muốn xóa?')">

                                    <i class="fa fa-trash delete"></i>

                                </button>

                            </form>

                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
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

                    {{ $notifications->firstItem() ?? 0 }}
                    -
                    {{ $notifications->lastItem() ?? 0 }}
                    trong tổng số
                    {{ $notifications->total() }}

                </div>


                <div class="page-links">

                    {{ $notifications->links() }}

                </div>

            </div>
        </div>
    </div>
</div>



<div class="page-content-wrapper">

</div>

<!-- FORM THÊM -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:8px;box-shadow:0 5px 25px rgba(0,0,0,0.2);">

            <!-- HEADER -->
            <div class="modal-header" style="padding:16px 24px;border-bottom:1px solid #dee2e6;">
                <h5 style="font-size:18px;font-weight:600;margin:0;">
                    Thêm cấu hình thông báo mới
                </h5>

                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <!-- BODY -->
            <div class="modal-body" style="padding:24px">

                <form action="{{ route('notification.store') }}" method="POST">
                    @csrf

                    <!-- LỊCH THÔNG BÁO -->
                    <div style="margin-bottom:20px">
                        <label style="font-weight:600;margin-bottom:8px;display:block">
                            Lịch thông báo
                        </label>

                        <select name="schedule_type" style="width:100%;padding:10px;border:1px solid #ced4da;border-radius:4px">
                            <option value="before_day">Trước 1 ngày</option>
                            <option value="same_day">Trong ngày</option>
                        </select>

                    </div>

                    <!-- THỜI GIAN -->
                    <div style="margin-bottom:20px">

                        <label style="font-weight:600;margin-bottom:8px;display:block">
                            Thời gian thông báo
                        </label>

                        <input type="time" name="notify_time"
                            style="width:100%;padding:10px;border:1px solid #ced4da;border-radius:4px"
                            value="08:00">

                    </div>

                    <!-- LOẠI THÔNG BÁO -->
                    <div style="margin-bottom:10px">

                        <label style="font-weight:600;margin-bottom:10px;display:block">
                            Loại hình thông báo
                        </label>

                        <div style="margin-bottom:8px">
                            <label>
                                <input type="checkbox" name="email" value="1">
                                Qua email
                            </label>
                        </div>

                        <div style="margin-bottom:8px">
                            <label>
                                <input type="checkbox" name="zalo" value="1">
                                Qua zalo
                            </label>
                        </div>

                        <div>
                            <label>
                                <input type="checkbox" name="phone" value="1">
                                Qua số điện thoại
                            </label>
                        </div>

                    </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer" style="padding:16px 24px;border-top:1px solid #dee2e6">

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
<!-- FORM SỬA -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Sửa cấu hình thông báo</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">

                    <label>Lịch thông báo</label>
                    <select name="schedule_type" id="edit_schedule" class="form-control">
                        <option value="before_day">Trước 1 ngày</option>
                        <option value="same_day">Trong ngày</option>
                    </select>

                    <br>

                    <label>Thời gian</label>
                    <input type="time" name="notify_time" id="edit_time" class="form-control">

                    <br>

                    <label>Loại thông báo</label>

                    <br>
                    <label>
                        <input type="checkbox" name="email" id="edit_email"> Email
                    </label>

                    <br>
                    <label>
                        <input type="checkbox" name="zalo" id="edit_zalo"> Zalo
                    </label>

                    <br>
                    <label>
                        <input type="checkbox" name="phone" id="edit_phone"> Phone
                    </label>

                </div>

                <div class="modal-footer">

                    <button class="btn btn-secondary" data-dismiss="modal">
                        Hủy
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Cập nhật
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
<!-- script -->
<script>
    function showForm() {
        document.getElementById("formAdd").style.display = "block";
    }

    function hideForm() {
        document.getElementById("formAdd").style.display = "none";
    }

    function editNotification(id, schedule, time, email, zalo, phone) {

        $('#editModal').modal('show');

        $('#editForm').attr('action', "{{ url('notification/update') }}/" + id);

        $('#edit_schedule').val(schedule);

        $('#edit_time').val(time);

        $('#edit_email').prop('checked', email);

        $('#edit_zalo').prop('checked', zalo);

        $('#edit_phone').prop('checked', phone);

    }
</script>
@endsection