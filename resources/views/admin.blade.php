<!-- filepath: c:\Users\lkmd5\Desktop\jwt-auth-app\resources\views\admin.blade.php -->
@extends('layouts.app')

@section('title', '後臺管理')

{{-- @push('scripts')
<script src="{{ asset('js/admin.js') }}"></script>
@endpush --}}

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-4">後臺管理</h1>
                <p class="text-gray-600">管理用戶和系統設置。</p>
            </div>
            <button class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg"
                    onclick="window.location.href='/dashboard'">
                返回控制面板
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- 作品審核卡片 -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">作品審核</h2>
            <p class="text-gray-600 mb-4">管理員審核用戶上傳的 AI 繪本，確保符合規範後上架。</p>
            <a class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg"
                    href="{{ route('admin.review-works') }}">
                查看作品
            </a>
        </div>

        <!-- 教案審核卡片 -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">教案審核</h2>
            <p class="text-gray-600 mb-4">管理員審核用戶上傳的教案，確保符合規範後上架。</p>
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg"
                    onclick="window.location.href='/admin/review-lessons'">
                查看教案
            </button>
            <a href="{{ route('lesson-plans.create') }}" class="block">
                <button class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                    </svg>
                    <span>上傳教案</span>
                </button>
            </a>
        </div>

        <!-- 活動管理卡片 -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">活動管理</h2>
            <p class="text-gray-600 mb-4">新增、編輯和公布活動，支援圖片與文字上傳，管理活動規則。</p>
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg"
                    onclick="window.location.href='/admin/manage-events'">
                管理活動
            </button>
        </div>
    </div>
</div>
@endsection