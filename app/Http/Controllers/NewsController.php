<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory; // Nhớ import Model Category
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    // 1. Danh sách bài viết
    public function index()
    {
        // Eager load thêm 'category' để tối ưu query và hiển thị tên danh mục
        $news = News::with(['author', 'category'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        return view('news.index', compact('news'));
    }

    // 2. Form thêm mới
    public function create()
    {
        // Lấy danh sách danh mục đang hoạt động để truyền sang View
        $categories = NewsCategory::where('active', 1)->get();
        
        return view('news.create', compact('categories'));
    }

    // 3. Lưu bài viết
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|max:255',
            'category_id' => 'required|exists:news_categories,id', // Validate danh mục
            'content'     => 'required',
            'thumbnail'   => 'nullable|image|max:10240'
        ], [
            'title.required'       => 'Vui lòng nhập tiêu đề bài viết.',
            'category_id.required' => 'Vui lòng chọn loại tin tức.',
            'content.required'     => 'Vui lòng nhập nội dung chi tiết.',
            'thumbnail.image'      => 'File tải lên phải là hình ảnh.',
            'thumbnail.max'        => 'Dung lượng ảnh không được quá 10MB.',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title) . '-' . time();
        $data['author_id'] = Auth::id();
        $data['status'] = 0; // Mặc định chờ duyệt

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/news'), $filename);
            $data['thumbnail'] = 'uploads/news/' . $filename;
        }

        News::create($data);

        return redirect()->route('news.index')->with('success', 'Thêm mới thành công! Bài viết đang chờ phê duyệt.');
    }

    // 4. Form sửa bài viết
    public function edit($id)
    {
        $news = News::findOrFail($id);
        
        // --- SỬA LẠI: Lấy danh sách danh mục để hiển thị ở form sửa ---
        $categories = NewsCategory::where('active', 1)->get();
        
        return view('news.edit', compact('news', 'categories'));
    }

    // 5. Cập nhật bài viết
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title'       => 'required|max:255',
            'category_id' => 'required|exists:news_categories,id', // Validate danh mục
            'content'     => 'required',
            'thumbnail'   => 'nullable|image|max:10240' 
        ], [
            'title.required'       => 'Vui lòng nhập tiêu đề.',
            'category_id.required' => 'Vui lòng chọn loại tin tức.',
            'content.required'     => 'Vui lòng nhập nội dung.',
            'thumbnail.image'      => 'File tải lên phải là hình ảnh.',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title) . '-' . time();

        // Reset trạng thái về chờ duyệt khi sửa bài (Logic nghiệp vụ)
        $data['status'] = 0; 

        if ($request->hasFile('thumbnail')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($news->thumbnail && file_exists(public_path($news->thumbnail))) {
                unlink(public_path($news->thumbnail));
            }
            // Upload ảnh mới
            $file = $request->file('thumbnail');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/news'), $filename);
            $data['thumbnail'] = 'uploads/news/' . $filename;
        } else {
            // Giữ nguyên ảnh cũ
            $data['thumbnail'] = $news->thumbnail;
        }

        $news->update($data);

        return redirect()->route('news.index')->with('success', 'Cập nhật thành công! Bài viết đang chờ phê duyệt lại.');
    }

    // 6. Phê duyệt
    public function approve($id)
    {
        $news = News::findOrFail($id);
        $news->status = 1; // Đã đăng
        $news->published_at = now();
        $news->save();

        return redirect()->back()->with('success', 'Đã phê duyệt bài viết!');
    }

    // 7. Từ chối
    public function reject($id)
    {
        $news = News::findOrFail($id);
        $news->status = 2; // Từ chối
        $news->save();

        return redirect()->back()->with('warning', 'Đã từ chối bài viết.');
    }

    // 8. Xóa bài viết
    public function destroy($id)
    {
        $news = News::findOrFail($id);

        if ($news->thumbnail && file_exists(public_path($news->thumbnail))) {
            unlink(public_path($news->thumbnail));
        }

        $news->delete();

        return redirect()->back()->with('success', 'Đã xóa bài viết thành công!');
    }

   public function publicIndex(Request $request)
    {
        // 1. Lấy danh sách các danh mục đang hoạt động (để hiển thị bộ lọc bên phải hoặc menu)
        $categories = NewsCategory::where('active', 1)->get();

        // 2. Bắt đầu query tin tức (Thêm with category để lấy tên danh mục)
        $query = News::with(['author', 'category'])
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc');

        // 3. Xử lý tìm kiếm từ khóa
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                  ->orWhere('summary', 'like', '%' . $keyword . '%')
                  ->orWhere('content', 'like', '%' . $keyword . '%');
            });
        }

        // 4. Xử lý lọc theo Danh mục (Thêm đoạn này)
        // Khi URL có dạng: ?category=tin-cong-nghe
        if ($request->filled('category')) {
            $slug = $request->category;
            $query->whereHas('category', function($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        $news = $query->paginate(9);

        // Truyền thêm biến $categories sang view
        return view('news.public_index', compact('news', 'categories'));
    }

    // 10. Xem chi tiết
    public function show($id, $slug = null)
    {
        // 1. Lấy bài viết chi tiết (Kèm category và author)
        $post = News::with(['author', 'category'])
                    ->where('id', $id)
                    ->where('status', 1)
                    ->firstOrFail();

        // 2. Tăng lượt xem (nếu có cột view_count)
        // $post->increment('view_count');

        // 3. Lấy bài viết liên quan (Cùng danh mục)
        $relatedPosts = News::with('category') // Nạp category để hiện thẻ màu ở bài liên quan
            ->where('status', 1)
            ->where('id', '!=', $id) // Trừ bài đang xem
            ->where('category_id', $post->category_id) // Cùng danh mục
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
            
        // Nếu muốn hiển thị sidebar danh mục ở trang chi tiết thì lấy thêm:
        // $categories = NewsCategory::where('active', 1)->get();

        return view('news.show', compact('post', 'relatedPosts'));
    }
}