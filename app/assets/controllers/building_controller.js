import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['buildButton', 'cost'];
    static values = {
        buildingCosts: Array
    }

    currentResources = [];

    connect() {
        document.addEventListener('resource:updated', this.resourceUpdated)
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
}
