export function fetchApi(endpoint: string, argOptions: RequestInit & { json?: Record<string, unknown> }) {
    const options = {
        ...argOptions,
        headers: {
            Accept: 'application/json',
            ...argOptions.headers,
        },
    } as RequestInit;

    if ('json' in options) {
        options.body = JSON.stringify(options.json);
        options.headers = {
            ...options.headers,
            ['Content-Type']: 'application/json',
        };
    }

    return fetch(endpoint, options).then(async (r) => {
        if (!r.ok) {
            throw new ApiError(r.status, await r.text());
        }
        if (r.status === 204) {
            return null;
        }
        return r.json();
    });
}

export class ApiError extends Error {
    constructor(
        public readonly status: number,
        public readonly body: string,
    ) {
        super('Server error');
    }
}
