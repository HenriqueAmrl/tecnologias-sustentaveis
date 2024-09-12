import { ClienteApi } from "../api/cliente-api";
import { FormaPagamento } from "../entidade/forma-pagamento";
import { SimulacaoParcela } from "../entidade/simulacao-parcela";

export class RepositorioFormaPagamento {
    private clienteApi: ClienteApi;

    public constructor() {
        this.clienteApi = new ClienteApi('/formas-pagamento');
    }

    async obterTodos(): Promise<FormaPagamento[]> {
        const resposta = await this.clienteApi.get('');
        if (!resposta.ok) {
            throw new Error('Erro ao consultar as formas de pagamento.');
        }

        return resposta.json();
    }

    async simular(idPagamento: number, valor: number): Promise<SimulacaoParcela[]> {
        const resposta = await this.clienteApi.post(`/${idPagamento}/simular`, { valor });
        if (!resposta.ok) {
            throw new Error('Erro ao obter as parcelas.');
        }
        const parcelas = await resposta.json();

        return parcelas.map((parcela: SimulacaoParcela) => {
            parcela.dataVencimento = new Date(parcela.dataVencimento);

            return parcela;
        });
    }
}