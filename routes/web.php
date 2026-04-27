<?php

use App\Http\Controllers\Api\TaskCalendarController ;
use App\Http\Controllers\Api\MyTaskApiController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\FacebookController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use League\Glide\ServerFactory;
use League\Glide\Responses\LaravelResponseFactory;
use App\Http\Controllers\ProfileController;

// Import Controllers
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserAuthorizationController;
use App\Http\Controllers\FeatureGroupController;
use App\Http\Controllers\GoiThauController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\UserFeatureGroupController;
use App\Http\Controllers\ZaloController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NotificationScheduleController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- GLIDE IMAGE SERVER ---
Route::get('glide/{path}', function ($path) {
    $server = ServerFactory::create([
        'source' => app('filesystem')->disk('public')->getDriver(),
        'cache' => storage_path('glide'),
        'response' => new LaravelResponseFactory(app('request'))
    ]);
    $response = $server->getImageResponse($path, request()->all());
    $response->send();
})->where('path', '.+');

// --- AUTHENTICATION ---
Route::get('login', [
    'uses' => 'LoginController@getLogin',
    'as'   => 'login'
]);
Route::post('login', [
    'uses' => 'LoginController@postLogin',
    'as'   => 'login.post'
]);
Route::get('logout', 'LoginController@getLogout')->name('logout.get');

Route::middleware(['auth'])->group(function () {

    // 1. ĐỔI MẬT KHẨU (Dùng can)
    Route::group(['middleware' => ['can:update-password']], function () {
        Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('password.change');
        Route::post('/change-password', [ChangePasswordController::class, 'store'])->name('password.update');
    });

    // 2. HỒ SƠ CÁ NHÂN (Dùng can)
    // AuthServiceProvider (Lớp 3) sẽ giúp Role User vào được đây
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index')
        ->middleware('can:read-profile');

    Route::group(['middleware' => ['can:update-profile']], function () {
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
    });
});

// --- PUBLIC ROUTES (Giữ nguyên) ---
Route::get('/', function () {
    return view('trangchu.index');
})->name('home');
Route::get('/news', [NewsController::class, 'publicIndex'])->name('news.public');
Route::get('/news/{id}-{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/info', [
    'uses' => 'CompanyController@show',
    'as' => 'company.show'
]);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (TOÀN BỘ DÙNG CAN)
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth', 'only_active_user']], function () {

    // 1. DASHBOARD
    Route::get('dashboard', [
        'middleware' => ['can:read-dashboard'], // Đổi permission -> can
        'uses' => 'DashboardController@getDashboard',
        'as'   => 'dashboard'
    ]);

    // 2. USER MANAGEMENT
    Route::prefix('users')->group(function () {
        Route::get('/', ['middleware' => ['can:read-users'], 'uses' => 'UserController@index', 'as' => 'user.index']);
        Route::get('/add', ['middleware' => ['can:create-users'], 'uses' => 'UserController@create', 'as' => 'user.add.get']);
        Route::post('/add', ['middleware' => ['can:create-users'], 'uses' => 'UserController@store', 'as' => 'user.add.post']);
        Route::get('/edit/{id}', ['middleware' => ['can:update-users'], 'uses' => 'UserController@edit', 'as' => 'user.edit.get']);
        Route::post('/edit', ['middleware' => ['can:update-users'], 'uses' => 'UserController@update', 'as' => 'user.edit.post']);
        Route::get('/delete/{id}', ['middleware' => ['can:delete-users'], 'uses' => 'UserController@destroy', 'as' => 'user.delete.get']);
    });

    // 3. STAFF MANAGEMENT
    Route::prefix('staffs')->group(function () {
        Route::get('/', ['middleware' => ['can:read-nhan-su'], 'uses' => 'NhanSuController@index', 'as' => 'nhan_su.index']);
        Route::get('/read/{id}', ['middleware' => ['can:read-nhan-su'], 'uses' => 'NhanSuController@read', 'as' => 'nhan_su.read.get']);
        Route::get('/add', ['middleware' => ['can:create-nhan-su'], 'uses' => 'NhanSuController@create', 'as' => 'nhan_su.add.get']);
        Route::post('/add', ['middleware' => ['can:create-nhan-su'], 'uses' => 'NhanSuController@store', 'as' => 'nhan_su.add.post']);
        Route::get('/edit/{id}', ['middleware' => ['can:update-nhan-su'], 'uses' => 'NhanSuController@edit', 'as' => 'nhan_su.edit.get']);
        Route::post('/edit/{id}', ['middleware' => ['can:update-nhan-su'], 'uses' => 'NhanSuController@update', 'as' => 'nhan_su.edit.post']);
        Route::get('/delete/{id}', ['middleware' => ['can:delete-nhan-su'], 'uses' => 'NhanSuController@destroy', 'as' => 'nhan_su.delete.get']);

        Route::get('/export-excel', ['middleware' => ['can:create-nhan-su'], 'uses' => 'NhanSuController@exportExcel', 'as' => 'nhan_su.export-excel.get']);
        Route::get('/import-excel', ['middleware' => ['can:create-nhan-su'], 'uses' => 'NhanSuController@importExcel', 'as' => 'nhan_su.import-excel.get']);
        Route::post('/import-excel', ['middleware' => ['can:create-nhan-su'], 'uses' => 'NhanSuController@postImportExcel', 'as' => 'nhan_su.import-excel.post']);
    });

    // 4. COMPANY SETTINGS
    Route::prefix('company')->group(function () {

        Route::get('/init', ['middleware' => ['can:update-company'], 'uses' => 'CompanyController@init', 'as' => 'company.init']);
        Route::get('/', ['middleware' => ['can:update-company'], 'uses' => 'CompanyController@index', 'as' => 'company.index']);
        Route::post('/update', ['middleware' => ['can:update-company'], 'uses' => 'CompanyController@update', 'as' => 'company.update']);
    });

    // 5. AJAX OPERATIONS (Đổi hết sang can)
    Route::prefix('ajax')->group(function () {
        Route::post('/dsBoPhanTheoPhongBan', ['uses' => 'NhanSuController@dsBoPhanTheoPhongBan', 'as' => 'dsBoPhanTheoPhongBan']);

        Route::post('/postThemHopDong', ['middleware' => ['can:create-hop-dong'], 'uses' => 'HopDongController@postThemHopDong', 'as' => 'postThemHopDong']);
        Route::post('/postTimHopDongTheoId', ['uses' => 'HopDongController@postTimHopDongTheoId', 'as' => 'postTimHopDongTheoId']);
        Route::post('/postSuaHopDong', ['middleware' => ['can:update-hop-dong'], 'uses' => 'HopDongController@postSuaHopDong', 'as' => 'postSuaHopDong']);
        Route::post('/postXoaHopDong', ['middleware' => ['can:delete-hop-dong'], 'uses' => 'HopDongController@postXoaHopDong', 'as' => 'postXoaHopDong']);

        Route::post('/postThemQuyetDinh', ['middleware' => ['can:create-quyet-dinh'], 'uses' => 'QuyetDinhController@postThemQuyetDinh', 'as' => 'postThemQuyetDinh']);
        Route::post('/postTimQuyetDinhTheoId', ['uses' => 'QuyetDinhController@postTimQuyetDinhTheoId', 'as' => 'postTimQuyetDinhTheoId']);
        Route::post('/postSuaQuyetDinh', ['middleware' => ['can:update-quyet-dinh'], 'uses' => 'QuyetDinhController@postSuaQuyetDinh', 'as' => 'postSuaQuyetDinh']);
        Route::post('/postXoaQuyetDinh', ['middleware' => ['can:delete-quyet-dinh'], 'uses' => 'QuyetDinhController@postXoaQuyetDinh', 'as' => 'postXoaQuyetDinh']);
    });

    // 6. FILE MANAGER
    Route::prefix('file-manager')->group(function () {
        Route::get('/', ['middleware' => ['can:update-file-manager'], 'uses' => 'FileManagerController@index', 'as' => 'file-manager.index']);
    });

    // 7. ACL / ROLES (Đổi permission -> can)
    Route::prefix('roles')->group(function () {
        Route::get('/', ['middleware' => ['can:read-acl'], 'uses' => 'RoleController@index', 'as' => 'role.index']);
        Route::get('/create', ['middleware' => ['can:create-acl'], 'uses' => 'RoleController@create', 'as' => 'role.create']);
        Route::post('/store', ['middleware' => ['can:create-acl'], 'uses' => 'RoleController@store', 'as' => 'role.store']);
        Route::get('/show/{id}', ['middleware' => ['can:read-acl'], 'uses' => 'RoleController@show', 'as' => 'role.show']);
        Route::get('/edit/{id}', ['middleware' => ['can:update-acl'], 'uses' => 'RoleController@edit', 'as' => 'role.edit']);
        Route::post('/edit/{id}', ['middleware' => ['can:update-acl'], 'uses' => 'RoleController@update', 'as' => 'role.update']);
    });

    // 8. NEWS
    Route::group(['prefix' => 'admin/news', 'as' => 'news.'], function () {
        Route::get('/', [NewsController::class, 'index'])->name('index')->middleware('can:read-news');

        // Tạo mới
        Route::get('/create', [NewsController::class, 'create'])->name('create')->middleware('can:create-news');
        Route::post('/store', [NewsController::class, 'store'])->name('store')->middleware('can:create-news');

        // Hiển thị form sửa
        Route::get('/edit/{id}', [NewsController::class, 'edit'])->name('edit')->middleware('can:edit-news');
        // Xử lý cập nhật dữ liệu
        Route::post('/update/{id}', [NewsController::class, 'update'])->name('update')->middleware('can:edit-news');
        // -----------------------

        // Duyệt / Từ chối / Xóa
        Route::get('/approve/{id}', [NewsController::class, 'approve'])->name('approve')->middleware('can:approve-news');
        Route::get('/reject/{id}', [NewsController::class, 'reject'])->name('reject')->middleware('can:approve-news');
        Route::get('/delete/{id}', [NewsController::class, 'destroy'])->name('destroy')->middleware('can:delete-news');
    });

    Route::get('/activity-log', [
        'middleware' => ['can:read-dashboard'], // Ai vào được dashboard thì xem được, hoặc dùng quyền riêng
        'uses' => 'LoginHistoryController@index',
        'as' => 'login-history.index'
    ]);

    Route::get('/activity-log', [
        'middleware' => ['can:read-dashboard'], // Ai vào được dashboard thì xem được, hoặc dùng quyền riêng
        'uses' => 'LoginHistoryController@index',
        'as' => 'login-history.index'
    ]);
});

// ==========================================================
// CÁC MODULE MỚI (PHÂN QUYỀN NHÓM) 
// ==========================================================

Route::group([
    'prefix' => 'groupPermissions',
    'middleware' => ['auth', 'can:manage-feature-group']
], function () {

    Route::get('/', [
        'uses' => 'FeatureGroupController@index',
        'as' => 'feature-group.index'
    ]);

    Route::post('/store', [
        'uses' => 'FeatureGroupController@store',
        'as' => 'feature-group.store'
    ]);

    Route::post('/update/{id}', [
        'uses' => 'FeatureGroupController@update',
        'as' => 'feature-group.update'
    ]);
});


// 2. MODULE PHÂN QUYỀN NGƯỜI DÙNG
// Chỉ cần kiểm tra đúng 1 quyền 'manage-user-feature'
Route::group([
    'prefix' => 'userPermissions',
    'middleware' => ['auth', 'can:manage-user-feature']
], function () {

    Route::get('/', [
        'uses' => 'UserFeatureGroupController@index',
        'as' => 'user-feature-group.index'
    ]);

    Route::post('/get-data', [
        'uses' => 'UserFeatureGroupController@getData',
        'as' => 'user-feature-group.get-data'
    ]);

    Route::post('/update', [
        'uses' => 'UserFeatureGroupController@update',
        'as' => 'user-feature-group.update'
    ]);
});


// Route hiển thị form và xử lý tìm kiếm
 Route::match(['get', 'post'], '/tra-cuu-goi-thau', [GoiThauController::class, 'index'])->name('goithau.search')->middleware('can:bid-search');;


// 1. Route xử lý Google (như cũ)
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// 2. Nhóm Route dành riêng cho Khách hàng
Route::group(['prefix' => 'customer', 'as' => 'customer.', 'middleware' => ['auth:customer']], function () {
    
    // 1. Dashboard
    // Route::get('dashboard', [
    //     'uses' => 'DashboardController@getDashboard',
    //     'as'   => 'dashboard' 
    // ]);

    // 2. Tra cứu gói thầu
    Route::match(['get', 'post'], '/tra-cuu-goi-thau', [GoiThauController::class, 'index'])
        ->name('goithau.search') 
        ->middleware('can:bid-search');
        // -> Tên đầy đủ sẽ là: customer.goithau.search (Đúng ý bạn muốn)

    // 3. Logout
    Route::post('/logout', function () {
        Auth::guard('customer')->logout();
        return redirect('/');
    })->name('logout');
});


// Route xử lý Facebook
Route::get('auth/facebook', [FacebookController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);


Route::get('auth/zalo', [ZaloController::class, 'redirectToZalo'])->name('login.zalo');
Route::get('auth/zalo/callback', [ZaloController::class, 'handleZaloCallback']);



// Đường dẫn vào trang quản lý công việc
Route::get('/quan-ly-cong-viec', function () {
    // Trả về file view tại resources/views/tasks/index.blade.php
    return view('tasks.index'); 
})->name('tasks.index')->middleware('auth');


// Trong routes/web.php
Route::get('/my-tasks', function () {
    return view('my-tasks.app'); // Trỏ đến file Blade giao diện bạn vừa tạo
})->middleware('auth')->name('my-tasks.index'); // Tên route phải khớp với sidebar

Route::get('/calendar', [TaskCalendarController::class, 'index'])->name('tasks.calendar');
Route::get('/api/calendar-tasks', [TaskCalendarController::class, 'getTasks']);
// Route nhận yêu cầu POST để cập nhật trạng thái công việc
Route::post('/api/calendar-tasks/{id}/complete', [App\Http\Controllers\Api\TaskCalendarController::class, 'completeTask']);

//Cấu hình thông báo
Route::get('/notification-config',
    [NotificationScheduleController::class,'index']
)->name('notification-config');
Route::post('/notification/store',[NotificationScheduleController::class,'store'])
->name('notification.store');
Route::put('/notification/update/{id}', 
[NotificationScheduleController::class,'update'])->name('notification.update');

Route::delete('/notification/delete/{id}', 
[NotificationScheduleController::class,'destroy'])->name('notification.delete');
Route::post('/api/tasks/{id}/not-done', [TaskController::class, 'notDone']);

///cấu hình ngày nghi
use App\Http\Controllers\HolidayController;

Route::get('/holiday-config', [HolidayController::class, 'indexView'])->name('holiday.index');

Route::post('/holiday/store', [HolidayController::class, 'store'])->name('holiday.store');

Route::put('/holiday/update/{id}', [HolidayController::class, 'update'])->name('holiday.update');

Route::delete('/holiday/delete/{id}', [HolidayController::class, 'destroy'])->name('holiday.delete');