@extends('layouts.app')

@section('content')

@push('scripts')
<script src="{{ asset('js/admin_review.js') }}"></script>
@endpush

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="lessonPlansTable()">
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
        <h2 class="text-2xl font-bold text-gray-800 mb-4">教案列表</h2>
        <table class="min-w-full bg-white" x-show="!withCredentials">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200 text-left">ID</th>
                    <th class="py-2 px-4 border-b border-gray-200 text-left">標題</th>
                    <th class="py-2 px-4 border-b border-gray-200 text-left">描述</th>
                    <th class="py-2 px-4 border-b border-gray-200 text-left">適用年級</th>
                    <th class="py-2 px-4 border-b border-gray-200 text-left">教學目標</th>
                    <th class="py-2 px-4 border-b border-gray-200 text-left">活動</th>
                    <th class="py-2 px-4 border-b border-gray-200 text-left">檔案</th>
                    <th class="py-2 px-4 border-b border-gray-200 text-left">操作</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="lessonPlan in lessonPlans" :key="lessonPlan.id">
                    <tr>
                        <td class="py-2 px-4 border-b border-gray-200" x-text="lessonPlan.id"></td>
                        <td class="py-2 px-4 border-b border-gray-200" x-text="lessonPlan.title"></td>
                        <td class="py-2 px-4 border-b border-gray-200" x-text="lessonPlan.description"></td>
                        <td class="py-2 px-4 border-b border-gray-200">
                            <template x-for="grade in lessonPlan.grades" :key="grade">
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded" x-text="grade.grade_level"></span>
                            </template>
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200" x-text="lessonPlan.teaching_goals"></td>
                        <td class="py-2 px-4 border-b border-gray-200" x-text="lessonPlan.activities"></td>
                        <td class="py-2 px-4 border-b border-gray-200">
                            <a :href="`/storage/${lessonPlan.file_path}`" class="text-blue-500" target="_blank">下載</a>
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200">
                            <template x-if="lessonPlan.is_approved">
                                <span >已發布</span>
                            </template>
                            <template x-if="!lessonPlan.is_approved">
                                <button @click="push(lessonPlan.id)" class="bg-green-600 hover:bg-green-700 text-white font-medium py-1 px-3 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                                    發布
                                </button>
                            </template>
                        </td>
                        <td>
                            <template x-if="!lessonPlan.is_approved">
                                <button @click="deleteLessonPlan(lessonPlan.id)" class="bg-red-600 hover:bg-red-700 text-white font-medium py-1 px-3 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                                    X
                                </button>
                            </template>
                            
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
    <!-- 使用分頁組件 -->
    @include('components.pagination', [
        'paginationVar' => 'pagination',
        'changePageFunc' => 'changePage',
        'tabType' => 'admin',
        'fetchDataFunc' => 'fetchData'
    ])
</div>
@endsection