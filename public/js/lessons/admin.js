
const lessonPlanForm = () => {
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
                window.location.href = '/dashboard';

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