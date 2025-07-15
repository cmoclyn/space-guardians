import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['quantity'];
    static values = {
        quantity: Number,
        production: Number,
        date: Number,
    };

    connect() {
        this.updateQuantity();
        this.interval = setInterval(() => this.updateQuantity(), 1000);
    }

    disconnect() {
        clearInterval(this.interval);
    }

    updateQuantity() {
        const now = Math.floor(Date.now() / 1000);
        const elapsedSeconds = now - this.dateValue;
        const produced = (this.productionValue / 3600) * elapsedSeconds;
        const total = this.quantityValue + produced;
        this.quantityTarget.textContent = Math.floor(total).toLocaleString();
    }
}
