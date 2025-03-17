const work = () => {
    return {
        async init() {
            await this.getAllPublishedWorks()
        },
        userId: null,
        works: [],
        myWorks: [],
        penddingWorks: [],
        activeTab: 'allWorks',
        myWorksSubTab: 'uploaded', // 子分類的默認選擇
        isLoading: false,
        sortBy: 'latest', // 默認排序方式
        isLoadingSort: false, // 排序載入狀態
        collects: [],
        pagination: {
            currentPage: 1,
            lastPage: 1,
            total: 0,
            perPage: 9
        },
        myWorksPagination: {
            currentPage: 1,
            lastPage: 1,
            total: 0,
            perPage: 9
        },
        myCollectsPagination: {
            currentPage: 1,
            lastPage: 1,
            total: 0,
            perPage: 9
        },
        myPendingPagination: {
            currentPage: 1,
            lastPage: 1,
            total: 0,
            perPage: 9
        },
        fetchData(tabType) {
            // 當每頁數量變更時重置為第一頁
            if (tabType === 'allWorks') {
                this.pagination.currentPage = 1;
                this.getAllPublishedWorks();
            } else if (tabType === 'myWorks') {
                this.myWorksPagination.currentPage = 1;
                this.getMyWorks();
            } else if (tabType === 'myCollects') {
                this.myCollectsPagination.currentPage = 1;
                this.getMyCollects();
            } else if (tabType === 'penddingWorks') {
                this.myCollectsPagination.currentPage = 1;
                this.getMyCollects();
            }
        },
        // 添加換頁方法
        changePage(page, tabType) {
            if (tabType === 'allWorks') {
                if (page < 1 || page > this.pagination.lastPage) return;
                this.pagination.currentPage = page;
                this.getAllPublishedWorks();
            } else if (tabType === 'myWorks') {
                if (page < 1 || page > this.myWorksPagination.lastPage) return;
                this.myWorksPagination.currentPage = page;
                this.getMyWorks();
            } else if (tabType === 'myCollects') {
                if (page < 1 || page > this.myCollectsPagination.lastPage) return;
                this.myCollectsPagination.currentPage = page;
                this.getMyCollects();
            }
        },
        async getAllPublishedWorks() {
            this.isLoading = true;
            const response = await axios.get('/api/works/published', {
                params: {
                    sort: this.sortBy,
                    page: this.pagination.currentPage,
                    per_page: this.pagination.perPage
                }
            });

            this.works = response.data.works.data;

            // 更新分頁信息
            this.pagination.currentPage = response.data.works.current_page;
            this.pagination.lastPage = response.data.works.last_page;
            this.pagination.total = response.data.works.total;
            this.pagination.perPage = response.data.works.per_page;

            console.log(this.pagination);
            this.isLoading = false;
        },
        async getMyWorks() {
            this.isLoading = true;
            const response = await axios.get('/api/works/my', {
                params: {
                    page: this.myWorksPagination.currentPage,
                    per_page: this.myWorksPagination.perPage
                }
            });
            // 假設後端返回的是分頁數據 works.data 包含當前頁的項目
            this.myWorks = response.data.works.data;

            // 更新分頁信息
            this.pagination.currentPage = response.data.works.current_page;
            this.pagination.lastPage = response.data.works.last_page;
            this.pagination.total = response.data.works.total;
            this.pagination.perPage = response.data.works.per_page;

            this.isLoading = false;
        },
        async getPenndingWorks() {
            this.isLoading = true;
            const response = await axios.get('/api/works/pennding', {
                params: {
                    page: this.myWorksPagination.currentPage,
                    per_page: this.myWorksPagination.perPage
                }
            });
            this.penddingWorks = response.data.works.data;

            // 更新分頁信息
            this.pagination.currentPage = response.data.works.current_page;
            this.pagination.lastPage = response.data.works.last_page;
            this.pagination.total = response.data.works.total;
            this.pagination.perPage = response.data.works.per_page;
            this.isLoading = false;
        },
        async getMyCollects() {
            this.isLoading = true;
            const response = await axios.get('/api/works/collects', {
                params: {
                    page: this.myCollectsPagination.currentPage,
                    per_page: this.myCollectsPagination.perPage
                }
            });
            this.collects = response.data.collects.data;
            this.myCollectsPagination.currentPage = response.data.collects.current_page;
            this.myCollectsPagination.lastPage = response.data.collects.last_page;
            this.myCollectsPagination.total = response.data.collects.total;
            this.myCollectsPagination.perPage = response.data.collects.per_page;

            this.isLoading = false;
        },
    }
}