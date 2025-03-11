{{-- 繪本競賽 --}}
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd" />
            </svg>
            繪本競賽
        </h2>
        <a href="{{ route('competition.submit') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            參加競賽
        </a>
    </div>
    
    <!-- 活動競賽卡片 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg border border-gray-200 shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="relative">
                <img src="https://via.placeholder.com/300x200" alt="競賽圖片" class="w-full h-48 rounded-t-lg object-cover">
                <div class="absolute top-0 right-0 bg-yellow-500 text-white px-3 py-1 m-2 rounded-full text-xs font-bold">
                    進行中
                </div>
            </div>
            <div class="p-5">
                <h3 class="text-xl font-bold mb-2 text-gray-800">2023年度繪本創作大賽</h3>
                <div class="flex items-center text-sm text-gray-500 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    截止日期：2023-12-31
                </div>
                <p class="text-gray-600 mb-4 line-clamp-2">參加我們的繪本創作競賽，展示你的創意並贏取獎品！本次競賽主題為「環保地球」，歡迎各年齡層參加。</p>
                <a href="#" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                    查看詳情
                    <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>