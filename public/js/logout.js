const logout = ()=> {
    const token = localStorage.getItem('jwt_token');

    if (token) {
        axios.post('/api/auth/logout', {}, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                localStorage.removeItem('jwt_token');
                window.location.href = '/login';
            })
            .catch(error => {
                console.error('登出時發生錯誤', error);
                // 即使發生錯誤，也刪除令牌並重定向
                localStorage.removeItem('jwt_token');
                window.location.href = '/login';
            });
    } else {
        window.location.href = '/login';
    }
}