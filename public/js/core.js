
document.addEventListener('DOMContentLoaded', function() {
    // 為所有 AJAX 請求設置 CSRF Token
    const token = document.head.querySelector('meta[name="csrf-token"]');
    
    if (token) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error('CSRF token not found');
    }
    
    // 檢查是否已登入 (已存儲 JWT)
    const jwt = localStorage.getItem('jwt_token');
    if (jwt) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${jwt}`;
        console.log(jwt);
    }else{
        // window.location.href = '/login';

    }
    
});
