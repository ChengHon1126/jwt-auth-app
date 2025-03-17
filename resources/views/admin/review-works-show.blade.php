<!-- filepath: c:\Users\lkmd5\Desktop\jwt-auth-app\resources\views\admin\review-works-show.blade.php -->
@extends('layouts.app')

@section('title', '作品審核 - HelloGPT 繪本')

@section('content')
<div x-data="reviewWork({{ $id }})" x-init="fetchWork">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4" x-text="work.title"></h1>
            <p class="text-gray-600 mb-4" x-text="work.description"></p>
            <img :src="`/storage/${work.image_path}`" alt="作品圖片" class="w-full h-auto rounded-lg mb-4">
            <a 
                :href="`/pdf/${work.files[0].file_path}`" 
                target="_blank" 
                rel="noopener noreferrer"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg"
            >
                預覽 PDF
            </a>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">審核操作</h2>
            <button @click="approveWork" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                通過審核
            </button>
            <button @click="rejectWork" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg mt-4">
                拒絕審核
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function reviewWork(workId) {
        return {
            work: {},
            async fetchWork() {
                try {
                    const response = await axios.get(`/api/works_show`, {
                        params: {
                            id: workId
                        }
                    });
                    this.work = response.data.work;
                } catch (error) {
                    console.error('Error fetching work:', error);
                }
            },
            async approveWork() {
                try {
                    const form = new FormData();
                    form.append('work_id', workId);
                    const response = await axios.post(`/api/works/approve`, form);
                    if (response.data.status === 'success') {
                        alert('作品已通過審核');
                        window.location.href = '/admin/review-works';
                    }
                } catch (error) {
                    console.error('Error approving work:', error);
                }
            },
            async rejectWork() {
                try {
                    const response = await axios.post(`/api/works/${workId}/reject`);
                    if (response.data.status === 'success') {
                        alert('作品已拒絕審核');
                        window.location.href = '/admin/review-works';
                    }
                } catch (error) {
                    console.error('Error rejecting work:', error);
                }
            }
        }
    }
</script>
@endpush