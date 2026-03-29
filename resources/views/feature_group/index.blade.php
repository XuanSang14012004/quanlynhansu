@extends('layouts.master')

@section('title', 'Phân quyền nhóm chức năng')

@section('style')
    <link href="{{ asset('assets/global/plugins/icheck/skins/all.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/feature_group/index.css') }}">
@endsection

@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Bảng Điều Khiển</a> <i class="fa fa-circle"></i></li>
                    <li><span>Phân quyền nhóm chức năng</span></li>
                </ul>
            </div>

            <h1 class="page-title"> Phân quyền nhóm chức năng </h1>

            @if (session('success'))
                <div class="alert alert-success">
                    <button class="close" data-close="alert"></button> {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-body" style="padding: 0;">
                            <div class="row">

                                <div class="col-md-4 group-list-container">
                                    <div style="padding: 15px; background: #f9f9f9; border-bottom: 1px solid #eee;">
                                        <span class="caption-subject font-blue-sharp bold uppercase">Danh sách nhóm chức
                                            năng</span>
                                    </div>

                                    <div class="mt-element-list">
                                        <div class="mt-list-container list-simple">
                                            @foreach ($groups as $group)
                                                <a href="{{ route('feature-group.index', ['group_id' => $group->id]) }}"
                                                    class="group-item {{ isset($selectedGroup) && $selectedGroup->id == $group->id ? 'active' : '' }}">

                                                    {{-- Icon người giống ảnh --}}
                                                    <i class="fa fa-user icon-state"></i>

                                                    {{-- Checkbox giả để trang trí giống ảnh --}}
                                                    <i class="fa fa-square-o" style="color:#aaa; margin-right:5px;"></i>

                                                    {{ $group->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Nút Cập nhật/Bỏ quả bên trái (giống ảnh) --}}
                                    <div
                                        style="padding: 15px; text-align: center; border-top: 1px solid #eee; margin-top: 20px;">
                                        <button type="submit" form="permission-form" class="btn btn-primary">Cập
                                            nhật</button>
                                        <button type="button" class="btn btn-default">Bỏ quả</button>
                                    </div>
                                </div>

                                <div class="col-md-8" style="padding: 15px 25px;">
                                    <div
                                        style="margin-bottom: 20px; border-bottom: 1px solid #36c6d3; padding-bottom: 10px;">
                                        <span class="caption-subject font-grey-gallery bold uppercase">Danh sách chức
                                            năng</span>
                                        @if ($selectedGroup)
                                            <span class="pull-right text-muted">Đang chọn:
                                                <b>{{ $selectedGroup->name }}</b></span>
                                        @endif
                                    </div>

                                    @if (isset($selectedGroup))
                                        <form id="permission-form"
                                            action="{{ route('feature-group.update', $selectedGroup->id) }}" method="POST">
                                            @csrf

                                            {{-- Đã bỏ class scroller và style height: 600px --}}
                                            <div>

                                                @foreach ($allPermissions as $moduleName => $perms)
                                                    <div>
                                                        {{-- Tiêu đề đỏ --}}
                                                        <div class="module-title">
                                                            <i class="fa fa-cog module-icon"></i>
                                                            {{ $moduleName ?: 'Chức năng khác' }}
                                                        </div>

                                                        <div style="padding-left: 20px;">
                                                            @foreach ($perms as $p)
                                                                <div class="permission-item">
                                                                    <label>
                                                                        <input type="checkbox" name="permissions[]"
                                                                            value="{{ $p->id }}" class="icheck"
                                                                            {{ in_array($p->id, $currentPermissions) ? 'checked' : '' }}>
                                                                        {{ $p->display_name }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </form>
                                    @else
                                        <div class="alert alert-warning">Chưa có nhóm chức năng nào.</div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/global/plugins/icheck/icheck.min.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('.icheck').iCheck({
                checkboxClass: 'icheckbox_square-blue', // Style checkbox vuông xanh
                radioClass: 'iradio_square-blue',
                increaseArea: '20%'
            });
        });
    </script>
@endsection
