@extends('layouts.master')

@section('title', 'Phân quyền người dùng vào nhóm')

@section('style')
    <style>
        /* Style cho ô danh sách chọn user (Select multiple) */
        .user-list-box {
            height: 300px !important;
            border: 1px solid #c2cad8;
            padding: 5px;
        }

        .user-list-box option {
            padding: 5px 10px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
        }

        .user-list-box option:hover {
            background-color: #eef1f5;
        }

        /* Style cho nút di chuyển giữa */
        .move-btn-group {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 300px;
        }

        .move-btn {
            margin: 5px 0;
            width: 40px;
        }
    </style>
@endsection

@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject font-blue-sharp bold uppercase">Phân quyền chi tiết người dùng vào
                            nhóm</span>
                    </div>
                </div>
                <div class="portlet-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            <button class="close" data-close="alert"></button>
                            <strong>Thành công!</strong> {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            <button class="close" data-close="alert"></button>
                            <strong>Lỗi!</strong> {{ session('error') }}
                        </div>
                    @endif
                    {{-- KẾT THÚC ĐOẠN CODE THÊM MỚI --}}
                    <form id="assign-form" action="{{ route('user-feature-group.update') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="bold">Chọn phòng ban</label>
                                    <select id="select-phong-ban" class="form-control">
                                        <option value="">-- Tất cả phòng ban --</option>
                                        @foreach ($phongBans as $pb)
                                            <option value="{{ $pb->id }}">{{ $pb->ten }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input type="text" id="search-user" class="form-control"
                                        placeholder="Tìm kiếm người dùng...">
                                </div>

                                <div class="form-group">
                                    <label class="bold">Danh sách người dùng</label>
                                    <select multiple id="list-available" class="form-control user-list-box">
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="move-btn-group">
                                    <button type="button" id="btn-add" class="btn btn-default move-btn"
                                        title="Thêm vào nhóm"> &gt; </button>
                                    <button type="button" id="btn-remove" class="btn btn-default move-btn"
                                        title="Xóa khỏi nhóm"> &lt; </button>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bold">Chọn nhóm</label>
                                    <select name="group_id" id="select-group" class="form-control" required>
                                        <option value="">-- Chọn nhóm chức năng --</option>
                                        @foreach ($groups as $g)
                                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group" style="height: 34px;"></div>

                                <div class="form-group">
                                    <label class="bold">Danh sách người dùng thuộc nhóm</label>
                                    <select multiple name="users[]" id="list-in-group" class="form-control user-list-box">
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions" style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <button type="button" class="btn btn-default" onclick="window.location.reload()">Bỏ
                                quả</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
        var urlGetData = "{{ route('user-feature-group.get-data') }}";
        var _token = $('input[name="_token"]').val();

        function loadUsers() {
            var groupId = $('#select-group').val();
            var phongBanId = $('#select-phong-ban').val();

            // --- BỎ ĐOẠN IF (!GROUPID) CŨ ĐI ---
            // Code cũ chặn ở đây, giờ ta bỏ chặn để nó chạy tiếp xuống dưới
            
            $.ajax({
                url: urlGetData,
                type: 'POST',
                data: {
                    group_id: groupId, 
                    phong_ban_id: phongBanId,
                    _token: _token
                },
                success: function(res) {
                    // 1. Đổ dữ liệu vào cột TRÁI
                    var leftHtml = '';
                    $.each(res.available, function(key, user) {
                        leftHtml += '<option value="' + user.id + '">' + user.name + ' (' + user.email + ')</option>';
                    });
                    $('#list-available').html(leftHtml);

                    // 2. Đổ dữ liệu vào cột PHẢI
                    var rightHtml = '';
                    $.each(res.in_group, function(key, user) {
                        rightHtml += '<option value="' + user.id + '">' + user.name + ' (' + user.email + ')</option>';
                    });
                    $('#list-in-group').html(rightHtml);
                },
                error: function() {
                    console.log('Lỗi tải dữ liệu');
                }
            });
        }


            // Sự kiện: Khi chọn Phòng Ban HOẶC chọn Nhóm -> Tải lại dữ liệu
            $('#select-phong-ban, #select-group').change(function() {
                loadUsers();
            });


            loadUsers();
            // ---------------------------------------------------------
            // LOGIC CHUYỂN ĐỔI GIỮA 2 DANH SÁCH (Nút > và <)
            // ---------------------------------------------------------

            // Nút > (Thêm vào nhóm)
            $('#btn-add').click(function() {
                // Lấy các option đang được chọn ở bên Trái, chuyển sang Phải
                $('#list-available option:selected').each(function() {
                    $(this).remove().appendTo('#list-in-group');
                });
            });

            // Nút < (Xóa khỏi nhóm)
            $('#btn-remove').click(function() {
                // Lấy các option đang được chọn ở bên Phải, trả về Trái
                $('#list-in-group option:selected').each(function() {
                    $(this).remove().appendTo('#list-available');
                });
            });

            // ---------------------------------------------------------
            // LOGIC TÌM KIẾM (Search Box bên trái)
            // ---------------------------------------------------------
            $('#search-user').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $("#list-available option").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // ---------------------------------------------------------
            // TRƯỚC KHI SUBMIT FORM
            // ---------------------------------------------------------
            $('#assign-form').submit(function() {
                var groupId = $('#select-group').val();
                if (!groupId) {
                    alert('Vui lòng chọn Nhóm chức năng trước khi cập nhật!');
                    return false;
                }

                // Mẹo: Phải select tất cả option ở cột Phải thì form mới gửi dữ liệu đi được
                $('#list-in-group option').prop('selected', true);
                return true;
            });
        });
    </script>
@endsection
