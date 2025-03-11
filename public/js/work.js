const work = () => {
    return {
        title: '',
        description: '',
        imageFile: null,
        imageName: null,
        pdfFile: null,
        pdfName: null,
        dragoverImage: false,
        dragoverPdf: false,
        loading: false,
        progress: 0,
        message: null,
        messageType: 'success', // 'success' or 'error'

        submitForm() {

            if (!this.validateForm()) return;

            this.loading = true;
            this.progress = 0;
            this.message = null;

            const formData = new FormData();
            formData.append('title', this.title);
            formData.append('description', this.description);
            if (this.imageFile) {
                formData.append('image', this.imageFile);
            }
            formData.append('pdf', this.pdfFile);
            // formData.append('_token', '{{ csrf_token() }}');
            const token = localStorage.getItem('jwt_token');


            axios.post(upload, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Authorization': `Bearer ${token}`
                },
                onUploadProgress: (progressEvent) => {
                    this.progress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                }
            })
                .then(response => {
                    this.loading = false;
                    this.message = response.data.message || '作品上傳成功！';
                    this.messageType = 'success';

                    // 重置表單或跳轉到其他頁面
                    if (response.data.redirect) {
                        setTimeout(() => {
                            window.location.href = response.data.redirect;
                        }, 1500);
                    } else {
                        this.resetForm();
                    }
                })
                .catch(error => {
                    this.loading = false;
                    this.messageType = 'error';

                    if (error.response && error.response.data.errors) {
                        // 處理表單驗證錯誤
                        const errors = error.response.data.errors;
                        const firstError = Object.values(errors)[0][0];
                        this.message = firstError;
                    } else {
                        this.message = error.response?.data?.message || '上傳失敗，請稍後再試。';
                    }
                });
        },

        validateForm() {
            this.message = null;
            if (!this.title.trim()) {

                this.message = '請輸入作品標題';
                this.messageType = 'error';
                console.log(this.message);
                return false;
            }

            if (!this.pdfFile) {
                this.message = '請上傳 PDF 檔案';
                this.messageType = 'error';
                return false;
            }

            return true;
        },

        resetForm() {
            this.title = '';
            this.description = '';
            this.imageFile = null;
            this.imageName = null;
            this.pdfFile = null;
            this.pdfName = null;

            // 重置檔案選擇器
            document.getElementById('image').value = '';
            document.getElementById('pdf').value = '';

            this.progress = 0;
        },

        handleImageFile(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const file = input.files[0];

                // 驗證檔案類型
                const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    this.message = '請上傳有效的圖片檔案 (JPG, PNG, GIF)';
                    this.messageType = 'error';
                    input.value = '';
                    this.imageFile = null;
                    this.imageName = null;
                    return;
                }

                // 驗證檔案大小
                if (file.size > 10 * 1024 * 1024) {
                    this.message = '圖片檔案大小不能超過 10MB';
                    this.messageType = 'error';
                    input.value = '';
                    this.imageFile = null;
                    this.imageName = null;
                    return;
                }

                this.imageFile = file;
                this.imageName = file.name;
            }
        },

        handlePdfFile(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const file = input.files[0];

                // 驗證檔案類型
                if (file.type !== 'application/pdf') {
                    this.message = '請上傳有效的 PDF 檔案';
                    this.messageType = 'error';
                    input.value = '';
                    this.pdfFile = null;
                    this.pdfName = null;
                    return;
                }

                // 驗證檔案大小
                if (file.size > 20 * 1024 * 1024) {
                    this.message = 'PDF 檔案大小不能超過 20MB';
                    this.messageType = 'error';
                    input.value = '';
                    this.pdfFile = null;
                    this.pdfName = null;
                    return;
                }

                this.pdfFile = file;
                this.pdfName = file.name;
            }
        },

        handleImageDrop(event) {
            this.dragoverImage = false;
            const dt = event.dataTransfer;
            if (dt.files && dt.files[0]) {
                const input = document.getElementById('image');
                // 模擬檔案選擇
                const dT = new DataTransfer();
                dT.items.add(dt.files[0]);
                input.files = dT.files;

                // 觸發檔案處理
                this.handleImageFile({ target: input });
            }
        },

        handlePdfDrop(event) {
            this.dragoverPdf = false;
            const dt = event.dataTransfer;
            if (dt.files && dt.files[0]) {
                const input = document.getElementById('pdf');
                // 模擬檔案選擇
                const dT = new DataTransfer();
                dT.items.add(dt.files[0]);
                input.files = dT.files;

                // 觸發檔案處理
                this.handlePdfFile({ target: input });
            }
        }
    }
};