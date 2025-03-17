const work_show = () => {
    return {
        async init() {
            await this.fetchWork();
            await this.user();

            console.log(this.data.id)
        },
        work: {},
        data: [],
        async fetchWork() {
            const response = await axios.get(`/api/works_show`, {
                params: {
                    id: id
                }
            });
            this.work = response.data.work;
        },
        async downloadFile(fileId, filename) {
            try {
                const response = await axios.get(`/api/files/download`, {
                    responseType: 'blob',
                    params: {
                        id: fileId
                    }
                });

                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', filename);
                document.body.appendChild(link);
                link.click();

                link.remove();
                window.URL.revokeObjectURL(url);
            } catch (error) {
                console.error('下载失败', error);
            }
        },

        async user() {
            const response = await axios.get('/api/auth/me', {
                withCredentials: true
            })
            try {
                const data = response.data;
                console.log('獲取用戶資料:', data);
                if (data.status === 'success' && data.user) {
                    this.data = response.data.user;
                }
            } catch (error) {
                console.log('獲取用戶資料失敗:', error);
            }
        },

    }
}
