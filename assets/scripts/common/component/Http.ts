'use strict'

const BASE_URL: string = '/public/v1';

type Method = 'GET' | 'POST' | 'PUT';

/**
 * The contract for request options
 */
export interface RequestOptions {
    method: Method;
    data?: any;
}

/**
 * The model to declare errors
 */
export class HttpError {
    readonly type: string;
    readonly message: string;

    constructor(type: string, message: string) {
        this.type = type;
        this.message = message;
    }
}

interface RequestInitInterface {
    method: string;
    headers: Record<string, string>;
    body?: any;
}

/**
 * The response after the http request
 */
export class Result {
    readonly data: any;
    readonly errors: Array<HttpError>;

    constructor(data: any, errors: Array<HttpError>) {
        this.data = data;
        this.errors = errors;
    }

    /**
     * Verifies that the result did not get errors
     */
    public isSuccess(): boolean {
        return this.errors.length === 0
    }
}

/**
 * HTTP request
 */
export class HttpRequest {
    private readonly endpoint: string;
    private readonly options: RequestOptions;

    constructor(endpoint: string, options: RequestOptions) {
        this.endpoint = endpoint;
        this.options = options;
    }

    /**
     * Create a request
     *
     * @param {string} endpoint
     * @param {RequestOptions} options
     */
    static create(endpoint: string, options: RequestOptions): HttpRequest {
        return new this(endpoint, options);
    }

    /**
     * Execute request by ajax
     *
     * @returns {Promise<Result>}
     */
    public async execute(): Promise<Result> {
        const requestOptions: RequestInit | RequestInitInterface = this.generateOptions();

        const response: Response = await fetch(`${BASE_URL}${this.endpoint}`, (requestOptions as RequestInit));
        const json = await (response as Response).json();

        const errors = (json.errors ?? []).map((e: HttpError) => new HttpError(e.type, e.message));

        return new Result(json, errors);
    }

    /**
     * Generates options depending on the http method
     *
     * @returns {RequestInit}
     * @private
     */
    private generateOptions(): RequestInit | RequestInitInterface {
        const options: RequestInitInterface = {
            method: this.options.method,
            headers: {
                'Accept': 'application/json'
            },
        };

        if (['PUT', 'POST'].includes(this.options.method)) {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(this.options.data ?? {});
        }

        return options;
    }

}
