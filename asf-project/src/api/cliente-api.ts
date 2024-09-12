const URL_API = 'http://localhost:3000';

export class ClienteApi {
    private urlBase: string;

    constructor(urlRecurso: string) {
        this.urlBase = URL_API + urlRecurso;
    }

    private async fetch(url: string, init?: RequestInit): Promise<Response> {
        const resposta = await fetch(this.urlBase + url, {
            credentials: 'include',
            ...init
        });
        if (resposta.status === 401) {
            location.href = '/login';
            throw new Error('Faça login para utilizar o sistema.');
        }
        if (resposta.status === 403) {
            throw new Error('Acesso não permitido.');
        }

        return resposta;
    }

    public async get(url: string, filters?: { [key: string]: any }): Promise<Response> {
        let query = new URLSearchParams(filters);
        return this.fetch(`${url}?${query.toString()}`);
    }

    public async post(url: string, body?: any): Promise<Response> {
        return this.fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(body)
        });
    }
}
