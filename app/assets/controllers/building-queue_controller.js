import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['name', 'remainingTime', 'progressBar'];

    hasData = false;
    name = '';
    level = 0;
    startedAt = Date.now();
    finishedAt = Date.now();

    connect() {
        this.hide();
    }

    hide(){
        this.nameTarget.classList.add('hidden');
        this.remainingTimeTarget.classList.add('hidden');
        this.progressBarTarget.classList.add('hidden');
    }

    show(){
        this.nameTarget.classList.remove('hidden');
        this.remainingTimeTarget.classList.remove('hidden');
        this.progressBarTarget.classList.remove('hidden');
    }

    disconnect() {
        clearInterval(this.interval);
    }

    updateRemainingTime() {
        const total = this.getRemainingTime();
        if (total === 0) {
            this.clear();
        }

        this.remainingTimeTarget.textContent = this.convertSecondsToReadableFormat(total) + ' (' + this.getPercent() + '%)';
        this.progressBarTarget.style.width = `${this.getPercent()}%`;
    }

    convertSecondsToReadableFormat(seconds) {
        const d = Math.floor(seconds / (3600 * 24));
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = Math.floor(seconds % 60);

        const parts = [];
        if (d > 0) parts.push(`${d}j`);
        if (h > 0 || d > 0) parts.push(`${h}h`);
        if (m > 0 || h > 0 || d > 0) parts.push(`${m}m`);
        parts.push(`${s}s`);

        return parts.join(' ');
    }

    getRemainingTime() {
        const now = Math.floor(Date.now() / 1000);
        const remainingSeconds = this.finishedAt - now;

        return Math.max(0, remainingSeconds);
    }

    getPercent() {
        const totalSeconds = this.finishedAt - this.startedAt;
        return Math.floor((totalSeconds - this.getRemainingTime()) / totalSeconds * 100);
    }


    init() {
        this.updateRemainingTime();
        this.show();
        this.interval = setInterval(() => this.updateRemainingTime(), 1000);

        const interval = function () {
            this.interval();
        }.bind(this);
    }

    clear() {
        if (typeof this.interval != 'undefined') {
            clearInterval(this.interval);
        }
        this.hide();

        if(this.hasData) {
            const parentElement = this.element.closest('[data-controller~="fragments--building-queue"]');
            if (!parentElement) return;

            const parentController = this.application.getControllerForElementAndIdentifier(parentElement, 'fragments--building-queue');
            if (parentController && typeof parentController.refresh === 'function') {
                parentController.refresh();
            }
        }
    }

    load(buildingQueue) {
        if(Object.entries(buildingQueue).length === 0){
            this.hasData = false;
            this.clear();
            return;
        }
        this.hasData = true;
        this.name = buildingQueue.name;
        this.level = buildingQueue.level + 1;
        this.startedAt = Math.floor(new Date(buildingQueue.startedAt).getTime() / 1000);
        this.finishedAt = Math.floor(new Date(buildingQueue.finishedAt).getTime() / 1000);

        if (this.hasNameTarget) {
            this.nameTarget.textContent = `${buildingQueue.name} (niveau ${this.level})`;
        }

        this.init();
    }
}
