import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['tooltip'];
    static values = {
        position: { type: String, default: 'top' }, // 'top' or 'bottom'
        backgroundColor: { type: String, default: 'bg-gray-600' },
        arrow: { type: Boolean, default: true }
    };

    connect() {
        this.element.classList.add('relative');
        this.applyBaseClasses();
        this.setPositioning();
        this.setBackgroundColor();
        if (this.arrowValue) {
            this.insertArrow();
        }
    }

    show() {
        this.tooltipTarget.classList.remove('invisible', 'opacity-0');
        this.tooltipTarget.classList.add('visible', 'opacity-100');
    }

    hide() {
        this.tooltipTarget.classList.remove('visible', 'opacity-100');
        this.tooltipTarget.classList.add('invisible', 'opacity-0');
    }

    applyBaseClasses() {
        this.tooltipTarget.classList.add(
            'absolute',
            'text-sm',
            'text-white',
            'rounded-lg',
            'shadow',
            'transition-opacity',
            'duration-300',
            'invisible',
            'opacity-0',
            'z-10',
            'px-3',
            'py-2',
            'whitespace-nowrap',
            'left-1/2',
            '-translate-x-1/2'
        );
    }

    setPositioning() {
        if (this.positionValue === 'bottom') {
            this.tooltipTarget.classList.add('top-full', 'mt-2');
        } else {
            this.tooltipTarget.classList.add('bottom-full', 'mb-2');
        }
    }

    setBackgroundColor() {
        this.tooltipTarget.classList.add(this.backgroundColorValue);
    }

    insertArrow() {
        const arrow = document.createElement('div');
        arrow.classList.add(
            'absolute',
            'w-2',
            'h-2',
            this.backgroundColorValue,
            'rotate-45',
            'left-1/2',
            '-translate-x-1/2'
        );

        if (this.positionValue === 'bottom') {
            arrow.classList.add('-top-1');
        } else {
            arrow.classList.add('-bottom-1');
        }

        this.tooltipTarget.appendChild(arrow);
    }
}
