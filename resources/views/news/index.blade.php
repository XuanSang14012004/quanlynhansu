@extends('layouts.master')

@section('title', 'Quản lý Tin tức')

@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><a href="{{ route('dashboard') }}">Bảng Điều Khiển</a> <i class="fa fa-circle"></i></li>
                <li><span>Quản trị tin tức</span></li>
            </ul>
        </div>
        <h1 class="page-title">
            <i class="fa fa-newspaper-o"></i> Quản lý Tin tức
        </h1>
        
        @if(session('success'))
            <div class="alert alert-success">
                <button class="close" data-close="alert"></button>
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-list font-green"></i>
                            <span class="caption-subject font-green bold uppercase">Danh sách bài viết</span>
                        </div>
                        <div class="actions">
                            <a href="{{ route('news.public') }}" class="btn btn-default btn-sm" target="_blank">
                                <i class="fa fa-globe"></i> Xem trang Tin Tức
                            </a>
                            <a href="{{ route('news.create') }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> Thêm bài viết mới
                            </a>
                            <a class="btn btn-circle btn-icon-only btn-default" href="{{ route('news.index') }}">
                                <i class="icon-refresh"></i>
                            </a>
                        </div>
                    </div>

                    <div class="portlet-body">
                        <div class="table-scrollable">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 60px; text-align: center;">Ảnh</th>
                                        <th style="width: 200px;">Tiêu đề</th>
                                        
                                        {{-- 1. THÊM CỘT DANH MỤC --}}
                                        <th style="width: 120px;">Danh mục</th>
                                        
                                        <th style="width: 120px;">Tác giả</th>
                                        <th style="width: 100px;">Ngày tạo</th>
                                        <th style="width: 80px; text-align: center;">Xem bài</th>
                                        <th style="width: 100px; text-align: center;">Trạng thái</th>
                                        
                                        @permission('approve-news|edit-news|delete-news')
                                            <th style="width: 120px; text-align: center;">Hành động</th> 
                                        @endpermission
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($news as $item)
                                    <tr>
                                        <td align="center" style="vertical-align: middle;">
                                            @if($item->thumbnail)
                                                <img src="{{ asset($item->thumbnail) }}" width="50" height="50" style="object-fit: cover; border-radius: 4px;">
                                            @else
                                                <span class="text-muted" style="font-size: 10px;">No Image</span>
                                            @endif
                                        </td>
                                        
                                        <td style="vertical-align: middle;">
                                            <strong>{{ $item->title }}</strong>
                                        </td>
                                        
                                        {{-- 2. HIỂN THỊ TÊN DANH MỤC --}}
                                        <td style="vertical-align: middle;">
                                            @if($item->category)
                                                <span class="label label-info"> {{ $item->category->name }} </span>
                                            @else
                                                <span class="label label-default"> Chưa phân loại </span>
                                            @endif
                                        </td>

                                        <td style="vertical-align: middle;">
                                            <i class="fa fa-user"></i> {{ $item->author->name ?? 'Unknown' }}
                                        </td>
                                        
                                        <td style="vertical-align: middle;">
                                            {{ $item->created_at->format('d/m/Y') }}
                                        </td>
                                        
                                        <td align="center" style="vertical-align: middle;">
                                            @if($item->status == 1)
                                                <a href="{{ route('news.show', ['id' => $item->id, 'slug' => $item->slug]) }}" target="_blank" class="btn btn-xs default" title="Xem bài viết hiển thị trên web">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @else
                                                <span class="text-muted" style="font-size:11px;">---</span>
                                            @endif
                                        </td>

                                        <td align="center" style="vertical-align: middle;">
                                            {!! $item->status_label !!}
                                        </td>

                                        @permission('approve-news|edit-news|delete-news')
                                        <td align="center" style="vertical-align: middle;">
                                            
                                            {{-- Nút Duyệt / Từ chối --}}
                                            @permission('approve-news')
                                                @if($item->status == 0)
                                                    <a href="{{ route('news.approve', $item->id) }}" class="btn btn-xs btn-success" title="Phê duyệt">
                                                        <i class="fa fa-check"></i>
                                                    </a>
                                                    <a href="{{ route('news.reject', $item->id) }}" class="btn btn-xs btn-warning" title="Từ chối" onclick="return confirm('Bạn chắc chắn muốn từ chối?')">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                @endif
                                            @endpermission

                                            {{-- Nút Sửa --}}
                                            @permission('edit-news')
                                                <a href="{{ route('news.edit', $item->id) }}" class="btn btn-xs btn-info" title="Sửa bài viết">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            @endpermission

                                            {{-- Nút Xóa --}}
                                            @permission('delete-news')
                                                <a href="{{ route('news.destroy', $item->id) }}" class="btn btn-xs btn-danger" title="Xóa bài viết" onclick="return confirm('CẢNH BÁO: Bạn có chắc chắn muốn xóa bài viết này không?')">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endpermission

                                        </td>
                                        @endpermission

                                    </tr>
                                    @endforeach
                                    
                                    @if($news->isEmpty())
                                    <tr>
                                        {{-- Tăng colspan lên 9 vì đã thêm 1 cột --}}
                                        <td colspan="9" class="text-center">Chưa có bài viết nào.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                {{ $news->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection