'use strict'

class RfcItem extends HTMLElement {
    connectedCallback() {
        const shadow = this.attachShadow({mode: 'open'});

        // --------------- Nodes ---------------
        // --------------- Nodes ---------------
        // --------------- Nodes ---------------

        const title: HTMLElement = document.createElement('p');
        const icon: HTMLElement = document.createElement('i');
        const badges: HTMLElement = document.createElement('p');

        title.innerHTML = this.getAttribute('data-title') ?? '';
        icon.dataset.pathname = this.getAttribute('data-pathname') ?? '';
        icon.dataset.phpLink = '';
        icon.dataset.modalId = this.getAttribute('data-modal-id') ?? '';

        const type: string = this.getAttribute('data-type') ?? '';
        const version: string = this.getAttribute('data-version') ?? '';
        const status: string = this.getAttribute('data-status') ?? '';
        const typeObject = new TypeUtil(type);
        const versionObject = new VersionUtil(version);
        const statusObject = new StatusUtil(status);

        badges.innerHTML = `
            ${typeObject.generateBadge()}
            ${versionObject.generateBadge()}
            ${statusObject.generateBadge()}
        `;

        title.setAttribute('class', 'text text-title');
        icon.setAttribute('class', 'icon fa fa-eye');
        badges.setAttribute('class', 'text text-badges');

        // --------------- Styles ---------------
        // --------------- Styles ---------------
        // --------------- Styles ---------------

        const style = document.createElement('style');

        const styleVariables = {
            __icon_size: '30px',
        };

        // Next comment tells PHPStorm that textContent's language is CSS
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
                display: inline-block;
                padding: 20px 15px !important;
                background: rgba(0, 0, 0, .25);
                border-radius: 15px;
                border-width: 0 0 0 15px;
                border-style: solid;
                border-color: rgba(0, 0, 0, .3);
                width: 300px;
                max-width: 400px;
                min-width: 200px;
                flex: 1 1 auto;
                margin: 0 10px 10px 0 !important;
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
            }

            .text.text-title {
                font-size: 1.2rem;
                color: #fff;
                margin-bottom: 10px;
                width: calc(100% - ${styleVariables.__icon_size})
            }

            .text:not(.text-title) {
                user-select: none;
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
            }

            .icon:hover {
                background: rgba(0, 0, 0, 0.7);
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
                background-color: ${VersionColor.GREEN};
                color: black;
            }

            .text.text-badges > .badge-info {
                background-color: ${VersionColor.BLUE};
            }

            .text.text-badges > .badge-warning {
                background-color: ${VersionColor.CATERPILLAR};
                color: black;
            }

            .text.text-badges > .badge-alert {
                background-color: ${VersionColor.PINK};
                color: black;
            }
        `;

        // --------------- Append elements ---------------
        // --------------- Append elements ---------------
        // --------------- Append elements ---------------

        shadow.appendChild(style);
        shadow.appendChild(title);
        shadow.appendChild(icon);
        shadow.appendChild(badges);
    }
}

interface Utils {
    generateBadge(): string;
}

class TypeUtil implements Utils {
    readonly label: string;
    readonly type: string;
    readonly class: string;

    constructor(label: string) {
        this.label = label;
        this.type = this.resolveType(label);
        this.class = this.resolveClass(this.type);
    }

    private resolveType(label: string): string {
        switch (label) {
            case 'New Feature':
                return Type.NEW;
            case 'Improvement':
                return Type.IMPROVEMENT;
            case 'Deprecation':
                return Type.DEPRECATION;
            case 'Removal':
                return Type.REMOVED;
        }

        throw new TypeError(`Not recognized type '${label}'`);
    }

    private resolveClass(type: string): string {
        switch (type) {
            case Type.NEW:
                return 'badge-success';
            case Type.IMPROVEMENT:
                return 'badge-info';
            case Type.DEPRECATION:
                return 'badge-warning';
            case Type.REMOVED:
                return 'badge-alert';
        }

        throw new TypeError(`Not recognized type '${type}'`);
    }

    public generateBadge(): string {
        if (!this.label) return '';

        return `
        <span class="badge ${this.class}">
            ${this.label}
        </span>
        `;
    }
}

enum Type {
    NEW = 'new',
    IMPROVEMENT = 'improvement',
    DEPRECATION = 'deprecation',
    REMOVED = 'removed',
}

class VersionUtil implements Utils {
    readonly label: string;
    readonly version: string;
    readonly color: string;

    constructor(label: string) {
        this.label = label;
        this.version = this.resolveVersion(label);
        this.color = this.resolveColor(this.version);
    }

    private resolveVersion(label: string): string {
        const match = label.match(/(\d)/);

        if (match && match.length > 0) {
            return match[0];
        }

        return Version.EMPTY;
    }

    private resolveColor(version: string): string {
        switch (version) {
            case Version.EMPTY:
                return VersionColor.TRANSPARENT;
            case Version.FIVE:
                return VersionColor.BLUE;
            case Version.SEVEN:
                return VersionColor.GREEN;
            case Version.EIGHT:
                return VersionColor.PURPLE;
            case Version.NINE:
                return VersionColor.CATERPILLAR;
            case Version.TEN:
                return VersionColor.PINK;
            case Version.ELEVEN:
                return VersionColor.BLUE;
            case Version.TWELVE:
                return VersionColor.SALMON;
            case Version.THIRTEEN:
                return VersionColor.YELLOW;
        }

        return VersionColor.TRANSPARENT;
    }

    public generateBadge(): string {
        if (!this.label) return '';

        return `
        <span class="badge" style="background-color:${this.color}; ">
            ${this.label}
        </span>
        `;
    }
}

enum Version {
    EMPTY = '',
    FIVE = '5',
    SEVEN = '7',
    EIGHT = '8',
    NINE = '9',
    TEN = '10',
    ELEVEN = '11',
    TWELVE = '12',
    THIRTEEN = '13',
}

enum VersionColor {
    TRANSPARENT = 'transparent',
    PURPLE = '#715fce',
    GREEN = '#6ce3d3',
    PINK = '#f86e93',
    CATERPILLAR = '#ffba42',
    BLUE = '#0c62f1',
    SALMON = '#e45d65',
    YELLOW = '#ffc253',
}

class StatusUtil implements Utils {
    readonly label: string;
    readonly status: string;
    readonly class: string;

    constructor(label: string) {
        this.label = label;
        this.status = this.resolveTStatus(label);
        this.class = this.resolveClass(this.status);
    }

    private resolveTStatus(label: string): string {
        switch (label) {
            case 'In Voting [Passing]':
                return Status.VOTING_PASSING;
            case 'Accepted':
                return Status.ACCEPTED;
            case 'Implemented':
                return Status.IMPLEMENTED;
            case 'Declined':
                return Status.DECLINED;
        }

        throw new TypeError(`Not recognized status '${label}'`);
    }

    private resolveClass(status: string): string {
        switch (status) {
            case Status.VOTING_PASSING:
                return 'badge-warning';
            case Status.ACCEPTED:
                return 'badge-success';
            case Status.IMPLEMENTED:
                return 'badge-info';
            case Status.DECLINED:
                return 'badge-alert';
        }

        throw new TypeError(`Not recognized status '${status}'`);
    }

    public generateBadge(): string {
        if (!this.label) return '';

        return `
        <span class="badge ${this.class}">
            ${this.label}
        </span>
        `;
    }
}

enum Status {
    VOTING_PASSING = 'voting-passing',
    ACCEPTED = 'accepted',
    IMPLEMENTED = 'implemented',
    DECLINED = 'declined',
}

// Register web component
customElements.define('rfc-item', RfcItem);