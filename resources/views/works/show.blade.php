<!-- filepath: c:\Users\lkmd5\Desktop\jwt-auth-app\resources\views\works\show.blade.php -->
@extends('layouts.app')

@section('title', '作品詳情 - HelloGPT 繪本')

@section('content')
<div x-data="{
    work: {},
    user: {},
    userRating: 0, // 用戶選擇的評分
    tempRating: 0, // 滑鼠懸停時的臨時評分
    userComment: '', // 用戶的評論內容
    isSubmitting: false, // 提交狀態
    comments: [],
    dverageRating: 0,
    ratingsCount: 0,

    async fetchWork() {
        const response = await axios.get(`/api/works_show`, {
            params: {
                id: {{ $id }}
            }
        });
        this.work = response.data.work;

        this.fetchComments();
        console.log(this.work);
    },
    async downloadFile(fileId, filename) {
        try {
            const response = await axios.get(`/api/files/download`, {
                responseType: 'blob',
                params: {
                    id: fileId
                }
            });

            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            
            link.remove();
            window.URL.revokeObjectURL(url);
        } catch (error) {
            console.error('下載失敗', error);
        }
    },
    async fetchUser() {
        try {
            const response = await axios.get('/api/auth/me', {
                withCredentials: true
            });
            const data = response.data;
            console.log('獲取用戶資料:', data);
            if (data.status === 'success' && data.user) {
                this.user = data.user;
            }
        } catch (error) {
            console.log('獲取用戶資料失敗:', error);
            if (error.response && error.response.status === 401) {
                // 獲取重定向 URL
                const redirectUrl = error.response.data.redirect;
                if (redirectUrl) {
                  // 執行重定向
                  window.location.href = redirectUrl;
                }
              }
        }
    },
    async submitForReview() {
        try {
            console.log(this.work.id);
            if(!confirm('送審後不得修改')) {
                return;
            }
            const form = new FormData();
            form.append('work_id', this.work.id);
            const response = await axios.post(`/api/works/submit-for-review`,form);
            if (response.data.status === 'success') {
                alert('作品已送審');
                window.location.href = '/dashboard';
            }
        } catch (error) {
            console.error('送審失敗', error);
            if (error.response && error.response.status === 401) {
                // 獲取重定向 URL
                const redirectUrl = error.response.data.redirect;
                if (redirectUrl) {
                  // 執行重定向
                  window.location.href = redirectUrl;
                }
              }
        }
    },
    collects(){
        const form = new FormData();
        form.append('work_id', this.work.id);
        axios.post(`/api/works/collects`, form)
            .then(response => {
                if (response.data.status === 'success') {
                    alert(response.data.message);
                    // 重新整理頁面
                    location.reload();
                }
            })
            .catch(error => {
                console.error('收藏失敗', error);
                if (error.response && error.response.status === 401) {
                    // 獲取重定向 URL
                    const redirectUrl = error.response.data.redirect;
                    if (redirectUrl) {
                      // 執行重定向
                      window.location.href = redirectUrl;
                    }
                  }
                
            });
    },
    // 獲取評論
    fetchComments() {
        axios.get(`/api/works/comments`,{
            params: {
                work_id: this.work.id
            }
        })
            .then(response => {
                this.comments = response.data.data;
                this.dverageRating = response.data.dverageRating;
                this.ratingsCount = response.data.ratingsCount;
                console.log(this.comments);
            })
            .catch(error => {
                console.error('獲取評論失敗', error);
                if (error.response && error.response.status === 401) {
                // 獲取重定向 URL
                const redirectUrl = error.response.data.redirect;
                if (redirectUrl) {
                    // 執行重定向
                    window.location.href = redirectUrl;
                }
            }
            });
    },
    // 送出評分和評論
    submitRatingAndComment() {
        // 表單驗證
        if (!this.userRating || this.userComment.trim() === '') {
            this.showToast('請填寫評分和評論', 'error');
            return;
        }
        
        // 字數限制檢查
        if (this.userComment.length > 200) {
            this.showToast('評論不能超過200字', 'error');
            return;
        }
        
        // 設置提交狀態
        this.isSubmitting = true;
        
        // 發送評分和評論請求
        axios.post('/api/works/rate-and-comment', {
            work_id: this.work.id,
            rating: this.userRating,
            comment: this.userComment
        })
        .then(response => {
            // 更新評分和評論
            this.work.rating = response.data.new_rating || this.work.rating;
            this.work.like = (this.work.like || 0) + 1;
            
            // 重新獲取評論列表
            this.fetchComments();
            
            // 重置表單
            this.userRating = 0;
            this.tempRating = 0;
            this.userComment = '';
            
            // 顯示成功消息
            this.showToast('評價已提交，感謝您的反饋！', 'success');
        })
        .catch(error => {
            console.error('評價提交失敗', error);
            this.showToast('評價提交失敗，請稍後再試', 'error');
        })
        .finally(() => {
            this.isSubmitting = false;
        });
    },
    // 日期格式化
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('zh-TW', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    },
    checkPending(){
        return this.work.progress == 'pending';
    },
    checkMy(){
        return this.work.user_id == this.user.id;
    },
    // 顯示通知
    showToast(message, type = 'info') {
        // 實現通知功能...
    }
}" x-init="fetchWork(); fetchUser()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-4" x-text="work.title"></h1>
                    
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-700 mb-2">作品描述</h2>
                        <p class="text-gray-600" x-text="work.description"></p>
                    </div>

                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-700 mb-2">創作資訊</h2>
                        <ul class="space-y-2 text-gray-600">
                            <li>
                                <span class="font-medium">作者：</span>
                                <span x-text="work.user?.name"></span>
                            </li>
                            <li>
                                <span class="font-medium">創作日期：</span>
                                <span x-text="work.created_at"></span>
                            </li>
                            <li>
                                <span class="font-medium">適用年齡：</span>
                                <span x-text="work.age_group"></span>
                            </li>
                        </ul>
                    </div>

                    <div class="flex space-x-4">
                        <button x-show="work.files.length > 0" @click="downloadFile(work?.files[0]?.id, work?.files[0].original_name)" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                            下載 PDF
                        </button>
                        <template x-if="work?.collects?.length == 0">
                            <button @click="collects()" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                                收藏作品
                            </button>
                        </template>
                        <template x-if="work?.collects?.length > 0">
                            <button @click="collects()" class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                                刪除蒐藏
                            </button>
                        </template>
                        <template x-if="checkMy() && checkPending()">
                            <div class="flex space-x-4">
                                <a :href="`/works/${work.id}/edit`" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                                    修改作品
                                </a>
                                <button @click="submitForReview()" class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                                    送審
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <div>
                    <img 
                    :src="'/storage/' + work.image_path" 
                    alt="作品圖片" 
                    class="w-full h-full rounded-t-lg object-cover"
                    >                   
                </div>
            </div>
        </div>
        <template x-if=" !checkPending()">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">作品評價</h2>
                <!-- 現有評分顯示 -->
                <div class="flex items-center mb-4">
                    <div class="text-yellow-500 mr-2">
                        <!-- 動態星級評分 -->
                        <template x-for="i in 5">
                            <span x-html="i <=dverageRating ? '★' : '☆'"></span>
                        </template>
                    </div>
                    <span class="text-gray-600" x-text="`(${ratingsCount ?? 0} 人評價)`"></span>
                </div>
                
                <!-- 顯示已有的評論 -->
                <div class="mt-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">用戶評論</h3>
                    <template x-if="comments?.length > 0">
                        <div class="space-y-4">
                            <template x-for="comment in comments" :key="comment.id">
                                <div class="border-b pb-3">
                                    <div class="flex justify-between items-center mb-2">
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-800" x-text="comment.user_name"></span>
                                            <div class="ml-2 text-yellow-500">
                                                <template x-for="i in 5" :key="i">
                                                    <span x-html="i <= comment.rating ? '★' : '☆'" class="text-sm"></span>
                                                </template>
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-500" x-text="formatDate(comment.created_at)"></span>
                                    </div>
                                    <p class="text-gray-600" x-text="comment.comment"></p>
                                </div>
                            </template>
                        </div>
                    </template>
                    <template x-if="comments?.length === 0">
                        <p class="text-gray-500 italic">尚無評論</p>
                    </template>
                </div>
                <template x-if="comments?.length === 0 && !checkMy()">
                <!-- 新增評分和留言功能 -->
                    <div class="mt-6 border-t pt-4">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">為這個作品評分並留言</h3>
                        
                        <!-- 評分區域 -->
                        <div class="flex items-center mb-3">
                            <label class="mr-2 text-gray-600">評分:</label>
                            <div class="flex text-gray-400">
                                <template x-for="star in 5" :key="star">
                                    <button 
                                        type="button"
                                        class="text-2xl hover:scale-110 transition-transform focus:outline-none"
                                        :class="star <= userRating ? 'text-yellow-500' : 'text-gray-300'"
                                        x-html="'★'"
                                        @click="userRating = star"
                                        @mouseover="tempRating = star"
                                        @mouseleave="tempRating = userRating"
                                    ></button>
                                </template>
                            </div>
                            <span class="ml-2 text-gray-600" x-text="userRating ? `${userRating} 顆星` : '未評分'"></span>
                        </div>
                        
                        <!-- 留言區域 -->
                        <div class="mb-4">
                            <label for="comment" class="block text-gray-600 mb-2">留言:</label>
                            <textarea 
                                id="comment" 
                                x-model="userComment" 
                                rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="請分享您對這個作品的看法..."
                            ></textarea>
                            <div class="text-xs text-gray-500 mt-1" x-text="`${userComment.length}/200 字`"></div>
                        </div>
                        
                        <!-- 提交按鈕 -->
                        <button 
                            @click="submitRatingAndComment()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus:ring-4 focus:outline-none focus:ring-blue-300 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="!userRating || userComment.trim() === '' || isSubmitting"
                        >
                            <span x-show="!isSubmitting">提交評價</span>
                            <span x-show="isSubmitting" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                處理中...
                            </span>
                        </button>
                    </div>
                </template>
            </div>
        </template>
    </div>
</div>
@endsection