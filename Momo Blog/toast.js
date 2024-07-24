document.addEventListener('DOMContentLoaded', (event) => {
    const toastEl = document.getElementById('loginToast');
    if (toastEl) {
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
});