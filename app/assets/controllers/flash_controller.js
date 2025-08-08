import {Controller} from '@hotwired/stimulus'

export default class extends Controller {
    connect() {
        document.addEventListener('flash:show', () => this.fetchAndDisplay());
    }

    async fetchAndDisplay() {
        const response = await fetch('/flash/messages');
        const flashes = await response.json();

        for (const [type, messages] of Object.entries(flashes)) {
            messages.forEach(message => this.showToast(message, type));
        }
    }

    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = message;

        Object.assign(toast.style, {
            position: 'fixed',
            top: '1rem',
            right: '1rem',
            backgroundColor: type === 'error' ? '#dc2626' : '#1e40af',
            color: 'white',
            padding: '0.75rem 1.25rem',
            borderRadius: '0.5rem',
            boxShadow: '0 2px 6px rgba(0, 0, 0, 0.3)',
            zIndex: 9999,
            transition: 'opacity 0.5s ease-in-out'
        });

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }
}
