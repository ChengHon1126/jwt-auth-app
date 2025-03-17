{{-- 作品分享 --}}
@push('scripts')
<script src="{{ asset('js/work/table.js') }}"></script>
@endpush
<div x-data="work()">
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                </svg>
                作品分享
            </h2>
            <a href="{{ route('upload') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                上傳作品
            </a>
        </div>

        {{-- 分類標籤 --}}
        <div class="flex space-x-4">
            <button @click="activeTab = 'allWorks';getAllPublishedWorks()" :class="{'bg-blue-600 text-white': activeTab === 'allWorks', 'bg-gray-200 text-gray-800': activeTab !== 'allWorks'}" class="px-4 py-2 rounded-lg font-semibold transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                全部作品
            </button>
            <button @click="activeTab = 'myWorks';getMyWorks()" :class="{'bg-blue-600 text-white': activeTab === 'myWorks', 'bg-gray-200 text-gray-800': activeTab !== 'myWorks'}" class="px-4 py-2 rounded-lg font-semibold transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                我的作品
            </button>
            <template x-if="$store.user.role == 1">
                <button @click="activeTab = 'penddingWorks'; getPenndingWorks()" :class="{'bg-blue-600 text-white': activeTab === 'penddingWorks', 'bg-gray-200 text-gray-800': activeTab !== 'penddingWorks'}" class="px-4 py-2 rounded-lg font-semibold transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                    其他用戶的作品
                </button>
            </template>
        </div>

        {{-- 加載動畫 --}}
        <div x-show="isLoading" class="flex justify-center items-center">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </div>

        {{-- 全部作品 --}}
        <div x-show="!isLoading && activeTab === 'allWorks'">
            
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">全部作品</h3>
                <div class="flex items-center">
                    <label for="sortOptions" class="mr-2 text-gray-600 text-sm">排序方式:</label>
                    <select 
                        id="sortOptions" 
                        x-model="sortBy" 
                        @change="getAllPublishedWorks()"
                        class="bg-white border border-gray-300 text-gray-700 py-1 px-3 pr-8 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                    >
                        <option value="latest">最新上傳</option>
                        <option value="mostCollected">最多收藏</option>
                        <option value="teacherRecommended">教師推薦</option>
                    </select>
                </div>
            </div>
            <div x-show="isLoadingSort" class="flex justify-center my-8">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
            </div>
            <div x-show="!isLoadingSort" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="work in works" :key="work.id">
                    <div class="bg-white rounded-lg border border-gray-200 shadow-md hover:shadow-lg transition-shadow duration-300">
                        <img 
                        :src="'/storage/' + work.image_path" 
                        alt="作品圖片" 
                        class="w-full h-48 rounded-t-lg object-cover"
                        >                       
                        <div class="p-5 relative">
                            <h3 class="text-xl font-bold mb-2 text-gray-800" x-text="work.title"></h3>
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span x-text="new Date(work.created_at).toLocaleDateString()"></span>
                            </div>
                            <p class="text-gray-600 mb-4 line-clamp-2" x-text="work.description"></p>
                            <div class="flex justify-between items-center">
                                <a :href="`/works/${work.id}`" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                    查看詳情
                                    <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                    </svg>
                                </a>
                                
                                <!-- 收藏圖標 - 右下角 -->
                                <svg 
                                    x-show="work.collects?.length > 0"
                                    xmlns="http://www.w3.org/2000/svg" 
                                    class="h-6 w-6 text-yellow-500 fill-current" 
                                    viewBox="0 0 20 20" 
                                    fill="currentColor"
                                >
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                
                                <!-- 教師推薦標誌 -->
                                <div x-show="work.is_recommended" class="absolute top-0 right-0 mt-2 mr-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                    教師推薦
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            @include('components.pagination', [
                'paginationVar' => 'pagination',
                'changePageFunc' => 'changePage',
                'tabType' => 'allWorks',
            ])
        </div>
        </div>
        {{-- 自己上傳的作品 --}}
        {{-- 自己上傳的作品和收藏 --}}
        <div x-show="!isLoading && activeTab === 'myWorks'">
            <!-- 子分類切換 -->
            <div class="flex space-x-4 mb-4 border-b">
                <button @click="myWorksSubTab = 'uploaded'" 
                        class="py-2 px-4 focus:outline-none transition-colors duration-200"
                        :class="myWorksSubTab === 'uploaded' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-gray-700'">
                    我的作品
                </button>
                <button @click="myWorksSubTab = 'collected'; getMyCollects()" 
                        class="py-2 px-4 focus:outline-none transition-colors duration-200"
                        :class="myWorksSubTab === 'collected' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-gray-700'">
                    我的收藏
                </button>
            </div>
            
            <!-- 我上傳的作品 -->
            <div x-show="myWorksSubTab === 'uploaded'">
                <h3 class="text-xl font-bold text-gray-800 mb-4">我的作品</h3>
                <div x-show="myWorks.length === 0" class="bg-gray-50 p-8 text-center rounded-lg">
                    <p class="text-gray-500">您尚未上傳任何作品</p>
                </div>
                <div x-show="myWorks.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="work in myWorks" :key="work.id">
                        <div class="bg-white rounded-lg border border-gray-200 shadow-md hover:shadow-lg transition-shadow duration-300">
                            <img 
                            :src="'/storage/' + work.image_path" 
                            alt="作品圖片" 
                            class="w-full h-48 rounded-t-lg object-cover"
                            >
                            <div class="p-5">
                                <h3 class="text-xl font-bold mb-2 text-gray-800" x-text="work.title"></h3>
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span x-text="new Date(work.created_at).toLocaleDateString()"></span>
                                </div>
                                <p class="text-gray-600 mb-4 line-clamp-2" x-text="work.description"></p>
                                <a :href="`/works/${work.id}`" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                    查看詳情
                                    <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
                @include('components.pagination', [
                'paginationVar' => 'pagination',
                'changePageFunc' => 'changePage',
                'tabType' => 'allWorks',
                ])
            </div>
            <!-- 我的收藏 -->
            <div x-show="myWorksSubTab === 'collected'">
                <h3 class="text-xl font-bold text-gray-800 mb-4">我的收藏</h3>
                <div x-show="collects.length === 0" class="bg-gray-50 p-8 text-center rounded-lg">
                    <p class="text-gray-500">您尚未收藏任何作品</p>
                </div>
                <div x-show="collects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="collect in collects" :key="collect.id">
                        <div class="bg-white rounded-lg border border-gray-200 shadow-md hover:shadow-lg transition-shadow duration-300">
                            <img 
                            :src="'/storage/' + collect.work.image_path" 
                            alt="作品圖片" 
                            class="w-full h-48 rounded-t-lg object-cover"
                            >
                            <div class="p-5">
                                <h3 class="text-xl font-bold mb-2 text-gray-800" x-text="collect.work.title"></h3>
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span x-text="new Date(collect.work.created_at).toLocaleDateString()"></span>
                                </div>
                                <p class="text-gray-600 mb-4 line-clamp-2" x-text="collect.work.description"></p>
                                <div class="flex justify-between items-center">
                                    <a :href="`/works/${collect.work_id}`" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                        查看詳情
                                        <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                        </svg>
                                    </a>
                                    <button 
                                        @click="cancelCollect(collect.work_id)" 
                                        class="text-yellow-500 hover:text-yellow-600 transition-colors"
                                        title="取消收藏"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
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
                    'tabType' => 'allWorks',
                ])
            </div>
        </div>
        {{-- 其他用戶上傳的作品 --}}
        <div x-show="!isLoading && activeTab === 'penddingWorks'">
            <h3 class="text-xl font-bold text-gray-800 mb-4">簽核中作品</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="work in penddingWorks" :key="work.id">
                    <div class="bg-white rounded-lg border border-gray-200 shadow-md hover:shadow-lg transition-shadow duration-300">
                        <img 
                        :src="'/storage/' + work.image_path" 
                        alt="作品圖片" 
                        class="w-full h-48 rounded-t-lg object-cover"
                        >                         <div class="p-5">
                            <h3 class="text-xl font-bold mb-2 text-gray-800" x-text="work.title"></h3>
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span x-text="new Date(work.created_at).toLocaleDateString()"></span>
                            </div>
                            <p class="text-gray-600 mb-4 line-clamp-2" x-text="work.description"></p>
                            <a :href="`/works/${work.id}`" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                查看詳情
                                <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </template>
            </div>
            @include('components.pagination', [
                'paginationVar' => 'pagination',
                'changePageFunc' => 'changePage',
                'tabType' => 'penddingWorks',
            ])
        </div>
    </div>
</div>

