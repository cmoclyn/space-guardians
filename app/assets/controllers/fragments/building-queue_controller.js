import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        planet: Number
    };

    connect() {
        this.refresh();
    }

    async refresh() {
        // const response = await fetch(`/planet/${this.planetValue}/buildingQueue`)
        // const html = await response.text();
        //
        // const template = document.createElement('template')
        // template.innerHTML = html.trim()
        //
        // this.element.replaceWith(template.content.firstElementChild)
    }
}
