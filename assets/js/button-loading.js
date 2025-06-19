// button-loading.js
export function initializeButtonLoading() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                button.innerHTML = 'Processing <span class="spinner"></span>';
                button.disabled = true;
            }
        });
    });
}