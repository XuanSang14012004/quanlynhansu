@extends('layouts.master')

@section('title', 'Soạn bài viết mới')

@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        {{-- Phần Breadcrumb giữ nguyên --}}
        
        <h1 class="page-title">
            <i class="fa fa-pencil-square-o"></i> Thêm tin tức mới
        </h1>
        
        {{-- Phần hiển thị lỗi chung --}}
        @if($errors->any())
        <div class="alert alert-danger">
            <button class="close" data-close="alert"></button>
            @foreach($errors->all() as $error)
                <p> {{ $error }} </p>
            @endforeach
        </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="icon-note font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Thông tin bài viết</span>
                        </div>
                    </div>
                    
                    <div class="portlet-body form">
                        <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-body">
                                
                                {{-- 1. TIÊU ĐỀ --}}
                                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                    <label class="control-label bold">Tiêu đề bài viết <span class="required" aria-required="true"> * </span></label>
                                    <input type="text" name="title" class="form-control" placeholder="Nhập tiêu đề tại đây..." value="{{ old('title') }}">
                                </div>
                                
                                {{-- 2. DANH MỤC (PHẦN MỚI THÊM) --}}
                                <div class="form-group {{ $errors->has('category_id') ? 'has-error' : '' }}">
                                    <label class="control-label bold">Loại tin tức <span class="required" aria-required="true"> * </span></label>
                                    <select name="category_id" class="form-control">
                                        <option value="">-- Chọn danh mục --</option>
                                        @if(isset($categories) && count($categories) > 0)
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>Chưa có danh mục nào (Vui lòng tạo trước)</option>
                                        @endif
                                    </select>
                                </div>

                                {{-- 3. ẢNH ĐẠI DIỆN --}}
                                <div class="form-group">
                                    <label class="control-label">Ảnh đại diện</label>
                                    <input type="file" name="thumbnail" class="form-control">
                                </div>

                                {{-- 4. NỘI DUNG --}}
                                <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                                    <label class="control-label bold">Nội dung chi tiết <span class="required" aria-required="true"> * </span></label>
                                    <textarea name="content" id="editor" class="form-control" rows="10">{{ old('content') }}</textarea> 
                                </div>

                            </div>
                            <div class="form-actions right">
                                <a href="{{ route('news.index') }}" class="btn default">
                                    <i class="fa fa-arrow-left"></i> Hủy bỏ
                                </a>
                                <button type="submit" class="btn green">
                                    <i class="fa fa-check"></i> Thêm mới tin tức
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'editor', {
        height: 400,
        versionCheck: false 
    });
</script>
@endsection