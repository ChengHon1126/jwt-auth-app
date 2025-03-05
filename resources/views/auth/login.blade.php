<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')
@section('title', '系統登入')

@push('scripts')
<script src="{{ asset('js/login.js') }}"></script>
@endpush


@section('content')

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">系統登入</h1>
        </div>

        <div x-data="loginForm()">
            <form @submit.prevent="login">
                <div class="form-group">
                    <label for="email" class="form-label">電子郵件</label>
                    <input
                        type="email"
                        id="email"
                        x-model="email"
                        class="form-input"
                        placeholder="請輸入電子郵件"
                        required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">密碼</label>
                    <input
                        type="password"
                        id="password"
                        x-model="password"
                        class="form-input"
                        placeholder="請輸入密碼"
                        required>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary btn-block"
                    :class="{'btn-disabled': loading}"
                    :disabled="loading">
                    <span x-show="!loading">登入</span>
                    <span x-show="loading">登入中...</span>
                </button>

                <!-- 錯誤訊息 -->
                <div
                    x-show="errorMessage"
                    x-text="errorMessage"
                    class="feedback-text text-danger"></div>

                <!-- 成功訊息 -->
                <div
                    x-show="successMessage"
                    x-text="successMessage"
                    class="feedback-text text-success"></div>
            </form>

            <div class="footer-text">
                還沒有帳號？<a href="{{ route('register') }}" class="footer-link">立即註冊</a>
            </div>
        </div>
    </div>
</div>
@endsection