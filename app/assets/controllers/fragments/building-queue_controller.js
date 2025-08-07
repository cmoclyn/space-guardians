import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['buildingQueue'];
    static values = {
        planet: Number
    };

    connect() {
        this.refresh();
    }

    async refresh() {
        const response = await fetch(`/planet/${this.planetValue}/buildingQueue`);
        const data = await response.json();

        const buildingQueueElement = this.buildingQueueTarget;
        const buildingQueueController = this.application.getControllerForElementAndIdentifier(buildingQueueElement.querySelector('[data-controller~="building-queue"]'), 'building-queue');

        if (buildingQueueController) {
            buildingQueueController.load(data);
        } else {
            console.warn('Resource controller not found');
        }
    }
}
