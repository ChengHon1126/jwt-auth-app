@extends('layouts.app')

@section('title', '上傳教案')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto" x-data="lessonPlanForm()">
        <div class="relative">
            <div class="absolute -inset-0.5 bg-blue-400 rounded-3xl opacity-75 blur-lg"></div>
            <div class="relative bg-white shadow-2xl rounded-3xl overflow-hidden">
                <!-- Elegant Gradient Header -->
                <div class="relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 opacity-90"></div>
                    <div class="relative px-6 py-8 flex items-center justify-between z-10">
                        <h1 class="text-4xl font-black text-white tracking-tight flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mr-4 transform -rotate-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            上傳教案
                        </h1>
                        <div class="text-white/80 text-sm font-medium bg-white/10 px-4 py-2 rounded-full">
                            教案上傳精靈
                        </div>
                    </div>
                </div>

                <!-- Form Container -->
                <form @submit.prevent="submitForm" class="p-10 space-y-8">
                    <!-- Form Status Message -->
                    <div x-show="message.text" 
                         x-transition 
                         :class="{
                             'bg-green-50 border-green-400 text-green-700': message.type === 'success', 
                             'bg-red-50 border-red-400 text-red-700': message.type === 'error'
                         }" 
                         class="p-5 rounded-2xl border-l-4 shadow-md relative" 
                         role="alert">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <svg x-show="message.type === 'success'" class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <svg x-show="message.type === 'error'" class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p x-text="message.text" class="text-base font-semibold"></p>
                        </div>
                    </div>

                    <!-- Form Fields Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- 教案標題 -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-3">
                                教案標題
                            </label>
                            <div class="relative group">
                                <input type="text" id="title" x-model="formData.title" 
                                       class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-300 rounded-xl 
                                              focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 
                                              transition duration-300 
                                              group-hover:border-gray-400" 
                                       placeholder="輸入教案的詳細標題" 
                                       required>
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-6 w-6 text-gray-400 group-hover:text-gray-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                            </div>
                            <p x-show="errors.title" x-text="errors.title" class="mt-2 text-sm text-red-600 pl-2"></p>
                        </div>

                        <!-- 教案描述 -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-3">
                                教案描述
                            </label>
                            <textarea id="description" x-model="formData.description" rows="4" 
                                      class="w-full px-4 py-3.5 border-2 border-gray-300 rounded-xl 
                                             focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 
                                             transition duration-300 
                                             hover:border-gray-400"
                                      placeholder="簡要說明教案的核心概念與教學意圖"></textarea>
                            <p x-show="errors.description" x-text="errors.description" class="mt-2 text-sm text-red-600 pl-2"></p>
                        </div>

                        <!-- 適用年級 -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                適用年級
                            </label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="cursor-pointer group">
                                    <input type="checkbox" value="elementary" x-model="formData.grade_levels" 
                                           class="sr-only peer" />
                                    <div class="w-full p-3 text-center rounded-xl 
                                                bg-gray-100 text-gray-600
                                                peer-checked:bg-blue-500 peer-checked:text-white
                                                group-hover:bg-gray-200
                                                transition duration-300">
                                        國小
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="checkbox" value="junior_high" x-model="formData.grade_levels" 
                                           class="sr-only peer" />
                                    <div class="w-full p-3 text-center rounded-xl 
                                                bg-gray-100 text-gray-600
                                                peer-checked:bg-blue-500 peer-checked:text-white
                                                group-hover:bg-gray-200
                                                transition duration-300">
                                        國中
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="checkbox" value="senior_high" x-model="formData.grade_levels" 
                                           class="sr-only peer" />
                                    <div class="w-full p-3 text-center rounded-xl 
                                                bg-gray-100 text-gray-600
                                                peer-checked:bg-blue-500 peer-checked:text-white
                                                group-hover:bg-gray-200
                                                transition duration-300">
                                        高中
                                    </div>
                                </label>
                            </div>
                            <p x-show="errors.grade_levels" x-text="errors.grade_levels" class="mt-2 text-sm text-red-600 pl-2"></p>
                        </div>

                        <!-- 教學目標 -->
                        <div>
                            <label for="teaching_goals" class="block text-sm font-semibold text-gray-700 mb-3">
                                教學目標
                            </label>
                            <textarea id="teaching_goals" x-model="formData.teaching_goals" rows="4" 
                                      class="w-full px-4 py-3.5 border-2 border-gray-300 rounded-xl 
                                             focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 
                                             transition duration-300 
                                             hover:border-gray-400"
                                      placeholder="清晰列出教學預期達成的具體目標"></textarea>
                            <p x-show="errors.teaching_goals" x-text="errors.teaching_goals" class="mt-2 text-sm text-red-600 pl-2"></p>
                        </div>

                        <!-- 課堂活動建議 -->
                        <div>
                            <label for="activities" class="block text-sm font-semibold text-gray-700 mb-3">
                                課堂活動建議
                            </label>
                            <textarea id="activities" x-model="formData.activities" rows="4" 
                                      class="w-full px-4 py-3.5 border-2 border-gray-300 rounded-xl 
                                             focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 
                                             transition duration-300 
                                             hover:border-gray-400"
                                      placeholder="設計具體且有趣的課堂互動活動"></textarea>
                            <p x-show="errors.activities" x-text="errors.activities" class="mt-2 text-sm text-red-600 pl-2"></p>
                        </div>

                        <!-- 教案檔案上傳 -->
                        <div class="md:col-span-2">
                            <label for="file" class="block text-sm font-semibold text-gray-700 mb-3">
                                上傳教案檔案
                            </label>
                            <div class="relative group">
                                <input type="file" id="file" @change="handleFileUpload" accept=".pdf" 
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                                <div class="w-full p-6 border-2 border-dashed border-gray-300 rounded-xl 
                                            text-center hover:border-blue-500 hover:bg-blue-50 
                                            group-hover:border-blue-500 
                                            transition duration-300">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <svg class="h-12 w-12 text-gray-400 group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-gray-600 group-hover:text-blue-600 transition">
                                            點擊上傳 PDF 檔案（最大 10MB）
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div x-show="fileProgress > 0 && fileProgress < 100" class="w-full bg-gray-200 rounded-full h-1.5 mt-3">
                                <div class="bg-blue-600 h-1.5 rounded-full" :style="`width: ${fileProgress}%`"></div>
                            </div>
                            <p x-show="errors.file" x-text="errors.file" class="mt-2 text-sm text-red-600 pl-2"></p>
                            <div x-show="formData.file" class="mt-3 p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-center space-x-4">
                                <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p x-text="formData.file.name" class="text-sm text-blue-700"></p>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl shadow-md hover:bg-blue-700 transition duration-300">
                            提交教案
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function lessonPlanForm() {
        return {
            formData: {
                title: '',
                description: '',
                grade_levels: [],
                teaching_goals: '',
                activities: '',
                file: null
            },
            errors: {},
            isSubmitting: false,
            fileProgress: 0,
            message: {
                text: '',
                type: ''
            },
            
            // 處理檔案上傳
            handleFileUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    // 檢查檔案類型
                    if (file.type !== 'application/pdf') {
                        this.errors.file = '只允許上傳 PDF 檔案';
                        return;
                    }
                    
                    // 檢查檔案大小 (10MB)
                    if (file.size > 10 * 1024 * 1024) {
                        this.errors.file = '檔案大小不能超過 10MB';
                        return;
                    }
                    
                    this.formData.file = file;
                    this.errors.file = null;
                }
            },
            
            // 提交表單
            async submitForm() {
                this.isSubmitting = true;
                this.errors = {};
                this.message = { text: '', type: '' };
                
                try {
                    // 表單驗證
                    if (!this.formData.title) {
                        this.errors.title = '請輸入教案標題';
                    }
                    
                    if (this.formData.grade_levels.length === 0) {
                        this.errors.grade_levels = '請至少選擇一個適用年級';
                    }
                    
                    if (!this.formData.file) {
                        this.errors.file = '請上傳教案檔案';
                    }
                    
                    // 如果有錯誤則停止提交
                    if (Object.keys(this.errors).length > 0) {
                        this.isSubmitting = false;
                        return;
                    }
                    
                    // 建立 FormData 對象來處理檔案上傳
                    const formData = new FormData();
                    formData.append('title', this.formData.title);
                    formData.append('description', this.formData.description || '');
                    this.formData.grade_levels.forEach(grade => {
                        formData.append('grade_levels[]', grade);
                    });
                    formData.append('teaching_goals', this.formData.teaching_goals || '');
                    formData.append('activities', this.formData.activities || '');
                    formData.append('file', this.formData.file);
                    
                    // 發送 API 請求
                    const response = await axios.post('/api/lesson-plans', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                        onUploadProgress: (progressEvent) => {
                            this.fileProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        }
                    });
                    
                    // 處理成功回應
                    this.message = {
                        text: '教案上傳成功！',
                        type: 'success'
                    };
                    
                    alert('教案上傳成功！');
                    window.location.href = '/admin';
                    
                } catch (error) {
                    alert('上傳失敗，請稍後再試。');
                    console.error('上傳失敗:', error);
                    
                    // 處理驗證錯誤
                    if (error.response && error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors;
                    } else {
                        this.message = {
                            text: '上傳失敗，請稍後再試。',
                            type: 'error'
                        };
                    }
                } finally {
                    this.isSubmitting = false;
                }
            }
        };
    }
</script>
@endsection