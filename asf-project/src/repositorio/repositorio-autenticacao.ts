import { ClienteApi } from "../api/cliente-api";
import { Credenciais } from "../entidade/credenciais";
import { Usuario } from "../entidade/usuario";

export class RepositorioAutenticacao {
    private clienteApi: ClienteApi;

    public constructor() {
        this.clienteApi = new ClienteApi('/autenticacao');
    }

    async login(credenciais: Credenciais): Promise<Usuario> {
        const resposta = await this.clienteApi.post('/login', credenciais);
        if (!resposta.ok) {
            const erro = await resposta.json();
            throw new Error(erro.mensagem || 'Erro ao realizar o login.');
        }

        return resposta.json();
    }

    async logout(): Promise<void> {
        const resposta = await this.clienteApi.post('/logout');
        if (!resposta.ok) {
            const erro = await resposta.json();
            throw new Error(erro.mensagem || 'Erro ao realizar o login.');
        }
    }
}
