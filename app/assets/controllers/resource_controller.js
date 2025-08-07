import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['quantity', 'production', 'name', 'image'];

    name = '';
    resource = 0;
    quantity = 0;
    production = 0;
    maximum = 0;
    date = Date.now();

    connect() {
    }

    disconnect() {
        clearInterval(this.interval);
    }

    updateQuantity() {
        const total = this.getQuantity();
        if (total === this.maximum) {
            clearInterval(this.interval);
        }
        this.quantityTarget.textContent = Math.floor(total).toLocaleString();
        this.emit();
    }

    getQuantity() {
        const now = Math.floor(Date.now() / 1000);
        const elapsedSeconds = now - this.date;
        const produced = (this.production / 3600) * elapsedSeconds;
        const total = this.quantity + produced;
        return Math.min(total, this.maximum);
    }

    emit() {
        const event = new CustomEvent('resource:updated', {
            bubbles: true,
            detail: {
                name: this.name,
                quantity: this.getQuantity(),
            }
        });
        document.dispatchEvent(event);
    }

    clear() {
        if (typeof this.interval != 'undefined') {
            clearInterval(this.interval);
        }
    }

    init() {
        this.updateQuantity();
        this.interval = setInterval(() => this.updateQuantity(), 1000);

        const interval = function () {
            this.interval();
        }.bind(this);
    }

    load(resource) {
        this.clear();
        this.name = resource.name;
        this.quantity = resource.quantity;
        this.production = resource.production;
        this.maximum = resource.maximum;
        this.date = Math.floor(new Date(resource.date).getTime() / 1000);

        if (this.hasProductionTarget) {
            this.productionTarget.textContent = `+${new Intl.NumberFormat('fr-FR').format(Math.round(resource.production))}/h`;
        }

        if (this.hasNameTarget) {
            this.nameTarget.textContent = resource.name;
        }

        if (this.hasImageTarget) {
            this.imageTarget.src = `/uploads/images/resource/${resource.image}`;
        }
        this.init();
    }
}
