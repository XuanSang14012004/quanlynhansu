@extends('layouts.master')

@section('title', 'Tra cứu Gói thầu')

@section('content')
    <div class="page-content-wrapper">
        <div class="page-content" style="background-color: #f3f4f6; min-height: 100vh; padding: 30px;">

            {{-- CSS Custom --}}
            <style>
                :root {
                    --primary-color: #2563eb;
                    --primary-hover: #1d4ed8;
                    --bg-card: #ffffff;
                    --text-main: #1f2937;
                    --text-secondary: #6b7280;
                    --border-color: #e5e7eb;
                }

                body {
                    font-family: 'Inter', 'Segoe UI', sans-serif;
                    color: var(--text-main);
                }

                .page-container-bg-solid .page-bar,
                .page-content-white .page-bar {
                    background-color: transparent
                }

                /* --- BREADCRUMB STYLE (MỚI THÊM) --- */
                .page-bar {
                    margin-bottom: 24px;
                    display: flex;
                    align-items: center;
                    background: transparent;
                }

                .page-breadcrumb {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                    display: flex;
                    align-items: center;
                }

                .page-breadcrumb li {
                    display: flex;
                    align-items: center;
                    font-size: 14px;
                    color: var(--text-secondary);
                    font-weight: 500;
                }

                .page-breadcrumb li a {
                    color: var(--text-secondary);
                    text-decoration: none;
                    transition: color 0.2s;
                }

                .page-breadcrumb li a:hover {
                    color: var(--primary-color);
                }

                /* Chỉnh lại icon tròn ngăn cách cho tinh tế hơn */
                .page-breadcrumb li i.fa-circle {
                    font-size: 4px;
                    /* Thu nhỏ dấu chấm */
                    margin: 0 12px;
                    color: #cbd5e1;
                    vertical-align: middle;
                    transform: translateY(-1px);
                }

                .page-breadcrumb li span.active {
                    color: var(--primary-color);
                    font-weight: 600;
                }

                /* ----------------------------------- */

                /* Card Style */
                .modern-card {
                    background: var(--bg-card);
                    border-radius: 12px;
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 15px -3px rgba(0, 0, 0, 0.05);
                    margin-bottom: 24px;
                    border: 1px solid var(--border-color);
                    overflow: hidden;
                }

                .card-header-clean {
                    padding: 20px 24px;
                    border-bottom: 1px solid var(--border-color);
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    background-color: #fff;
                }

                .header-title {
                    font-size: 18px;
                    font-weight: 700;
                    color: #111827;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                /* Search Form */
                .search-wrapper {
                    position: relative;
                }

                .modern-input {
                    width: 100%;
                    padding: 14px 20px;
                    padding-right: 140px;
                    border: 2px solid #e5e7eb;
                    border-radius: 8px;
                    font-size: 15px;
                    transition: all 0.2s;
                    background-color: #f9fafb;
                }

                .modern-input:focus {
                    border-color: var(--primary-color);
                    background-color: #fff;
                    outline: none;
                    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
                }

                .btn-search-modern {
                    position: absolute;
                    right: 6px;
                    top: 6px;
                    bottom: 6px;
                    background-color: var(--primary-color);
                    color: white;
                    border: none;
                    border-radius: 6px;
                    padding: 0 24px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: background 0.2s;
                }

                .btn-search-modern:hover {
                    background-color: var(--primary-hover);
                }

                /* Filters as Pills */
                .filter-row {
                    display: flex;
                    gap: 12px;
                    margin-top: 20px;
                    flex-wrap: wrap;
                }

                .filter-checkbox {
                    display: none;
                }

                .filter-label {
                    padding: 8px 16px;
                    background-color: #f3f4f6;
                    border: 1px solid #d1d5db;
                    border-radius: 20px;
                    font-size: 14px;
                    color: #4b5563;
                    cursor: pointer;
                    transition: all 0.2s;
                    user-select: none;
                    display: flex;
                    align-items: center;
                    gap: 6px;
                }

                .filter-checkbox:checked+.filter-label {
                    background-color: #eff6ff;
                    border-color: var(--primary-color);
                    color: var(--primary-color);
                    font-weight: 600;
                }

                .filter-checkbox:checked+.filter-label::before {
                    content: '✓';
                    font-weight: bold;
                }

                /* Table Styling */
                .table-responsive {
                    overflow-x: auto;
                }

                .modern-table {
                    width: 100%;
                    border-collapse: collapse;
                    min-width: 1200px;
                }

                .modern-table thead th {
                    background-color: #f8fafc;
                    color: #475569;
                    font-weight: 600;
                    font-size: 13px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    padding: 16px;
                    border-bottom: 2px solid #e2e8f0;
                    text-align: left;
                    white-space: nowrap;
                }

                .modern-table tbody td {
                    padding: 16px;
                    border-bottom: 1px solid #f1f5f9;
                    font-size: 14px;
                    vertical-align: middle;
                    color: #334155;
                }

                .modern-table tbody tr:hover {
                    background-color: #f8fafc;
                }

                /* Badges & Elements */
                .stt-badge {
                    background: #e2e8f0;
                    color: #475569;
                    width: 28px;
                    height: 28px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 50%;
                    font-size: 12px;
                    font-weight: bold;
                    margin: 0 auto;
                }

                .price-tag {
                    color: #059669;
                    font-weight: 700;
                    font-family: 'Consolas', monospace;
                    letter-spacing: -0.5px;
                }

                .field-badge {
                    background-color: #e0f2fe;
                    color: #0369a1;
                    padding: 4px 10px;
                    border-radius: 6px;
                    font-size: 12px;
                    font-weight: 600;
                }

                .link-code {
                    color: var(--primary-color);
                    font-weight: 600;
                    text-decoration: none;
                    background: #eff6ff;
                    padding: 4px 8px;
                    border-radius: 4px;
                    transition: 0.2s;
                    font-size: 13px;
                }

                .link-code:hover {
                    background: var(--primary-color);
                    color: white;
                }

                .status-text {
                    font-size: 13px;
                    color: #4b5563;
                    background: #f3f4f6;
                    padding: 4px 8px;
                    border-radius: 4px;
                    white-space: nowrap;
                }

                /* Alert & Empty State */
                .alert-modern {
                    padding: 16px;
                    border-radius: 8px;
                    border-left: 4px solid;
                    margin-bottom: 20px;
                }

                .alert-error {
                    background: #fef2f2;
                    border-color: #ef4444;
                    color: #991b1b;
                }

                .alert-warning {
                    background: #fffbeb;
                    border-color: #f59e0b;
                    color: #92400e;
                }

                .empty-state {
                    text-align: center;
                    padding: 60px 20px;
                    color: var(--text-secondary);
                }

                .empty-state i {
                    font-size: 64px;
                    color: #d1d5db;
                    margin-bottom: 20px;
                }

                /* Pagination Clean */
                .pagination {
                    margin: 0;
                    justify-content: flex-end;
                }

                .page-item .page-link {
                    border: 1px solid #e5e7eb;
                    color: #374151;
                    margin: 0 2px;
                    border-radius: 6px;
                    padding: 6px 12px;
                }

                .page-item.active .page-link {
                    background-color: var(--primary-color);
                    border-color: var(--primary-color);
                    color: white;
                }
            </style>

            {{-- BREADCRUMB (ĐÃ THÊM) --}}
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        {{-- Giả sử bạn có route dashboard, nếu không có thể đổi thành '/' --}}
                        <a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> Trang chủ</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span class="active">Tra cứu Gói thầu</span>
                    </li>
                </ul>
            </div>

            {{-- FORM TÌM KIẾM --}}
            <div class="modern-card">
                <div class="card-header-clean">
                    <div class="header-title">
                        <i class="fa fa-search" style="color: var(--primary-color);"></i>
                        Bộ lọc tìm kiếm
                    </div>
                </div>
                <div style="padding: 24px;">
                    @if (isset($error))
                        <div class="alert-modern alert-error">
                            <strong><i class="fa fa-exclamation-circle"></i> Lỗi kết nối:</strong> {{ $error }}
                        </div>
                    @endif

                    {{-- Logic: Nếu là Khách hàng -> Gửi về route Khách. Nếu là Admin -> Gửi về route Admin --}}
                    <form
                        action="{{ Auth::guard('customer')->check() ? route('customer.goithau.search') : route('goithau.search') }}"
                        method="POST">
                        @csrf
                        <div class="search-wrapper">
                            <input type="text" name="search" class="modern-input"
                                placeholder="Nhập tên gói thầu hoặc mã KHLCNT..." value="{{ request('search') }}" required>
                            <button type="submit" class="btn-search-modern">
                                <i class="fa fa-search"></i> Tìm kiếm
                            </button>
                        </div>

                        <div class="filter-row">
                            <div class="filter-item">
                                <input type="checkbox" id="chk_tomtat" name="theo_tomtatcv" value="1"
                                    class="filter-checkbox" {{ request('theo_tomtatcv') ? 'checked' : '' }}>
                                <label for="chk_tomtat" class="filter-label">Tìm theo tóm tắt CV</label>
                            </div>

                            <div class="filter-item">
                                <input type="checkbox" id="chk_sort" name="sort_price_desc" value="1"
                                    class="filter-checkbox" {{ request('sort_price_desc') ? 'checked' : '' }}>
                                <label for="chk_sort" class="filter-label">Giá cao xuống thấp</label>
                            </div>

                            {{-- Radio: Tất cả (Value rỗng) --}}
                            <div class="filter-item">
                                <input type="radio" id="rad_all" name="skip_direct_contract" value=""
                                    class="filter-checkbox" {{ !request('skip_direct_contract') ? 'checked' : '' }}>
                                <label for="rad_all" class="filter-label">Tất cả gói</label>
                            </div>

                            {{-- Radio: Bỏ qua CĐT (Value 1) --}}
                            <div class="filter-item">
                                <input type="radio" id="rad_skip" name="skip_direct_contract" value="1"
                                    class="filter-checkbox" {{ request('skip_direct_contract') == '1' ? 'checked' : '' }}>
                                <label for="rad_skip" class="filter-label">Bỏ qua chỉ định thầu</label>
                            </div>

                            {{-- Radio: Chỉ lấy CĐT (Value 2 - Mới thêm) --}}
                            <div class="filter-item">
                                <input type="radio" id="rad_only" name="skip_direct_contract" value="2"
                                    class="filter-checkbox" {{ request('skip_direct_contract') == '2' ? 'checked' : '' }}>
                                <label for="rad_only" class="filter-label"
                                    style="color: #d97706; border-color: #d97706;">Chỉ lấy chỉ định thầu</label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- KẾT QUẢ TÌM KIẾM --}}
            @if (isset($result) && count($result) > 0)
                <div class="modern-card">
                    <div class="card-header-clean">
                        <div class="header-title">
                            <i class="fa fa-table" style="color: #64748b;"></i> Kết quả tra cứu
                        </div>
                        <div>
                            <span style="font-size: 14px; color: #6b7280;">
                                Hiển thị <strong>{{ $result->count() }}</strong> / {{ $result->total() }} bản ghi
                            </span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th class="text-center" width="50px">STT</th>
                                    <th width="18%">Chủ Đầu Tư</th>
                                    <th width="25%">Tên Gói Thầu</th>
                                    <th width="10%">Mã KHLCNT</th>
                                    <th width="10%">Lĩnh Vực</th>
                                    <th class="text-right" width="10%">Giá Gói</th>
                                    <th>TG Bắt đầu</th>
                                    <th>Ngày QĐ</th>
                                    <th>Ngày Đăng</th>
                                    <th>Hình thức</th>
                                    <th>Phương thức</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($result as $row)
                                    <tr>
                                        <td class="text-center">
                                            <div class="stt-badge">
                                                {{ ($result->currentPage() - 1) * $result->perPage() + $loop->iteration }}
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-weight: 600; color: #1e293b; line-height: 1.4;">
                                                {{ $row->chuDauTu }}</div>
                                        </td>
                                        <td>
                                            <div style="line-height: 1.5; color: #374151;">{{ $row->tenGoiThau }}</div>
                                        </td>
                                        <td>
                                            <a href="https://muasamcong.mpi.gov.vn/web/guest/contractor-selection?render=url-redirect&type=KHLCNT&code={{ $row->maKHLCNT }}"
                                                target="_blank" class="link-code" title="Xem chi tiết trên MSC">
                                                {{ $row->maCode }} <i class="fa fa-external-link"
                                                    style="font-size: 10px;"></i>
                                            </a>
                                        </td>
                                        <td><span class="field-badge">{{ $row->linhVuc }}</span></td>
                                        <td class="text-right"><span
                                                class="price-tag">{{ number_format($row->giaGoi, 0, ',', '.') }}</span>
                                        </td>
                                        <td><small>{{ $row->thoiGianBatDauTC }}</small></td>
                                        <td><small>{{ $row->ngayQD }}</small></td>
                                        <td>
                                            <div style="color: #4b5563; font-size: 13px;">
                                                {{ date('d/m/Y', $row->ngayDang) }}
                                            </div>
                                        </td>
                                        <td><span class="status-text">{{ $row->hinhThucLCNT }}</span></td>
                                        <td><span class="status-text">{{ $row->phuongThucLCNT }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- PHÂN TRANG --}}
                    <div style="padding: 20px; border-top: 1px solid #f1f5f9;">
                        <div class="row align-items-center">
                            <div class="col-12 d-flex justify-content-end">
                                {{ $result->appends(request()->all())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(request()->isMethod('post') || request()->has('search'))
                <div class="alert-modern alert-warning">
                    <h5 style="margin: 0 0 5px 0; font-weight: bold;">Không tìm thấy kết quả!</h5>
                    <span>Không có gói thầu nào phù hợp với từ khóa "<strong>{{ request('search') }}</strong>".</span>
                </div>
            @else
                <div class="modern-card">
                    <div class="empty-state">
                        <i class="fa fa-search-plus"></i>
                        <h3 style="color: #374151; font-weight: 700; margin-top: 30px;">Tra cứu thông tin đấu thầu</h3>
                        <p>Nhập từ khóa và nhấn tìm kiếm để bắt đầu khai thác dữ liệu.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
