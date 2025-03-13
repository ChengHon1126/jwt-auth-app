// public/js/login.js
// document.addEventListener('DOMContentLoaded', function() {
//     // 如果用戶已登入（有 JWT token），重定向到儀表板
//     if (localStorage.getItem('jwt_token')) {
//         window.location.href = '/dashboard';
//     }
// });

const loginForm = () => {
    return {
        email: '',
        password: '',
        errorMessage: '',
        successMessage: '',
        loading: false,

        login() {
            this.loading = true;
            this.errorMessage = '';
            this.successMessage = '';

            // 使用 Axios 發送 JWT 登入請求
            axios.post('/api/auth/login', {
                email: this.email,
                password: this.password
            })
                .then(response => {
                    // 登入成功
                    if (response.status === 200) {
                        this.successMessage = '登入成功！正在跳轉...';

                        window.location.href = '/dashboard';
                    }
                })
                .catch(error => {
                    // 處理錯誤
                    if (error.response) {
                        // 服務器回應錯誤
                        if (error.response.data.error) {
                            this.errorMessage = error.response.data.error;
                        } else if (error.response.data.errors) {
                            const errors = error.response.data.errors;
                            this.errorMessage = Object.values(errors).flat().join(', ');
                        } else {
                            this.errorMessage = '登入失敗，請檢查您的電子郵件和密碼';
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


// 確保函數在全局範圍可用
window.loginForm = loginForm;