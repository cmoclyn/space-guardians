import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['name', 'remainingTime', 'progressBar'];

    name = '';
    level = 0;
    startedAt = Date.now();
    finishedAt = Date.now();

    connect() {
        this.updateRemainingTime();
        this.interval = setInterval(() => this.updateRemainingTime(), 1000);
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
        this.interval = setInterval(() => this.updateRemainingTime(), 1000);

        const interval = function () {
            this.interval();
        }.bind(this);
    }

    clear() {
        if (typeof this.interval != 'undefined') {
            clearInterval(this.interval);
        }
        // TODO Update buildingQueue from back
    }

    load(buildingQueue) {
        this.clear();
        console.log(buildingQueue)
        this.name = buildingQueue.name;
        this.level = buildingQueue.level;
        this.startedAt = Math.floor(new Date(buildingQueue.startedAt).getTime() / 1000);
        this.finishedAt = Math.floor(new Date(buildingQueue.finishedAt).getTime() / 1000);

        if (this.hasNameTarget) {
            this.nameTarget.textContent = `${buildingQueue.name} (niveau ${buildingQueue.level})`;
        }

        this.init();
    }
}
