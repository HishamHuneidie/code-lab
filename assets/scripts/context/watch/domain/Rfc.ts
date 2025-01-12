'use strict'

class Rfc {
    readonly pathname: string;
    readonly title: string;
    readonly type: string;
    readonly version: string;
    readonly status: string;
    readonly phpLink: string;

    constructor(
        pathname: string,
        title: string,
        type: string,
        version: string,
        status: string,
        phpLink: string = '',
    ) {
        this.pathname = pathname;
        this.title = title;
        this.type = type;
        this.version = version;
        this.status = status;
        this.phpLink = phpLink;
    }
}

export default Rfc;