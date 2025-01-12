'use strict'

declare global {
    interface HTMLElement {
        getParentByClass(className: string): null | HTMLElement;

        show(): void;

        hide(): void;

        appear(): void;

        disappear(): void;
    }
}

Object.defineProperty(HTMLElement.prototype, 'getParentByClass', {
    enumerable: false,
    value: function (className: string): null | HTMLElement {
        let parent: HTMLElement = this.parentElement;

        while (!parent.classList.contains(className)) {
            if (parent.localName === 'body') return null;

            parent = (parent.parentElement as HTMLElement);
        }

        return parent;
    },
});

Object.defineProperty(HTMLElement.prototype, 'show', {
    enumerable: false,
    value: function (): void {
        this.classList.remove('d-hidden');
    }
});

Object.defineProperty(HTMLElement.prototype, 'hide', {
    enumerable: false,
    value: function (): void {
        this.classList.add('d-hidden');
    }
});

Object.defineProperty(HTMLElement.prototype, 'appear', {
    enumerable: false,
    value: function (): void {
        this.classList.remove('d-none');
    }
});

Object.defineProperty(HTMLElement.prototype, 'disappear', {
    enumerable: false,
    value: function (): void {
        this.classList.add('d-none');
    }
});

export {};