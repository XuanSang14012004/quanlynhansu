@extends('layouts.master')

@section('title', 'Quản lý công việc')

{{-- CSS SECTION: Premium Design with Modern UI/UX --}}
@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* ==================== GENERAL STYLES ==================== */
    :root {
        --primary-color: #0d6efd;
        --primary-dark: #0b5ed7;
        --primary-light: #e7f1ff;
        --success-color: #198754;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --secondary-color: #6c757d;
        --light-bg: #f8f9fa;
        --white: #ffffff;
        --text-dark: #212529;
        --text-muted: #6c757d;
        --border-color: #dee2e6;
        --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
        --radius-sm: 6px;
        --radius-md: 8px;
        --radius-lg: 12px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }



    /* ==================== PAGE HEADER ==================== */

    /* ==================== CARDS ==================== */
    .card {
        border: none;
        box-shadow: var(--shadow-sm);
        border-radius: var(--radius-lg);
        margin-bottom: 1.5rem;
        transition: var(--transition);
        background: var(--white);
        overflow: hidden;
    }

    .card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .card-title {
        font-weight: 600;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-title i {
        color: var(--primary-color);
        font-size: 1.25rem;
    }

    /* ==================== FILTER SECTION ==================== */
    .filter-section {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        padding: 1.75rem;
        border-radius: var(--radius-lg);
        border: 1px solid rgba(13, 110, 253, 0.1);
    }

    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    /* Filter Buttons Group */
    .btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .btn-group input {
        display: none;
    }

    .btn-group .btn {
        padding: 10px 20px;
        font-weight: 500;
        font-size: 0.9rem;
        border-radius: var(--radius-sm);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        flex: 0 0 auto;
    }

    .btn-group .btn-outline-primary {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        background: var(--white);
    }

    .btn-group .btn-outline-primary:hover {
        background: var(--primary-light);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
    }

    .btn-group .btn-check:checked+.btn-outline-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, #0b5ed7 100%);
        color: var(--white);
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        transform: scale(1.05);
    }

    /* Custom Date Range */
    #customDateRange {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.25rem;
        border-radius: var(--radius-md);
        border: 2px dashed var(--primary-color);
        animation: slideDown 0.3s ease;
        margin-top: 1rem;
    }

    #customDateRange.d-none {
        display: none !important;
    }

    #customDateRange:not(.d-none) {
        display: block !important;
    }

    /* Animation khi hiện */
    #customDateRange {
        animation: slideDown 0.3s ease;
    }



    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
            max-height: 0;
            overflow: hidden;
        }

        to {
            opacity: 1;
            transform: translateY(0);

            max-height: 500px;
        }
    }

    /* ==================== SEARCH & FILTER CONTROLS - ENHANCED ==================== */
    .filter-controls {
        background: rgba(13, 110, 253, 0.03) !important;
        padding: 1.5rem !important;
        border-radius: var(--radius-md) !important;
        border: 1px solid rgba(13, 110, 253, 0.1) !important;
        margin-top: 1rem !important;
    }

    .filter-controls .row {
        align-items: flex-end !important;
    }

    .filter-controls .form-label {
        display: block !important;
        margin-bottom: 0.5rem !important;
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        color: #6c757d !important;
    }

    .filter-controls .form-label i {
        margin-right: 0.35rem !important;
        font-size: 0.95rem !important;
    }

    .filter-controls .form-control,
    .filter-controls .form-select {
        border: 2px solid #dee2e6 !important;
        border-radius: var(--radius-sm) !important;
        padding: 0.75rem 1rem !important;
        transition: var(--transition) !important;
        background: var(--white) !important;
        font-size: 0.95rem !important;
        height: 45px !important;
        line-height: 1.5 !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }

    .filter-controls .form-control:focus,
    .filter-controls .form-select:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1) !important;
        outline: none !important;
        background: var(--white) !important;
    }

    .filter-controls .form-control:hover,
    .filter-controls .form-select:hover {
        border-color: var(--primary-color) !important;
    }

    .filter-controls .input-group {
        box-shadow: var(--shadow-sm) !important;
        border-radius: var(--radius-sm) !important;
        overflow: hidden !important;
        display: flex !important;
        width: 100% !important;
        flex-wrap: nowrap !important;
    }

    .filter-controls .input-group-text {
        background: linear-gradient(135deg, var(--primary-color) 0%, #0b5ed7 100%) !important;
        border: none !important;
        color: var(--white) !important;
        padding: 0 1rem !important;
        font-size: 1.1rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
        width: auto !important;
    }

    .filter-controls .input-group .form-control {
        border-left: none !important;
        padding-left: 0.75rem !important;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        flex: 1 1 auto !important;
        width: 1% !important;
        min-width: 0 !important;
    }

    .filter-controls .input-group:focus-within {
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15) !important;
    }

    .filter-controls .input-group:focus-within .input-group-text {
        background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%) !important;
    }

    .filter-controls .btn {
        height: 45px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem !important;
        white-space: nowrap !important;
        box-sizing: border-box !important;
    }

    /* Override any conflicting Bootstrap styles */
    .filter-controls select.form-select {
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
        background-repeat: no-repeat !important;
        background-position: right 0.75rem center !important;
        background-size: 16px 12px !important;
        padding-right: 2.5rem !important;
    }

    .filter-controls input[type="text"].form-control,
    .filter-controls input[type="search"].form-control {
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
    }

    /* Ensure proper spacing */
    .filter-controls .col-md-5,
    .filter-controls .col-md-3,
    .filter-controls .col-md-2 {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }

    /* Remove any inherited placeholder styles */
    .filter-controls .form-control::placeholder {
        color: #adb5bd !important;
        opacity: 1 !important;
    }

    /* Fix for any inherited button styles */
    .filter-controls .btn-outline-secondary {
        border: 2px solid var(--secondary-color) !important;
        color: var(--secondary-color) !important;
        background: var(--white) !important;
        font-weight: 600 !important;
    }

    .filter-controls .btn-outline-secondary:hover {
        background: var(--secondary-color) !important;
        color: var(--white) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3) !important;
    }

    .filter-controls .btn-outline-secondary i {
        font-size: 1rem !important;
    }

    /* ==================== GENERAL FORM CONTROLS ==================== */
    .form-control,
    .form-select {
        border: 2px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 0.75rem 1rem;
        transition: var(--transition);
        background: var(--white);
        font-size: 0.95rem;
        height: 45px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        outline: none;
        background: var(--white);
    }

    .form-control:hover,
    .form-select:hover {
        border-color: var(--primary-color);
    }

    /* ==================== BUTTONS ==================== */
    .btn {
        font-weight: 600;
        border-radius: var(--radius-sm);
        padding: 0.75rem 1.5rem;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, #0b5ed7 100%);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        color: var(--white);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(13, 110, 253, 0.4);
    }

    .btn-outline-secondary {
        border: 2px solid var(--secondary-color);
        color: var(--secondary-color);
        background: var(--white);
    }

    .btn-outline-secondary:hover {
        background: var(--secondary-color);
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
    }

    .btn i {
        font-size: 1rem;
    }

    /* ==================== TABLE STYLING ==================== */
    .table-responsive {
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .table {
        margin-bottom: 0;
        background: var(--white);
    }



    .table-primary th {
        background: #dceafe;
        color: black;
        border: none;
        font-weight: 600;
        padding: 1rem;

        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .table tbody tr {
        transition: var(--transition);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .table tbody tr:hover {
        background: linear-gradient(90deg, rgba(13, 110, 253, 0.03) 0%, rgba(13, 110, 253, 0.08) 100%);
        transform: scale(1.005);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
    }

    /* Empty State */
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 3rem;
        color: var(--text-muted);
        opacity: 0.5;
        margin-bottom: 1rem;
    }

    /* ==================== BADGES ==================== */
    .badge {
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.8rem;
        border-radius: 50px;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .badge-success {
        background: linear-gradient(135deg, #28a745 0%, #198754 100%);
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .badge-warning {
        background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
        color: #000;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    }

    .badge-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }

    /* ==================== ACTION BUTTONS ==================== */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: var(--radius-sm);
    }

    .btn-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: var(--white);
        box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);
    }

    .btn-info:hover {
        background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(23, 162, 184, 0.4);
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #198754 100%);
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #198754 0%, #157347 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    }

    .btn-warning {
        background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
        color: #000;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    }

    .btn-warning:hover {
        background: linear-gradient(135deg, #ffb300 0%, #ffa000 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
    }

    /* ==================== MODALS ==================== */
    .modal-content {
        border: none;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 2px solid var(--border-color);
        padding: 1.25rem 1.5rem;
    }

    .modal-header.bg-success {
        background: linear-gradient(135deg, #28a745 0%, #198754 100%);
    }

    .modal-title {
        font-weight: 700;
        font-size: 1.25rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        background: var(--light-bg);
        border-top: 2px solid var(--border-color);
    }

    /* ==================== SCROLLBAR ==================== */
    ::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }

    ::-webkit-scrollbar-track {
        background: var(--light-bg);
        border-radius: var(--radius-sm);
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--primary-color) 0%, #0b5ed7 100%);
        border-radius: var(--radius-sm);
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
    }

    /* ==================== RESPONSIVE ==================== */
    @media (max-width: 768px) {
        .page-title {
            font-size: 1.5rem;
        }

        .filter-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .btn-group {
            width: 100%;
        }

        .btn-group .btn {
            flex: 1 1 calc(50% - 0.25rem);
            min-width: 120px;
        }

        .filter-controls {
            padding: 1rem !important;
        }

        .filter-controls .col-md-5,
        .filter-controls .col-md-3,
        .filter-controls .col-md-2 {
            width: 100% !important;
            margin-bottom: 1rem !important;
        }

        .filter-controls .row {
            gap: 0 !important;
        }

        .action-buttons {
            flex-direction: column;
        }
    }

    /* ==================== CRITICAL OVERRIDES - MUST BE LAST ==================== */
    .page-content .card .filter-section .filter-controls {
        background: rgba(13, 110, 253, 0.03) !important;
    }

    .page-content .card .filter-section .filter-controls .form-control,
    .page-content .card .filter-section .filter-controls .form-select,
    .page-content .card .filter-section .filter-controls .btn {
        box-sizing: border-box !important;
        margin: 0 !important;
    }

    /* Force proper display for search input group */
    .page-content .filter-controls .input-group {
        position: relative !important;
        display: flex !important;
        flex-wrap: nowrap !important;
        align-items: stretch !important;
    }

    .page-content .filter-controls .input-group>* {
        position: relative !important;
        flex: 1 1 auto !important;
    }

    .page-content .filter-controls .input-group-text {
        flex: 0 0 auto !important;
        width: auto !important;
    }

    /* Remove any padding/margin conflicts */
    .page-content .filter-controls .row>* {
        padding-left: calc(var(--bs-gutter-x) * 0.5) !important;
        padding-right: calc(var(--bs-gutter-x) * 0.5) !important;
    }

    /* Ensure labels don't break layout */
    .page-content .filter-controls label.form-label {
        width: 100% !important;
        display: block !important;
    }

    /* ==================== DATE FILTER - TAB STYLE ==================== */

    .btn-group {
        display: inline-flex;
        border: 1px solid #2563eb;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
    }

    /* Ẩn radio */
    .btn-check {
        display: none;
    }

    /* Button */
    .btn-group .btn {
        border: none;
        background: #fff;
        color: #2563eb;
        font-size: 14px;
        font-weight: 500;
        padding: 8px 16px;
        border-right: 1px solid #2563eb;
        border-radius: 0 !important;
    }

    /* Bỏ border phải của thằng cuối */
    .btn-group .btn:last-child {
        border-right: none;
    }

    /* Hover */
    .btn-group .btn:hover {
        background: #eff6ff;
    }

    /* Active */
    .btn-check:checked+.btn {
        background: #2563eb;
        color: #fff;
    }

    /* Icon */
    .btn-group .btn i {
        margin-right: 6px;
    }


</style>
@endsection

@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    {{-- Giả sử bạn có route dashboard, nếu không có thể đổi thành '/' --}}
                    <a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> Trang chủ</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span class="active">Quản lý công việc</span>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">

                <!-- Filter Card -->
                <div class="card hover-lift">
                    <div class="card-body filter-section">
                        <div class="filter-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-funnel"></i> Bộ lọc & Tìm kiếm
                            </h5>
                            <button class="btn btn-primary" onclick="openTaskDialog()">
                                <i class="bi bi-plus-circle"></i> Thêm mới
                            </button>
                        </div>

                        <!-- Date Filter Buttons -->
                        <div class="mb-3">
                            <div class="btn-group mb-2" role="group">
                                <input type="radio" class="btn-check" name="dateFilter" id="all" value="all" checked onchange="handleDateFilterChange()">
                                <label class="btn btn-outline-primary" for="all">
                                    <i class="bi bi-calendar-week"></i> Tất cả
                                </label>

                                <input type="radio" class="btn-check" name="dateFilter" id="yesterday" value="yesterday" onchange="handleDateFilterChange()">
                                <label class="btn btn-outline-primary" for="yesterday">
                                    <i class="bi bi-calendar-minus"></i> Hôm qua
                                </label>

                                <input type="radio" class="btn-check" name="dateFilter" id="today" value="today" onchange="handleDateFilterChange()">
                                <label class="btn btn-outline-primary" for="today">
                                    <i class="bi bi-calendar-check"></i> Hôm nay
                                </label>

                                <input type="radio" class="btn-check" name="dateFilter" id="tomorrow" value="tomorrow" onchange="handleDateFilterChange()">
                                <label class="btn btn-outline-primary" for="tomorrow">
                                    <i class="bi bi-calendar-plus"></i> Ngày mai
                                </label>

                                <input type="radio" class="btn-check" name="dateFilter" id="thisWeek" value="thisWeek" onchange="handleDateFilterChange()">
                                <label class="btn btn-outline-primary" for="thisWeek">
                                    <i class="bi bi-calendar3"></i> Tuần này
                                </label>

                                <input type="radio" class="btn-check" name="dateFilter" id="custom" value="custom" onchange="handleDateFilterChange()">
                                <label class="btn btn-outline-primary" for="custom">
                                    <i class="bi bi-calendar-range"></i> Từ ngày - đến ngày
                                </label>
                            </div>

                            <!-- Custom Date Range -->
                            <div id="customDateRange" class="d-none">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold"><i class="bi bi-calendar-event"></i> Từ ngày:</label>
                                        <input type="date" class="form-control" id="fromDate" onchange="applyFilters()">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold"><i class="bi bi-calendar-event"></i> Đến ngày:</label>
                                        <input type="date" class="form-control" id="toDate" onchange="applyFilters()">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Search and Filter Controls -->
                        <div class="filter-controls">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold text-muted small mb-2">
                                        <i class="bi bi-search"></i> Tìm kiếm
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-search"></i>
                                        </span>
                                        <input type="text" class="form-control" id="searchText"
                                            placeholder="Nhập từ khóa tìm kiếm công việc..."
                                            oninput="applyFilters()">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold text-muted small mb-2">
                                        <i class="bi bi-people"></i> Nhân viên
                                    </label>
                                    <select class="form-select" id="assigneeFilter" onchange="applyFilters()">
                                        <option value="all">Tất cả nhân viên</option>
                                        {{-- User options will be populated by JS --}}
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold text-muted small mb-2">
                                        <i class="bi bi-flag"></i> Trạng thái
                                    </label>
                                    <select class="form-select" id="statusFilter" onchange="applyFilters()">
                                        <option value="all">Tất cả</option>
                                        <option value="0">Chờ xử lý</option>
                                        <option value="1">Hoàn thành</option>
                                        <option value="2">Hết hạn</option>
                                        <option value="3">Không thực hiện</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold text-muted small mb-2 d-block">
                                        &nbsp;
                                    </label>
                                    <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                        <i class="bi bi-arrow-counterclockwise"></i> Xóa lọc
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasks Table Card -->
                <div class="card hover-lift">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width:35%">Nội dung công việc</th>
                                        <th>Thời gian</th>
                                        <th>Ngày thực hiện</th>
                                        <th>Người thực hiện</th>
                                        <th>Trạng thái</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="tasksTableBody">
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bi bi-inbox"></i>
                                                <h6 class="fw-semibold text-muted">Chưa có công việc nào</h6>
                                                <p class="text-muted small">Nhấn "Thêm mới" để tạo công việc đầu tiên</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- MODALS -->

<!-- Task Modal (Add/Edit) -->
<div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 8px; box-shadow: 0 5px 25px rgba(0,0,0,0.2);">

            <div class="modal-header" style="border-bottom: 1px solid #dee2e6; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; background-color: #fff; border-radius: 8px 8px 0 0;">
                <h5 class="modal-title" id="taskModalTitle" style="font-size: 18px; font-weight: 600; color: #333; margin: 0;">
                    Thêm công việc mới
                </h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: none; font-size: 24px; line-height: 1; color: #666; cursor: pointer; padding: 0; outline: none; margin-top: -2px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" style="padding: 24px; background-color: #fff;">
                <form id="taskForm">
                    <input type="hidden" id="taskId">

                    <div style="margin-bottom: 20px;">
                        <label style="font-size: 14px; font-weight: 600; color: #555; margin-bottom: 8px; display: block;">
                            Nội dung công việc <span style="color: #dc3545;">*</span>
                        </label>
                        <input type="text" id="taskContent" required style="width: 100%; border: 1px solid #ced4da; border-radius: 4px; padding: 10px 12px; font-size: 14px; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#0d6efd'" onblur="this.style.borderColor='#ced4da'">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="font-size: 14px; font-weight: 600; color: #555; margin-bottom: 8px; display: block;">
                            Mô tả công việc
                        </label>
                        <textarea id="taskDescription" rows="4" style="width: 100%; border: 1px solid #ced4da; border-radius: 4px; padding: 10px 12px; font-size: 14px; outline: none; resize: vertical; transition: border-color 0.2s;" onfocus="this.style.borderColor='#0d6efd'" onblur="this.style.borderColor='#ced4da'"></textarea>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="font-size: 14px; font-weight: 600; color: #555; margin-bottom: 8px; display: block;">
                            Thời gian thực hiện (tùy chọn)
                        </label>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <input type="time" id="taskStartTime" style="width: 160px; border: 1px solid #ced4da; border-radius: 4px; padding: 10px 12px; font-size: 14px; outline: none;">
                            <span style="color: #555; font-size: 14px; white-space: nowrap;">đến</span>
                            <input type="time" id="taskEndTime" style="width: 160px; border: 1px solid #ced4da; border-radius: 4px; padding: 10px 12px; font-size: 14px; outline: none;">
                        </div>
                    </div>

                    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                        <div style="flex: 1;">
                            <label style="font-size: 14px; font-weight: 600; color: #555; margin-bottom: 8px; display: block;">
                                Ngày bắt đầu <span style="color: #dc3545;">*</span>
                            </label>
                            <input type="date" id="taskStartDate" required style="width: 100%; border: 1px solid #ced4da; border-radius: 4px; padding: 10px 12px; font-size: 14px; outline: none;">
                        </div>
                        <div style="flex: 1;">
                            <label style="font-size: 14px; font-weight: 600; color: #555; margin-bottom: 8px; display: block;">
                                Ngày kết thúc <span style="color: #dc3545;">*</span>
                            </label>
                            <input type="date" id="taskEndDate" required style="width: 100%; border: 1px solid #ced4da; border-radius: 4px; padding: 10px 12px; font-size: 14px; outline: none;">
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="font-size: 14px; font-weight: 600; color: #555; margin-bottom: 8px; display: block;">
                            Người thực hiện <span style="color: #dc3545;">*</span>
                        </label>
                        <select id="taskAssignee" multiple required style="width: 100%; border: 1px solid #ced4da; border-radius: 4px; padding: 10px 12px; font-size: 14px; outline: none; background-color: #fff; cursor: pointer;">
                            {{-- Các option user sẽ được load qua JS --}}
                        </select>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="font-size: 14px; font-weight: 600; color: #555; margin-bottom: 8px; display: block;">
                            Trạng thái công việc
                        </label>
                        <select id="taskStatus" style="width: 100%; border: 1px solid #ced4da; border-radius: 4px; padding: 10px 12px; font-size: 14px; outline: none; background-color: #fff; cursor: pointer;">
                            <option value="0">Chờ xử lý</option>
                            <option value="1">Đã hoàn thành</option>
                            <option value="2">Hết hạn xử lý</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 0;">
                        <label style="font-size: 14px; font-weight: 600; color: #555; margin-bottom: 8px; display: block;">
                            Tài liệu đính kèm
                        </label>
                        <input type="file" id="taskAttachment" 
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip"
                        style="width: 100%; border: 1px solid #ced4da; border-radius: 4px; padding: 7px 12px; font-size: 14px; outline: none; background-color: #fff;">
                    </div>
                </form>
            </div>

            <div class="modal-footer" style="border-top: 1px solid #dee2e6; padding: 16px 24px; background-color: #fff; border-radius: 0 0 8px 8px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" data-dismiss="modal" data-bs-dismiss="modal" style="background-color: #6c757d; color: #fff; border: none; padding: 8px 24px; border-radius: 4px; font-size: 14px; font-weight: 500; cursor: pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                    Hủy
                </button>
                <button type="button" onclick="saveTask()" style="background-color: #0d6efd; color: #fff; border: none; padding: 8px 24px; border-radius: 4px; font-size: 14px; font-weight: 500; cursor: pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                    Lưu
                </button>
            </div>
        </div>
    </div>
</div>
<!-- View Modal -->


<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle"></i> Xác nhận xóa
                </h5>

            </div>
            <div class="modal-body">
                <input type="hidden" id="deleteTaskId">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <div>
                        Bạn có chắc chắn muốn xóa công việc này không? <br>
                        <strong>Hành động này không thể hoàn tác.</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Hủy
                </button>
                <button type="button" class="btn btn-danger" onclick="executeDelete()">
                    <i class="bi bi-trash"></i> Xóa ngay
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Complete Modal -->
<div class="modal fade" id="completeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-check-circle"></i> Xác nhận công việc
                </h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="completeTaskId">
                <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>
                        Công việc:<br>
                        <strong id="completeTaskContent"></strong>
                    </span>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-journal-text"></i> Ghi chú:
                    </label>
                    <textarea class="form-control" id="completionNote" rows="4" placeholder="Nhập kết quả công việc, ghi chú hoàn thành..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Đóng
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmNotDone()">
                    <i class="bi bi-x-octagon"></i> Không thực hiện
                </button>
                <button type="button" class="btn btn-success" onclick="confirmComplete()">
                    <i class="bi bi-send"></i> Hoàn thành
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Date Filter Change Handler
    function handleDateFilterChange() {
        const customFilter = document.getElementById('custom');
        const customDateRange = document.getElementById('customDateRange');

        if (customFilter.checked) {
            customDateRange.classList.remove('d-none');
        } else {
            customDateRange.classList.add('d-none');
        }

        applyFilters();
    }


    document.addEventListener('DOMContentLoaded', function() {
        // $('#taskAssignee').select2({
        //     placeholder: "Chọn người thực hiện...",
        //     allowClear: true,
        //     width: '100%', // Đảm bảo full width
        //     dropdownParent: $('#taskModal')
        // });
        // Từ ngày
        flatpickr("#fromDate", {
            dateFormat: "Y-m-d", // ✅ GỬI yyyy-mm-dd (backend Laravel cần format này)
            altInput: true, // Tạo input phụ để hiển thị
            altFormat: "d/m/Y", // ✅ HIỂN THỊ dd/mm/yyyy cho người dùng
            locale: "vn",
            onChange: function(selectedDates, dateStr, instance) {
                applyFilters();
            }
        });

        // Đến ngày
        flatpickr("#toDate", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            locale: "vn",
            onChange: function(selectedDates, dateStr, instance) {
                applyFilters();
            }
        });

        // Tab "Của tôi" (nếu có)
        if (document.getElementById('myFromDate')) {
            flatpickr("#myFromDate", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                locale: "vn",
                onChange: function() {
                    applyFilters();
                }
            });
        }

        if (document.getElementById('myToDate')) {
            flatpickr("#myToDate", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                locale: "vn",
                onChange: function() {
                    applyFilters();
                }
            });
        }
    });
</script>
<script src="{{ asset('js/task-manager.js') }}"></script>
@endsection