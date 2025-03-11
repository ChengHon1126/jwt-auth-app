@extends('layouts.app')

@section('title', '上傳作品')
@push('scripts')
<script src="{{ asset('js/work.js') }}"></script>
@endpush
@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-100">
        <h1 class="text-3xl font-bold mb-6 text-gray-800 border-b pb-4">上傳作品</h1>
        
        <div x-data="work()" class="space-y-6"
        >
           
            
            <!-- 進度條 -->
            <div x-show="loading" class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-300" :style="`width: ${progress}%`"></div>
            </div>
            
            <!-- 表單內容 -->
            <div class="form-group">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">作品標題 <span class="text-red-500">*</span></label>
                <input type="text" id="title" x-model="title" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                    placeholder="請輸入作品標題">
            </div>
            
            <div class="form-group">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">作品描述</label>
                <textarea id="description" x-model="description" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                    placeholder="請描述您的作品內容或創作理念"></textarea>
            </div>
            
            <div class="form-group">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">作品圖片</label>
                <div class="mt-1 flex items-center">
                    <div class="w-full">
                        <label for="image" class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:bg-gray-50 transition-colors duration-200" 
                               :class="{'border-indigo-300 bg-indigo-50': dragoverImage}" 
                               @dragover.prevent="dragoverImage = true" 
                               @dragleave.prevent="dragoverImage = false" 
                               @drop.prevent="handleImageDrop($event)">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="text-sm text-gray-600">
                                    <span class="font-medium text-indigo-600 hover:text-indigo-500">
                                        上傳圖片
                                    </span>
                                    或拖放檔案至此
                                </div>
                                <p class="text-xs text-gray-500">
                                    PNG, JPG, GIF 最大 10MB
                                </p>
                            </div>
                        </label>
                        <input id="image" type="file" class="sr-only" 
                               accept="image/*" @change="handleImageFile($event)">
                        <!-- 顯示檔案名稱的區域 -->
                        <div x-show="imageName" class="flex items-center mt-2 ml-2 text-sm text-gray-600">
                            <svg class="h-4 w-4 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="truncate" x-text="imageName"></span>
                            <!-- 刪除按鈕 -->
                            <button type="button" @click.prevent="imageFile = null; imageName = null; document.getElementById('image').value = ''" 
                                    class="ml-2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="pdf" class="block text-sm font-medium text-gray-700 mb-1">作品 PDF <span class="text-red-500">*</span></label>
                <div class="mt-1 flex items-center">
                    <div class="w-full">
                        <label for="pdf" class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:bg-gray-50 transition-colors duration-200"
                               :class="{'border-indigo-300 bg-indigo-50': dragoverPdf}"
                               @dragover.prevent="dragoverPdf = true"
                               @dragleave.prevent="dragoverPdf = false"
                               @drop.prevent="handlePdfDrop($event)">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <div class="text-sm text-gray-600">
                                    <span class="font-medium text-indigo-600 hover:text-indigo-500">
                                        上傳PDF檔案
                                    </span>
                                    或拖放檔案至此
                                </div>
                                <p class="text-xs text-gray-500">
                                    僅限 PDF 格式，最大 20MB
                                </p>
                            </div>
                        </label>
                        <input id="pdf" type="file" class="sr-only" 
                               accept=".pdf" @change="handlePdfFile($event)">
                        <!-- 顯示檔案名稱的區域 -->
                        <div x-show="pdfName" class="flex items-center mt-2 ml-2 text-sm text-gray-600">
                            <svg class="h-4 w-4 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="truncate" x-text="pdfName"></span>
                            <!-- 刪除按鈕 -->
                            <button type="button" @click.prevent="pdfFile = null; pdfName = null; document.getElementById('pdf').value = ''" 
                                    class="ml-2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
             <!-- 顯示訊息 -->
             <div x-show="message" x-transition 
             :class="{ 
                 'bg-green-50 border-green-400 text-green-700': messageType === 'success',
                 'bg-red-50 border-red-400 text-red-700': messageType === 'error' 
             }" 
             class="p-4 mb-4 border-l-4 rounded-r-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <template x-if="messageType === 'success'">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </template>
                    <template x-if="messageType === 'error'">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </template>
                </div>
                <div class="ml-3">
                    <p x-text="message"></p>
                </div>
            </div>
        </div>
            <div class="pt-4">
                <button type="button" @click="submitForm()" 
                        :disabled="loading"
                        :class="{'opacity-50 cursor-not-allowed': loading}"
                        class="w-full inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                    <svg x-show="!loading" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                    </svg>
                    <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="loading ? '上傳中...' : '上傳作品'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const upload = '{{ route('upload') }}';
</script>
@endpush

@endsection