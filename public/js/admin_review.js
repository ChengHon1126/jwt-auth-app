const review = () => {
    return {
        async init() {
            await this.fetchReviewData();
        },
        datas: [],
        withCredentials: false,
        // 添加分頁相關屬性
        pagination: {
            currentPage: 1,
            lastPage: 1,
            total: 0,
            perPage: 10
        },
        async fetchReviewData() {
            const response = await axios.get('/api/review', {
                withCredentials: true,
                page: this.pagination.currentPage,
                per_page: this.pagination.perPage
            })
            try {
                const data = response.data;
                if (data.status === 'success' && data.data) {
                    this.datas = data.data.data;

                    // 更新分頁信息
                    this.pagination.currentPage = data.current_page;
                    this.pagination.lastPage = data.last_page;
                    this.pagination.total = data.total;
                    this.pagination.perPage = data.per_page;
                }
            } catch (error) {
                alert('獲取評論資料失敗:', error);
            }
        },
        // 換頁方法
        changePage(page, tabType) {
            if (page < 1 || page > this.pagination.lastPage) return;
            this.pagination.currentPage = page;
            this.fetchReviewData();
        }
    }
}

const lessonPlansTable = () => {
    return {
        async init() {
            await this.fetchLessonPlans();
        },
        lessonPlans: [],
        withCredentials: false,
        // 添加分頁相關屬性
        pagination: {
            currentPage: 1,
            lastPage: 1,
            total: 0,
            perPage: 10
        },

        async fetchLessonPlans() {
            const res = await axios.get('/api/lesson-plans', {
                params: {
                    page: this.pagination.currentPage,
                    per_page: this.pagination.perPage
                }
            });
            // console.log(res.data.data);
            const data = res.data.data;
            this.lessonPlans = data.data;
            this.pagination.currentPage = data.current_page;
            this.pagination.lastPage = data.last_page;
            this.pagination.total = data.total;
        },
        changePage(page, tabType) {
            if (page < 1 || page > this.pagination.lastPage) return;
            this.pagination.currentPage = page;
            this.fetchLessonPlans();
        },

        async push(id) {
            try {


                const res = await axios.put('/api/lesson-plans', {
                    id: id
                });

                if (res.status === 201) {
                    alert('推送成功');
                    this.fetchLessonPlans();
                    // 可以添加其他成功後的邏輯，如刷新列表
                } else {
                    alert('推送失敗：未知錯誤');
                }
            } catch (e) {
                alert('推送失敗');
                console.error(e);
                // 可以根據錯誤類型做更詳細的錯誤處理
                if (e.response) {
                    // 伺服器返回錯誤
                    console.error('伺服器返回錯誤:', e.response.data);
                } else if (e.request) {
                    // 請求已發出但無回應
                    console.error('無法連接到伺服器');
                } else {
                    // 設置請求時發生錯誤
                    console.error('請求設置錯誤', e.message);
                }
            }
        },

        async deleteLessonPlan(id) {
            if (!confirm('確定要刪除此課程計劃嗎？')) return;

            try {
                const res = await axios.delete(`/api/lesson-plans`, {
                    data: { id: id }
                });
                if (res.status === 204) {
                    alert('刪除成功');
                    this.fetchLessonPlans();
                }
            } catch (err) {
                alert('刪除失敗');
                console.log(err);
            }
        }
    }
}