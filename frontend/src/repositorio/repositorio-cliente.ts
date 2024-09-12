import { ClienteApi } from "../api/cliente-api";
import { Cliente } from "../entidade/cliente";

export class RepositorioCliente {
    private clienteApi: ClienteApi;

    public constructor() {
        this.clienteApi = new ClienteApi('/clientes');
    }

    async pesquisar( pesquisa: string ): Promise< Cliente|null > {
        pesquisa = pesquisa.replace(/\D/g, '');
        const resposta = await this.clienteApi.get('', { cpf: pesquisa });
        if ( ! resposta.ok ) {
            throw new Error( 'Erro ao consultar o cliente.' );
        }
        const [ cliente ] = await resposta.json();
        return cliente || null;
    }

    async cadastrar(data: Cliente): Promise<void> {
        const resposta = await this.clienteApi.post('', data);
        if (!resposta.ok) {
            const erro = await resposta.json();
            throw new Error(erro.mensagem || 'Erro ao cadastrar o cliente.');
        }
    }
}
