// assets/admin.js
import './js/ckeditor/init.js';
import './styles/admin.scss';

// Prosty przykład: obsługa zdarzenia kliknięcia
document.addEventListener('DOMContentLoaded', () => {
    const list = document.querySelector('[data-element-list]');
    if (list) {
        list.querySelectorAll('[data-action-delete]').forEach(btn => {
            btn.addEventListener('click', async event => {
                event.preventDefault();

                let url = btn.dataset.href;
                if (!confirm('Na jesteś pewien, że chcesz usunąć ten element?')) return;

                const formData = new FormData();
                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        alert('Błąd podczas usuwania.');
                        return;
                    }
                    window.location.href = btn.dataset.redirect;

                } catch (err) {
                    console.error(err);
                    alert('Błąd sieci.');
                }
            });
        });
    }

    console.log('Panel administracyjny załadowany.');
});