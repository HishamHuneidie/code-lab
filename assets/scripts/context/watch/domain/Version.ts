'use strict'

/**
 * PHP Version
 */
class Version {
    readonly versionNumber: string;
    readonly link: string;
    readonly status: string;
    readonly release: Release;

    constructor(versionNumber: string, link: string, status: string, release: Release) {
        this.versionNumber = versionNumber;
        this.link = link;
        this.status = status;
        this.release = release;
    }

    public static fromHTML(versionItem: HTMLElement): Version {
        return new Version(
            versionItem.getAttribute('data-version-number') ?? '',
            versionItem.getAttribute('data-link') ?? '',
            versionItem.getAttribute('data-status') ?? '',
            Release.fromHTML(versionItem),
        );
    }
}

class Release {
    readonly versionNumber: string;
    readonly link: string;
    readonly date: string;
    readonly listLink: string;

    constructor(versionNumber: string, link: string, date: string, listLink: string) {
        this.versionNumber = versionNumber;
        this.link = link;
        this.date = date;
        this.listLink = listLink;
    }

    public static fromHTML(versionItem: HTMLElement): Release {
        return new Release(
            versionItem.getAttribute('data-release-version-number') ?? '',
            versionItem.getAttribute('data-release-link') ?? '',
            versionItem.getAttribute('data-release-date') ?? '',
            versionItem.getAttribute('data-release-list-link') ?? '',
        );
    }

}

export default Version;