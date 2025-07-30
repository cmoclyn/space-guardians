import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['resource'];
    static values = {
        planet: Number,
        resource: Number
    };

    connect() {
        const refresh = function(){
            this.refresh();
        }.bind(this);
        document.addEventListener('buildingQueue:started', refresh);
        this.refresh();
    }

    disconnect() {
        document.removeEventListener('buildingQueue:started', this.refresh)
    }

    async refresh() {
        const response = await fetch(`/planet/${this.planetValue}/resource/${this.resourceValue}`);
        const data = await response.json();
        console.log('Data received:', data);

        const resourceElement = this.resourceTarget;
        const resourceController = this.application.getControllerForElementAndIdentifier(resourceElement.querySelector('[data-controller~="resource"]'), 'resource');

        if (resourceController) {
            resourceController.load(data);
        } else {
            console.warn('Resource controller not found');
        }
    }
}
