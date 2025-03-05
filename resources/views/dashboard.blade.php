@extends('layouts.app')

@section('title', '控制面板')
<script src="{{ asset('js/logout.js') }}"></script>

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-4">歡迎來到控制面板</h1>
        <p>您已成功登入系統。</p>

        <div class="mt-6">
            <button
                id="logout-button"
                class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded"
                onclick="logout()">
                登出
            </button>
        </div>
    </div>
</div>

@endsection