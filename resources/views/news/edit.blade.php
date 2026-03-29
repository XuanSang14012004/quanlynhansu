@extends('layouts.master')

@section('title', 'Chỉnh sửa bài viết')

@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a> <i class="fa fa-circle"></i></li>
                <li><a href="{{ route('news.index') }}">Tin tức</a> <i class="fa fa-circle"></i></li>
                <li><span>Chỉnh sửa</span></li>
            </ul>
        </div>

        <h1 class="page-title"> <i class="fa fa-pencil"></i> Chỉnh sửa bài viết </h1>

        {{-- Phần hiển thị lỗi chung (nếu có) --}}
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
                        <div class="caption font-green-haze">
                            <i class="icon-note font-green-haze"></i>
                            <span class="caption-subject bold uppercase"> Nội dung bài viết</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        
                        {{-- FORM BẮT ĐẦU --}}
                        <form action="{{ route('news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            {{-- HTML Form không hỗ trợ PUT nên ta cần method spoofing --}}
                            {{-- Bạn có thể dùng @method('PUT') nếu route của bạn là PUT/PATCH --}}
                            {{-- Nếu route là POST như ví dụ trước thì KHÔNG cần dòng @method --}}
                            
                            <div class="form-body">
                                
                                {{-- 1. TIÊU ĐỀ --}}
                                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                    <label class="control-label bold">Tiêu đề bài viết <span class="required">*</span></label>
                                    <input type="text" name="title" class="form-control" 
                                           value="{{ old('title', $news->title) }}" placeholder="Nhập tiêu đề...">
                                    
                                    @if($errors->has('title'))
                                        <span class="help-block font-red">{{ $errors->first('title') }}</span>
                                    @endif
                                </div>

                                {{-- 2. DANH MỤC (PHẦN MỚI THÊM) --}}
                                <div class="form-group {{ $errors->has('category_id') ? 'has-error' : '' }}">
                                    <label class="control-label bold">Loại tin tức <span class="required">*</span></label>
                                    <select name="category_id" class="form-control">
                                        <option value="">-- Chọn danh mục --</option>
                                        @if(isset($categories) && count($categories) > 0)
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" 
                                                    {{-- Logic chọn: Ưu tiên old input -> rồi đến dữ liệu cũ trong database --}}
                                                    {{ (old('category_id') ?? $news->category_id) == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>Chưa có danh mục nào</option>
                                        @endif
                                    </select>
                                    
                                    @if($errors->has('category_id'))
                                        <span class="help-block font-red">{{ $errors->first('category_id') }}</span>
                                    @endif
                                </div>

                                {{-- 3. ẢNH ĐẠI DIỆN --}}
                                <div class="form-group {{ $errors->has('thumbnail') ? 'has-error' : '' }}">
                                    <label class="control-label bold">Ảnh đại diện</label>
                                    
                                    {{-- Hiển thị ảnh cũ --}}
                                    @if($news->thumbnail)
                                        <div style="margin-bottom: 10px;">
                                            <img src="{{ asset($news->thumbnail) }}" alt="Old Image" 
                                                 style="max-height: 150px; border: 1px solid #ddd; padding: 3px;">
                                        </div>
                                    @endif
                                    
                                    <input type="file" name="thumbnail" class="form-control">
                                    <span class="help-block text-muted"> Để trống nếu không muốn thay đổi ảnh. </span>
                                    
                                    @if($errors->has('thumbnail'))
                                        <span class="help-block font-red">{{ $errors->first('thumbnail') }}</span>
                                    @endif
                                </div>

                                {{-- 4. NỘI DUNG --}}
                                <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                                    <label class="control-label bold">Nội dung chi tiết <span class="required">*</span></label>
                                    <textarea name="content" id="editor" class="form-control" rows="10">{{ old('content', $news->content) }}</textarea>
                                    
                                    @if($errors->has('content'))
                                        <span class="help-block font-red">{{ $errors->first('content') }}</span>
                                    @endif
                                </div>
                                
                            </div>

                            <div class="form-actions right">
                                <a href="{{ route('news.index') }}" class="btn default">
                                    <i class="fa fa-arrow-left"></i> Hủy
                                </a>
                                <button type="submit" class="btn green">
                                    <i class="fa fa-save"></i> Lưu cập nhật
                                </button>
                            </div>
                        </form>
                        {{-- FORM KẾT THÚC --}}
                        
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