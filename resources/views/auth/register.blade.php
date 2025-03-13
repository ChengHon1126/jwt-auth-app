<!-- resources/views/auth/register.blade.php -->
@extends('layouts.app')

@section('title', '註冊帳號')
@push('scripts')
<script src="{{ asset('js/register.js') }}"></script>
@endpush

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-md">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900">註冊帳號</h1>
        </div>

        <div x-data="registerForm()">
            <form @submit.prevent="register" class="mt-8 space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">姓名</label>
                    <div class="mt-1">
                        <input
                            type="text"
                            id="name"
                            x-model="name"
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="請輸入您的姓名"
                            required>
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">電子郵件</label>
                    <div class="mt-1">
                        <input
                            type="email"
                            id="email"
                            x-model="email"
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="請輸入電子郵件"
                            required>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">密碼</label>
                    <div class="mt-1">
                        <input
                            type="password"
                            id="password"
                            x-model="password"
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="請輸入密碼（至少6位數）"
                            required>
                        <!-- minlength="6" -->
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">確認密碼</label>
                    <div class="mt-1">
                        <input
                            type="password"
                            id="password_confirmation"
                            x-model="password_confirmation"
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="請再次輸入密碼"
                            required>
                        <!-- minlength="6" -->
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
                        :class="{'opacity-75 cursor-not-allowed': loading}"
                        :disabled="loading">
                        <span x-show="!loading">註冊</span>
                        <span x-show="loading">處理中...</span>
                    </button>
                </div>

                <!-- 錯誤訊息 -->
                <div
                    x-show="errorMessage"
                    x-text="errorMessage"
                    class="mt-2 text-sm text-red-600"></div>

                <!-- 成功訊息 -->
                <div
                    x-show="successMessage"
                    x-text="successMessage"
                    class="mt-2 text-sm text-green-600"></div>
            </form>

            <div class="mt-6 text-center text-sm">
                已有帳號？<a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">立即登入</a>
            </div>
        </div>
    </div>
</div>
@endsection