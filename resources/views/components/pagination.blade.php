{{-- 
  resources/views/components/pagination.blade.php 
  參數說明:
  - paginationVar: 分頁變量名稱
  - changePageFunc: 換頁函數名稱
  - tabType: 分頁所屬的標籤類型
  - fetchDataFunc: 資料獲取函數名稱 (用於改變每頁數量後重新獲取資料)
--}}

<div class="pagination" x-show="{{ $paginationVar }}.lastPage >= 1" x-cloak>
    <div class="pagination-info">
        <small 
           x-text="'顯示第'+((Number({{ $paginationVar }}.currentPage)-1)*Number({{ $paginationVar }}.perPage)+1)+
                   ' 至 '+ Math.min(Number({{ $paginationVar }}.perPage)*Number({{ $paginationVar }}.currentPage), 
                   {{ $paginationVar }}.total) +' 項結果，共 '+{{ $paginationVar }}.total+' 項'"></small>
        
        <!-- 每頁顯示數量選擇器 -->
        <div class="limit">
            <label>
                每頁顯示：
                <select class="form-control" 
                        x-model="{{ $paginationVar }}.perPage" 
                        @change="fetchData('{{ $tabType }}')">
                    <option value="10">10</option>
                    <option value="30">30</option>
                    <option value="70">70</option>
                    <option value="100">100</option>
                </select>
            </label>
        </div>
    </div>
    
    <div class="links">
        <template x-if="{{ $paginationVar }}.currentPage > 1">
            <a href="javascript:;" @click="{{ $changePageFunc }}({{ $paginationVar }}.currentPage - 1, '{{ $tabType }}')">&laquo;</a>
        </template>
        
        <template x-if="{{ $paginationVar }}.lastPage <= 10">
            <div class="flex space-x-1">
                <template x-for="page in {{ $paginationVar }}.lastPage">
                    <a href="javascript:;" 
                       @click="{{ $changePageFunc }}(page, '{{ $tabType }}')" 
                       :class="[(page == {{ $paginationVar }}.currentPage) ? 'active' : '']" 
                       x-text="page"></a>
                </template>
            </div>
        </template>
        
        <template x-if="{{ $paginationVar }}.lastPage > 10">
            <select @change="{{ $changePageFunc }}($event.target.value, '{{ $tabType }}')" 
                    style="width:66px;padding:8px 2px" 
                    x-model="{{ $paginationVar }}.currentPage">
                <template x-for="page in {{ $paginationVar }}.lastPage">
                    <option :value="page" x-text="page"></option>
                </template>
            </select>
        </template>
        
        <template x-if="{{ $paginationVar }}.currentPage <= {{ $paginationVar }}.lastPage">
            <a href="javascript:;" @click="{{ $changePageFunc }}({{ $paginationVar }}.currentPage + 1, '{{ $tabType }}')">&raquo;</a>
        </template>
    </div>
</div>

<style>
    .pagination {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .pagination-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .pagination .links {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .pagination a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        background-color: white;
        color: #4a5568;
        text-decoration: none;
        font-size: 0.875rem;
        line-height: 1.25rem;
        font-weight: 500;
    }
    
    .pagination a:hover {
        background-color: #f7fafc;
    }
    
    .pagination a.active {
        z-index: 10;
        background-color: #ebf8ff;
        border-color: #4299e1;
        color: #2b6cb0;
    }
    
    .pagination small {
        font-size: 0.875rem;
        color: #718096;
    }
    
    .pagination .limit {
        display: flex;
        align-items: center;
    }
    
    .pagination .limit label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #718096;
    }
    
    .pagination .form-control {
        padding: 0.375rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        background-color: white;
        color: #4a5568;
        font-size: 0.875rem;
        line-height: 1.25rem;
    }
</style>