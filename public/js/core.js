

document.addEventListener('DOMContentLoaded', function () {
    // 設置 CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

});