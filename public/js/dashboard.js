const dashboard = () => {
    return {
        async init() {
            Alpine.store('user', {
                id: null,
                name: null,
                email: null,
                role: null,
                isLoaded: false,
                isLoggedIn: false,

                set(userData) {
                    this.id = userData.id;
                    this.name = userData.name;
                    this.email = userData.email;
                    this.role = userData.role;
                    this.isLoggedIn = true;
                    this.isLoaded = true;
                    console.log('User data set:', this); // 添加日誌
                },

                clear() {
                    this.id = null;
                    this.name = null;
                    this.email = null;
                    this.role = null;
                    this.isLoggedIn = false;
                    this.isLoaded = true;
                    console.log('User data cleared:', this); // 添加日誌
                }
            });
            await this.fetchUserData();
        },

        async fetchUserData() {
            const response = await axios.get('/api/auth/me', {
                withCredentials: true
            })
            try {
                const data = response.data;
                console.log('獲取用戶資料:', data);
                if (data.status === 'success' && data.user) {
                    if (window.Alpine && Alpine.store('user')) {
                        Alpine.store('user').set(data.user);
                    }
                    document.dispatchEvent(new CustomEvent('user-authenticated', {
                        detail: data.user
                    }));
                }
            } catch (error) {
                console.log('獲取用戶資料失敗:', error);
                if (window.Alpine && Alpine.store('user')) {
                    Alpine.store('user').clear();
                }
                document.dispatchEvent(new CustomEvent('user-unauthenticated'));
            }
        },


    }
}
