@extends('layouts.master')

@section('title', 'Lịch sử đăng nhập hệ thống')

@section('content')
<div class="page-content-wrapper">
    <div class="page-content" style="background-color: #f4f6f9;">
            <link rel="stylesheet" href="{{ asset('css/login_history/index.css') }}">

            <div class="history-wrapper">
                <div class="history-card">
                    <div class="history-header">
                        <h2>
                            <i class="fa fa-history"></i>
                            Nhật ký hoạt động
                        </h2>
                    </div>

                    <div class="search-section">
                        <form action="{{ route('login-history.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label><i class="fa fa-search"></i> Từ khóa</label>
                                        <input type="text" name="keyword" class="form-control-modern"
                                            placeholder="Tên, email..." value="{{ request('keyword') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group-modern">
                                        <label><i class="fa fa-calendar"></i> Từ ngày</label>
                                        <input type="date" name="date_from" class="form-control-modern"
                                            value="{{ request('date_from') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group-modern">
                                        <label><i class="fa fa-calendar"></i> Đến ngày</label>
                                        <input type="date" name="date_to" class="form-control-modern"
                                            value="{{ request('date_to') }}">
                                    </div>
                                </div>
                                <div class="col-md-2" style="display: flex; gap: 5px;">
                                    <button class="btn-modern btn-search" type="submit">
                                        Tìm
                                    </button>
                                    @if (request('keyword') || request('date_from') || request('date_to'))
                                        <a href="{{ route('login-history.index') }}" class="btn-modern btn-reset"
                                            title="Làm mới">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th>Thời gian</th>
                                        <th>Tài khoản</th>
                                        <th>Hành động</th>
                                        <th>IP Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($histories->count() > 0)
                                        @foreach ($histories as $history)
                                            <tr>
                                                <td>
                                                    <span style="font-weight: 600; color: #555;">
                                                        {{ $history->created_at->format('H:i:s') }}
                                                    </span>
                                                    <br>
                                                    <small
                                                        style="color: #999;">{{ $history->created_at->format('d/m/Y') }}</small>
                                                </td>
                                                <td>
                                                    @if ($history->user)
                                                        <div class="user-info">
                                                            <div class="user-avatar">
                                                                {{ strtoupper(substr($history->user->name, 0, 1)) }}
                                                            </div>
                                                            <div class="user-details">
                                                                <span class="user-name">{{ $history->user->name }}</span>
                                                                <span class="user-email">{{ $history->user->email }}</span>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">User đã xóa</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($history->action == 'login')
                                                        <span class="action-badge badge-login">
                                                            <i class="fa fa-sign-in"></i> Đăng nhập
                                                        </span>
                                                    @else
                                                        <span class="action-badge badge-logout">
                                                            <i class="fa fa-sign-out"></i> Đăng xuất
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="ip-address">
                                                        {{ $history->ip_address }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center" style="padding: 50px;">
                                                <i class="fa fa-inbox" style="font-size: 40px; color: #ddd;"></i>
                                                <p style="margin-top: 10px; color: #999;">Không tìm thấy dữ liệu phù hợp</p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="pagination-container">
                        {{ $histories->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
