import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['remainingTime'];
    static values = {
        startedAt: Number,
        finishedAt: Number
    }

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
            clearInterval(this.interval);
        }

        this.remainingTimeTarget.textContent = total + 'secondes restantes (' + this.getPercent() + '%)';
    }

    getRemainingTime() {
        const now = Math.floor(Date.now() / 1000);
        const remainingSeconds = this.finishedAtValue - now;

        return Math.max(0, remainingSeconds);
    }

    getPercent() {
        const totalSeconds = this.finishedAtValue - this.startedAtValue;
        return Math.floor((totalSeconds - this.getRemainingTime()) / totalSeconds * 100);
    }
}
