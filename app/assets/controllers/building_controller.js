import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['buildButton', 'cost'];
    static values = {
        planet: Number,
        building: Number,
        buildingCosts: Array
    }

    currentResources = [];

    connect() {
        document.addEventListener('resource:updated', this.resourceUpdated);
    }

    disconnect() {
        document.removeEventListener('resource:updated', this.resourceUpdated)
    }

    resourceUpdated = (event) => {
        const details = event.detail;
        this.currentResources[details.name] = details.quantity;
        this.buildButtonTarget.disabled = !this.canBuild();
    }

    canBuild() {
        let canBuild = true;
        this.buildingCostsValue.forEach((element) => {
            if (!this.currentResources.hasOwnProperty(element.resourceName)) {
                return false;
            }
            if (this.currentResources[element.resourceName] < element.quantity) {
                canBuild = false;
            }
        });
        return canBuild;
    }

    async build() {
        if (!this.canBuild()) {
            return;
        }

        const response = await fetch(`/planet/${this.planetValue}/build/${this.buildingValue}`, {
            method: 'POST',
        });

        if (response.status === 204) {
            const event = new CustomEvent('buildingQueue:started', {
                bubbles: true,
                detail: {}
            });
            document.dispatchEvent(event);

        } else if (response.status === 400) {
            console.log('error', await response.text());
        } else {
            console.warn('Erreur lors de la construction', await response.text());
        }

        document.dispatchEvent(new CustomEvent('flash:show'));
    }
}
