<!-- resources/views/auth/register.blade.php -->
@extends('layouts.app')

@section('title', '註冊帳號')
@push('scripts')
<script src="{{ asset('js/register.js') }}"></script>
@endpush
@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">註冊帳號</h1>
        </div>

        <div x-data="registerForm()">
            <form @submit.prevent="register">
                <div class="form-group">
                    <label for="name" class="form-label">姓名</label>
                    <input
                        type="text"
                        id="name"
                        x-model="name"
                        class="form-input"
                        placeholder="請輸入您的姓名"
                        required>
                </div>

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
                        placeholder="請輸入密碼（至少6位數）"
                        required>
                    <!-- minlength="6" -->
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">確認密碼</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        x-model="password_confirmation"
                        class="form-input"
                        placeholder="請再次輸入密碼"
                        required>
                    <!-- minlength="6" -->
                </div>

                <button
                    type="submit"
                    class="btn btn-primary btn-block"
                    :class="{'btn-disabled': loading}"
                    :disabled="loading">
                    <span x-show="!loading">註冊</span>
                    <span x-show="loading">處理中...</span>
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
                已有帳號？<a href="{{ route('login') }}" class="footer-link">立即登入</a>
            </div>
        </div>
    </div>
</div>
@endsection