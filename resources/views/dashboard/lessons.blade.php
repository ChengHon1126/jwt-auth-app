{{-- 教案分享 --}}
@push('scripts')
<script src="{{ asset('js/lesson/table.js') }}"></script>
@endpush

<div x-data="lessonsTable()" x-init="init()">
    <div ">
        <div class="space-y-6" >
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                    </svg>
                    教案分享
                </h2>
                <a href="{{ route('lesson-plans.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    上傳教案
                </a>
            </div>
            
            {{-- 分類標籤 --}}
            <div class="flex space-x-4">
                <button @click="activeTab = 'allLessons'; getAllLessons()" 
                        :class="{'bg-blue-600 text-white': activeTab === 'allLessons', 'bg-gray-200 text-gray-800': activeTab !== 'allLessons'}" 
                        class="px-4 py-2 rounded-lg font-semibold transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                    全部教案
                </button>
                <button @click="activeTab = 'myLessons'; getMyLessons()" 
                        :class="{'bg-blue-600 text-white': activeTab === 'myLessons', 'bg-gray-200 text-gray-800': activeTab !== 'myLessons'}" 
                        class="px-4 py-2 rounded-lg font-semibold transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                    我的教案
                </button>
            </div>
            
            {{-- 加載動畫 --}}
            <div x-show="isLoading" class="flex justify-center items-center">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </div>
            
            {{-- 全部教案 --}}
            <div x-show="!isLoading && activeTab === 'allLessons'">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">全部教案</h3>
                    <div class="flex items-center">
                        <label for="sortOptions" class="mr-2 text-gray-600 text-sm">排序方式:</label>
                        <select 
                            id="sortOptions" 
                            x-model="sortBy" 
                            @change="getAllLessons()"
                            class="bg-white border border-gray-300 text-gray-700 py-1 px-3 pr-8 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        >
                            <option value="latest">最新上傳</option>
                            <option value="mostDownloaded">最多下載</option>
                        </select>
                    </div>
                </div>
                <div x-show="isLoadingSort" class="flex justify-center my-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
                </div>
                <div x-show="!isLoadingSort" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="lesson in lessons" :key="lesson.id">
                        <div class="bg-white rounded-lg border border-gray-200 shadow-md hover:shadow-lg transition-shadow duration-300 relative">
                            <!-- 審核狀態標籤 -->
                            <div x-show="lesson.is_approved === 1" class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full z-10">
                                已審核
                            </div>
                            <div x-show="lesson.is_approved === 0" class="absolute top-2 right-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full z-10">
                                待審核
                            </div>
                            <div class="flex items-center justify-center w-full h-48 bg-blue-50 rounded-t-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                                </svg>
                            </div>
                            <div class="p-5">
                                <h3 class="text-xl font-bold mb-2 text-gray-800" x-text="lesson.title"></h3>
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span x-text="new Date(lesson.created_at).toLocaleDateString()"></span>
                                </div>
                                <div class="flex flex-wrap mb-3">
                                    <template x-for="grade in lesson.grades" :key="grade.id">
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-2 mb-2 px-2.5 py-0.5 rounded">
                                            <span x-text="grade.grade_level"></span>
                                        </span>
                                    </template>
                                </div>
                                <p class="text-gray-600 mb-4 line-clamp-2" x-text="lesson.description"></p>
                                <div class="flex justify-between items-center">
                                    <a :href="`/storage/${lesson.file_path}`" target="_blank" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                        查看教案
                                        <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                        </svg>
                                    </a>
                                    
                                    <div class="flex items-center space-x-4">
                                        <!-- 下載次數圖標 -->
                                        <div x-show="lesson.download_count > 0" class="flex items-center text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            <span x-text="lesson.download_count"></span>
                                        </div>
                                        
                                        <!-- 收藏按钮 -->
                                        <button 
                                            @click="toggleFavorite(lesson)" 
                                            class="relative flex itemzs-center justify-center h-8 w-8 rounded-full hover:bg-gray-100 transition-colors"
                                            :disabled="lesson.isTogglingFavorite"

                                        >
                                            <!-- 加载动画 -->
                                            <div 
                                                x-show="lesson.isTogglingFavorite" 
                                                class="absolute inset-0 flex items-center justify-center"
                                            >
                                                <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                            
                                            <!-- 收藏图标 -->
                                            <svg 
                                                x-show="!lesson.isTogglingFavorite" 
                                                xmlns="http://www.w3.org/2000/svg" 
                                                class="h-6 w-6 transition-colors" 
                                                :class="lesson.is_collected ? 'text-yellow-500' : 'text-gray-400 hover:text-gray-600'"
                                                :fill="lesson.is_collected ? 'currentColor' : 'none'" 
                                                viewBox="0 0 24 24" 
                                                stroke="currentColor"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                        </button>
                                    </div>
                            </div>
                        </div>
                    </template>
                </div>
                @include('components.pagination', [
                    'paginationVar' => 'pagination',
                    'changePageFunc' => 'changePage',
                    'tabType' => 'allLessons',
                ])
            </div>
            
            {{-- 我的教案 --}}
            <div x-show="!isLoading && activeTab === 'myLessons'">
                <h3 class="text-xl font-bold text-gray-800 mb-4">我的教案</h3>
                <div x-show="myLessons.length === 0" class="bg-gray-50 p-8 text-center rounded-lg">
                    <p class="text-gray-500">您尚未上傳任何教案</p>
                </div>
                <div x-show="myLessons.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="lesson in myLessons" :key="lesson.id">
                        <div class="bg-white rounded-lg border border-gray-200 shadow-md hover:shadow-lg transition-shadow duration-300 relative">
                            <div x-show="lesson.is_approved === 1" class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full z-10">
                                已審核
                            </div>
                            <div x-show="lesson.is_approved === 0" class="absolute top-2 right-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full z-10">
                                待審核
                            </div>
                            <div class="flex items-center justify-center w-full h-48 bg-blue-50 rounded-t-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                                </svg>
                            </div>
                            <div class="p-5">
                                <h3 class="text-xl font-bold mb-2 text-gray-800" x-text="lesson.title"></h3>
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span x-text="new Date(lesson.created_at).toLocaleDateString()"></span>
                                </div>
                                <div class="flex flex-wrap mb-3">
                                    <template x-for="grade in lesson.grades" :key="grade.id">
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-2 mb-2 px-2.5 py-0.5 rounded">
                                            <span x-text="grade.grade_level"></span>
                                        </span>
                                    </template>
                                </div>
                                <p class="text-gray-600 mb-4 line-clamp-2" x-text="lesson.description"></p>
                                <div class="flex justify-between items-center">
                                    <a :href="`/storage/${lesson.file_path}`" target="_blank" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                        查看教案
                                        <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                        </svg>
                                    </a>
                                    
                                    <!-- 下載次數圖標 - 如果有的話 -->
                                    <div x-show="lesson.download_count > 0" class="flex items-center text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        <span x-text="lesson.download_count"></span>
                                    </div>
                                </div>

                                
                            </div>
                        </div>
                    </template>
                </div>
                @include('components.pagination', [
                    'paginationVar' => 'pagination',
                    'changePageFunc' => 'changePage',
                    'tabType' => 'myLessons',
                ])
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const lessonsTable = () => {
    return {
        activeTab: 'allLessons',
        pagination: {
            currentPage: 1,
            lastPage: 1,
            total: 0,
            perPage: 9
        },
        lessons: [],
        myLessons: [],
        isLoading: false,
        isLoadingSort: false,
        sortBy: 'latest',
        
        async init() {
            // 初始加載教案數據
            this.getAllLessons();
        },
        
        async getAllLessons() {
            this.isLoading = true;
            this.isLoadingSort = true;
            try {
                const res = await axios.get('/api/lesson-plans/public', {
                    params: {
                        page: this.pagination.currentPage,
                        per_page: this.pagination.perPage,
                        sort: this.sortBy
                    }
                });
                
                const data = res.data.data;
                this.lessons = data.data.map(lesson => ({
                        ...lesson,
                        isTogglingFavorite: false
                    }));
                // this.lessons = data.data;
                this.pagination.currentPage = data.current_page;
                this.pagination.lastPage = data.last_page;
                this.pagination.total = data.total;
            } catch (error) {
                console.error('獲取教案數據失敗:', error);
            } finally {
                this.isLoading = false;
                this.isLoadingSort = false;
            }
        },
        
        async getMyLessons() {
            this.isLoading = true;
            try {
                const res = await axios.get('/api/lesson-plans/my', {
                    params: {
                        page: this.pagination.currentPage,
                        per_page: this.pagination.perPage
                    }
                });
                
                const data = res.data.data;
                this.myLessons = data.data;
                this.pagination.currentPage = data.current_page;
                this.pagination.lastPage = data.last_page;
                this.pagination.total = data.total;
            } catch (error) {
                console.error('獲取我的教案數據失敗:', error);
            } finally {
                this.isLoading = false;
            }
        },
        
        changePage(page, tabType) {
            if (page < 1 || page > this.pagination.lastPage) return;
            this.pagination.currentPage = page;
            
            if (tabType === 'allLessons') {
                this.getAllLessons();
            } else if (tabType === 'myLessons') {
                this.getMyLessons();
            }
        },
        async toggleFavorite(lesson){
            let id = lesson.id;
            this.lesson.isTogglingFavorite = true;
            const form = new FormData();
            form.append('collectable_id', id);
            form.append('collectable_type', 'lessons');
            const res = await axios.post(`/api/lesson-plans/collect`,form);
            if(res.data.status === 'success'){
                alert(res.data.message);
                lesson.is_collected = !lesson.is_collected;
                this.lesson.isTogglingFavorite = false;
            }

        
        }
    }
}
</script>
@endpush