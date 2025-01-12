'use strict'

import Version from '@context/watch/domain/Version';

const baseUrl = 'https://php.watch';

/**
 * Web component that shows a PHP-Version
 * It show: version, status, releases and more
 * Events: 'open'
 */
class VersionItem extends HTMLElement {
    static COMPONENT_NAME: string = 'version-item';

    connectedCallback() {
        const shadow = this.attachShadow({mode: 'open'});
        const version = Version.fromHTML(this);

        // ----------------------------------- Nodes -----------------------------------
        // ----------------------------------- Nodes -----------------------------------
        // ----------------------------------- Nodes -----------------------------------

        // Create elements
        const versionNumber: HTMLElement = document.createElement('p');
        const mainVersionNumber: HTMLElement = document.createElement('span');
        const releaseVersionNumber: HTMLElement = document.createElement('span');
        const icon: HTMLElement = document.createElement('a');
        const date: HTMLElement = document.createElement('p');
        const tags: HTMLElement = document.createElement('p');
        const status: HTMLElement = document.createElement('span');

        // Adding attributes
        versionNumber.setAttribute('class', 'text text-title');
        releaseVersionNumber.setAttribute('class', 'text-mute text-small text-release-version');
        icon.setAttribute('class', 'icon fa fa-eye');
        icon.setAttribute('href', `${baseUrl}${version.link}`);
        icon.setAttribute('target', '_blank');
        icon.setAttribute('data-link', version.release.link);
        icon.setAttribute('data-release-link', version.release.link);
        icon.setAttribute('data-release-list-link', version.release.link);
        date.setAttribute('class', 'text text-mute');
        tags.setAttribute('class', 'text text-badges');
        status.setAttribute('class', getColorClassByStatus(version.status));

        // Setting content
        mainVersionNumber.textContent = version.versionNumber;
        releaseVersionNumber.textContent = version.release.versionNumber;
        date.textContent = version.release.date;
        status.textContent = version.status;

        // ----------------------------------- Styles -----------------------------------
        // ----------------------------------- Styles -----------------------------------
        // ----------------------------------- Styles -----------------------------------

        const style: HTMLStyleElement = document.createElement('style');

        const styleVariables = {
            __host_width: '',
            __host_margin: '10px',
            __host_border: '15px',
            __icon_size: '30px',
        };

        styleVariables.__host_width = `calc(100% - calc(3 * ${styleVariables.__host_margin}))`;

        // Next comment tells PHPStorm that the following text is CSS
        // language=CSS
        style.textContent = `
            @import 'https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css';

            * {
                padding: 0;
                margin: 0;
                box-sizing: border-box;
                --icon-size: 35px;
            }

            :host {
                display: block;
                padding: 20px 15px !important;
                background: rgba(0, 0, 0, .25);
                border-radius: 15px;
                border-width: 0 0 0 ${styleVariables.__host_border};
                border-style: solid;
                border-color: rgba(0, 0, 0, .3);
                width: calc(${styleVariables.__host_width} / 4) !important;
                overflow: hidden;
                white-space: nowrap;
                box-shadow: 0 20px 20px 7px rgba(0, 0, 0, .15);
                transition: all .2s ease;
                position: relative;
                z-index: 1;
            }

            :host(:hover) {
                transform: translateY(-5px);
            }

            .text {
                height: 25px;
                line-height: 25px;
                width: 100%;
                overflow: hidden;
                text-overflow: ellipsis;
                margin-bottom: 10px;
            }

            .text.text-title {
                font-size: 1.2rem;
                color: #fff;
                width: calc(100% - ${styleVariables.__icon_size})
            }

            .text:not(.text-title) {
                user-select: none;
            }

            .text-small {
                font-size: 0.95rem;
            }

            .text-mute {
                opacity: 0.6;
            }

            .text-release-version {
                margin-left: 10px;
            }

            .icon {
                position: absolute;
                z-index: 2;
                top: 0;
                right: 0;
                margin: 10px;
                width: ${styleVariables.__icon_size};
                height: ${styleVariables.__icon_size};
                line-height: ${styleVariables.__icon_size};
                text-align: center;
                background: rgba(0, 0, 0, 0.4);
                cursor: pointer;
                border-radius: 50%;
                font-size: 12px;
                text-decoration: none;
                color: #fff;
            }

            .icon:hover {
                background: rgba(0, 0, 0, 0.7);
            }

            .text-badges {
                margin-bottom: 0;
            }

            .text.text-badges > span {
                display: inline-block;
                padding: 0 5px;
                border-radius: 5px;
                background-color: black;
                margin-right: 5px;
                height: 20px;
                line-height: 20px;
                font-size: 14px;
            }

            .text.text-badges > .badge {
                font-weight: bold;
            }

            .text.text-badges > .badge-success {
                background-color: ${Color.GREEN};
                color: black;
            }

            .text.text-badges > .badge-info {
                background-color: ${Color.BLUE};
            }

            .text.text-badges > .badge-warning {
                background-color: ${Color.CATERPILLAR};
                color: black;
            }

            .text.text-badges > .badge-alert {
                background-color: ${Color.PINK};
                color: black;
            }
        `;

        // ----------------------------------- Append elements -----------------------------------
        // ----------------------------------- Append elements -----------------------------------
        // ----------------------------------- Append elements -----------------------------------

        versionNumber.appendChild(mainVersionNumber);
        versionNumber.appendChild(releaseVersionNumber);
        tags.appendChild(status);

        shadow.appendChild(style);
        shadow.appendChild(versionNumber);
        shadow.appendChild(icon);
        shadow.appendChild(date);
        shadow.appendChild(tags);

    }

}

enum Color {
    TRANSPARENT = 'transparent',
    PURPLE = '#715fce',
    GREEN = '#6ce3d3',
    PINK = '#f86e93',
    CATERPILLAR = '#ffba42',
    BLUE = '#0c62f1',
    SALMON = '#e45d65',
    YELLOW = '#ffc253',
}

function getColorClassByStatus(status: string): string {
    if (status === 'Upcoming Release') return 'badge badge-info';
    if (status === 'Supported (Latest)' || status === 'Supported') return 'badge badge-success';
    if (status === 'Security-Fixes Only') return 'badge badge-warning';
    if (status === 'Unsupported') return 'badge badge-alert';

    return '';
}

customElements.define(VersionItem.COMPONENT_NAME, VersionItem);