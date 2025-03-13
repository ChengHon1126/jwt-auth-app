const logout = () => {
    const token = localStorage.getItem('jwt_token');

    // if (token) {
    axios.post('/api/auth/logout', {}, {
        headers: {
            'Authorization': `Bearer ${token}`
        }
    })
        .then(response => {
            if (response.status === 200) {
                // localStorage.removeItem('jwt_token');
                window.location.href = '/login';
            }
        })
        .catch(error => {

            window.location.href = '/login';
        });
    // } else {
    //     window.location.href = '/login';
    // }
}