<!-- filepath: c:\Users\lkmd5\Desktop\jwt-auth-app\resources\views\dashboard.blade.php -->
@extends('layouts.app')

@section('title', '控制面板')

@push('scripts')
<script src="{{ asset('js/dashboard.js') }}"></script>
<script src="{{ asset('js/logout.js') }}"></script>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="dashboard()">
    <!-- 頭部區域 -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">歡迎來到控制面板</h1>
                <p class="mt-2 text-gray-600">您已成功登入系統，可以開始管理您的內容。</p>
            </div>
            <button x-show="$store.user.role === 1"
                    id="admin-button"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg flex items-center mr-4"
                    onclick="window.location.href='/admin'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 2a2 2 0 00-2 2v1H6a2 2 0 00-2 2v1H3a2 2 0 00-2 2v6a2 2 0 002 2h14a2 2 0 002-2v-6a2 2 0 00-2-2h-1V7a2 2 0 00-2-2h-2V4a2 2 0 00-2-2zm0 2h2v1h-2V4zm-4 3h8v1H6V7zm-3 3h14v6H3v-6z" />
                    </svg>
                    後臺管理
                </button>
            <button
                id="logout-button"
                class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg flex items-center"
                onclick="logout()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                </svg>
                登出系統
            </button>
        </div>
    </div>

    <!-- 選項卡 -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden" x-data="{ activeTab: 'works' }">
        <!-- 選項卡導航 -->
        <div class="flex border-b border-gray-200 bg-gray-50">
            <button @click="activeTab = 'works'; $dispatch('select-tab', {tab: 'works'})" :class="{'border-b-2 border-blue-500 text-blue-600 font-medium': activeTab === 'works'}" class="px-6 py-4 text-gray-600 hover:text-gray-800 focus:outline-none transition duration-150 ease-in-out">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                    </svg>
                    作品分享
                </span>
            </button>
            <button @click="activeTab = 'lessons'; $dispatch('select-tab', {tab: 'lessons'})" :class="{'border-b-2 border-blue-500 text-blue-600 font-medium': activeTab === 'lessons'}" class="px-6 py-4 text-gray-600 hover:text-gray-800 focus:outline-none transition duration-150 ease-in-out">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                    </svg>
                    教案分享
                </span>
            </button>
            <button @click="activeTab = 'competitions'" :class="{'border-b-2 border-blue-500 text-blue-600 font-medium': activeTab === 'competitions'}" class="px-6 py-4 text-gray-600 hover:text-gray-800 focus:outline-none transition duration-150 ease-in-out">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd" />
                    </svg>
                    繪本競賽
                </span>
            </button>
        </div>

        <!-- 選項卡內容 -->
        <div class="p-6">
            <!-- 作品分享模組 -->
            <div x-show="activeTab === 'works'">
                @include('dashboard.works')
            </div>

            <!-- 教案分享模組 -->
            <div x-show="activeTab === 'lessons'" style="display: none;">
                @include('dashboard.lessons')
            </div>

            <!-- 繪本競賽模組 -->
            <div x-show="activeTab === 'competitions'" style="display: none;">
                @include('dashboard.competitions')
            </div>
        </div>
    </div>
</div>
@endsection