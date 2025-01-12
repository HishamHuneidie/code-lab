'use strict'

/**
 * Utility: Helps to work with modals
 */
class Modal {
    readonly id: string;
    readonly modal: Element;
    readonly window: Element;
    readonly head: Element;
    readonly body: Element;
    readonly footer: null | Element;
    readonly closeButtons: Array<Element>;
    readonly footerButtons: Map<string, Element>;
    static modals: Map<string, Modal> = new Map();

    private constructor(id: string) {
        const {modal, window} = this.findMainElements(id);
        this.id = id;
        this.modal = modal;
        this.window = window;

        const {head, body, footer} = this.findSecondaryElements(window);
        this.head = head;
        this.body = body;
        this.footer = footer;

        const closeButtons: NodeListOf<Element> = modal.querySelectorAll('[data-modal-button="close"]');
        this.closeButtons = Object.values(closeButtons);
        this.closeButtons.forEach((button: Element) => button.setAttribute('data-modal-id', id));

        const mapFooterButtons: Map<string, Element> = new Map();

        if (footer) {
            const footerButtons: NodeListOf<Element> = footer.querySelectorAll('[data-modal-button]');

            Object.values(footerButtons).forEach((button: Element): void => {
                const action = button.getAttribute('data-modal-button') ?? '';

                if (!action || action === 'close') return;

                button.setAttribute('data-modal-id', id);

                mapFooterButtons.set(action, (button as Element));
            });

        }

        this.footerButtons = mapFooterButtons;

        this.observe();
        this.setEvents();

        Modal.modals.set(id, this);
    }

    public static instance(id: string): Modal {
        let modal: undefined | Modal = Modal.modals.get(id);

        if (modal) return modal;

        return new Modal(id);
    }

    /**
     * Main part of the constructor
     *
     * @param id
     * @private
     */
    private findMainElements(id: string): { modal: Element, window: Element } {
        const modal = document.querySelector(id);
        if (!modal) throw new Error('Modal not found');

        const window = modal.querySelector('.modal-window');
        if (!window) throw new Error('Modal window not found');

        return {modal, window}
    }

    /**
     * Secondary part of the constructor
     *
     * @param window
     * @private
     */
    private findSecondaryElements(window: Element): { head: Element, body: Element, footer: null | Element } {
        const head: null | Element = window.querySelector('.modal-window-head');
        if (!head) throw new Error('Modal head not found');

        const body: null | Element = window.querySelector('.modal-window-body');
        if (!body) throw new Error('Modal body not found');

        const footer: null | Element = window.querySelector('.modal-window-footer');

        return {head, body, footer}
    }

    /**
     * Observe changes in modal
     *
     * @private
     */
    private observe(): void {
        const observer: MutationObserver = new MutationObserver((mutations: MutationRecord[]): void => {
            for (const mutation of mutations) {
                if (mutation.type === 'attributes') {
                    if (mutation.attributeName === 'class') {
                        const target: Element = (mutation.target as Element);

                        const eventType = target.classList.contains('show')
                            ? 'modal:open'
                            : 'modal:close';

                        const event = new Event(eventType);

                        this.modal.dispatchEvent(event);
                    }
                }
            }
        });

        observer.observe(this.modal, {
            attributes: true,
        })
    }

    /**
     * Set events to all elements inside the modal
     *
     * @private
     */
    private setEvents(): void {
        // Events for buttons that close the modal
        this.closeButtons.forEach((button: Element): void => {
            button.addEventListener('click', (e: Event): void => {
                const modalId: null | string = button.getAttribute('data-modal-id');
                const modal: undefined | Modal = Modal.modals.get(modalId as string);

                if (modal) {
                    modal.close();
                }
            });
        });
    }

    /**
     * Searches a button by action|name
     *
     * @param action This is the action or name
     * @private
     */
    public getButton(action: string): undefined | Element {
        return this.footerButtons.get(action);
    }

    /**
     * Opens the modal
     */
    public open(): void {
        this.modal.classList.add('show');
    }

    /**
     * Hides the modal
     */
    public close(): void {
        this.modal.classList.remove('show');
    }

    /**
     * Shows a loader in the modal
     */
    public load(loaderStatus: boolean): void {
        this.modal.setAttribute('data-loader', loaderStatus ? 'true' : 'false');
    }
}

export default Modal;