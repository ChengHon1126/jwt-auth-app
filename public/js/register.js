const registerForm = ()=> {
    return {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        errorMessage: '',
        successMessage: '',
        loading: false,
        
        register() {
            this.loading = true;
            this.errorMessage = '';
            this.successMessage = '';
            
            // 檢查密碼確認
            if (this.password !== this.password_confirmation) {
                this.errorMessage = '密碼與確認密碼不符';
                this.loading = false;
                return;
            }
            
            // 使用 Axios 發送註冊請求
            axios.post('/api/auth/register', {
                name: this.name,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation
            })
            .then(response => {
                // 註冊成功
                this.successMessage = '註冊成功！請稍候...';
                
                // 清空表單
                this.name = '';
                this.email = '';
                this.password = '';
                this.password_confirmation = '';
                
                // 延遲後跳轉到登入頁面
                setTimeout(() => {
                    window.location.href = '/login';
                }, 1500);
            })
            .catch(error => {
                // 處理錯誤
                if (error.response) {
                    // 服務器回應錯誤
                    if (error.response.data.errors) {
                        // 驗證錯誤的特殊處理
                        const errors = error.response.data.errors;
                        const firstError = Object.values(errors)[0][0];
                        this.errorMessage = firstError;
                    } else {
                        this.errorMessage = error.response.data.message || '註冊失敗';
                    }
                } else if (error.request) {
                    // 請求發出但沒有收到回應
                    this.errorMessage = '無法連接到伺服器，請稍後再試';
                } else {
                    // 其他錯誤
                    this.errorMessage = '發生錯誤，請稍後再試';
                }
            })
            .finally(() => {
                this.loading = false;
            });
        }
    }
}