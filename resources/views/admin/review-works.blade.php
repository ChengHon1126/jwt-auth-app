<!-- filepath: c:\Users\lkmd5\Desktop\jwt-auth-app\resources\views\admin\review-works.blade.php -->
@extends('layouts.app')

@section('title', '作品審核')
@push('scripts')
<script src="{{ asset('js/admin_review.js') }}"></script>
@endpush
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="review()">
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-4">作品審核</h1>
                <p class="text-gray-600">管理員審核用戶上傳的 AI 繪本，確保符合規範後上架。</p>
            </div>
            <a class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg"
                    :href="`/admin`">
                返回上一頁
            </a>
        </div>
    </div>

    <div x-show="withCredentials" class="flex justify-center items-center">
        <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6" >
        <h2 class="text-2xl font-bold text-gray-800 mb-4">作品列表</h2>
        <table class="min-w-full bg-white" x-show="!withCredentials">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200 text-left">ID</th>
                    <th class="py-2 px-4 border-b border-gray-200 text-left">名稱</th>
                    <th class="py-2 px-4 border-b border-gray-200 text-left">描述</th>
                    <th class="py-2 px-4 border-b border-gray-200 text-left">建立人</th>
                    <th class="py-2 px-4 border-b border-gray-200 text-left">上傳時間</th>
                    <th class="py-2 px-4 border-b border-gray-200 text-left">操作</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(data, idx) in datas" :key="idx">
                    <tr>
                        <td class="py-2 px-4 border-b border-gray-200" x-text="data.id"></td>
                        <td class="py-2 px-4 border-b border-gray-200" x-text="data.title"></td>
                        <td class="py-2 px-4 border-b border-gray-200">
                            <span x-text="data.description" class="line-clamp-1"></span>
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200" x-text="data.user.name"></td>
                        <td class="py-2 px-4 border-b border-gray-200" x-text="data.created_at"></td>
                        <td class="py-2 px-4 border-b border-gray-200">
                            <a :href="`/admin/review-works/${data.id}`" class="bg-green-600 hover:bg-green-700 text-white font-medium py-1 px-3 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                                審核
                            </a>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <!-- 使用分頁組件 -->
        @include('components.pagination', [
            'paginationVar' => 'pagination',
            'changePageFunc' => 'changePage',
            'tabType' => 'admin',
            'fetchDataFunc' => 'fetchData'
        ])
    </div>
</div>
@endsection