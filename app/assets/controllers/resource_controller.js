import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['quantity'];
    static values = {
        quantity: Number,
        production: Number,
        maximum: Number,
        date: Number,
        name: String,
    };

    connect() {
        this.updateQuantity();
        this.interval = setInterval(() => this.updateQuantity(), 1000);
    }

    disconnect() {
        clearInterval(this.interval);
    }

    updateQuantity() {
        const total = this.getQuantity();
        if (total === this.maximumValue) {
            clearInterval(this.interval);
        }
        this.quantityTarget.textContent = Math.floor(total).toLocaleString();
        this.emit();
    }

    getQuantity() {
        const now = Math.floor(Date.now() / 1000);
        const elapsedSeconds = now - this.dateValue;
        const produced = (this.productionValue / 3600) * elapsedSeconds;
        const total = this.quantityValue + produced
        return Math.min(total, this.maximumValue);
    }

    emit() {
        const event = new CustomEvent('resource:updated', {
            bubbles: true,
            detail: {
                eventName: 'resource:updated',
                name: this.nameValue,
                quantity: this.getQuantity(),
            }
        });
        document.dispatchEvent(event);
    }
}
