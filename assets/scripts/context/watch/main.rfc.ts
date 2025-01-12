'use strict'

import 'document-register-element';
import '@web-component/RfcItem';
import Rfc from '@context/watch/domain/Rfc';
import Modal from '@component/Modal';
import {HttpRequest, Result} from '@component/Http';

const modalId = '#modal-php-net-viewer';
let modal: Modal;

const init = (e: Event): void => {

    modal = Modal.instance(modalId);

    const items: NodeListOf<HTMLElement> = document.querySelectorAll('rfc-item');
    Object.values(items).forEach((item: HTMLElement): void => {
        const icon: null | HTMLElement = item.shadowRoot?.querySelector('.icon') ?? null;

        if (!icon) return;

        icon.addEventListener('click', showRfc);
    });
};

window.addEventListener('load', init);

// Util methods

async function showRfc(e: Event): Promise<void> {
    const icon: Element = (e.currentTarget as Element);
    const pathname: string = icon.getAttribute('data-pathname') ?? '';
    const phpLink: null | string = icon.getAttribute('data-php-link') ?? null;

    if (phpLink && modal) {
        const frame: null | Element = modal.modal.querySelector('iframe') ?? null;

        if (frame) {
            frame.setAttribute('src', phpLink);
            modal.load(false);
            return modal.open();
        }
    }

    if (!modal) {
        return;
    }

    modal.load(true);
    modal.open();

    const request: HttpRequest = HttpRequest.create(`/watch/${pathname}`, {
        method: 'GET'
    });

    try {
        const result: Result = await request.execute();

        if (!result.isSuccess()) {
            // TODO: Show errors
            console.log('cambur: noSuccess:', result);
        }

        const data: Rfc = result.data;
        const frame: null | Element = modal.modal.querySelector('iframe') ?? null;

        if (frame) {
            frame.setAttribute('src', data.phpLink);
            icon.setAttribute('data-php-link', data.phpLink);
            modal.load(false);
            return modal.open();
        }
    } catch (e) {
        // TODO: Show errors
        console.log('cambur: catch:', e);
    }
}