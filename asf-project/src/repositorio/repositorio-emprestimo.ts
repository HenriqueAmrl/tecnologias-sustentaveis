import { ClienteApi } from "../api/cliente-api";
import { Emprestimo } from "../entidade/emprestimo";
import { Parcela } from "../entidade/parcela";

export class RepositorioEmprestimo {
    private clienteApi: ClienteApi;

    public constructor() {
        this.clienteApi = new ClienteApi('/emprestimos');
    }

    async obterTodos(): Promise<Emprestimo[]> {
        const resposta = await this.clienteApi.get('');
        if (!resposta.ok) {
            throw new Error('Erro ao obter empréstimos');
        }
        const emprestimos = (await resposta.json());

        return emprestimos.map((emprestimo: any) => {
            emprestimo.dataCriacao = new Date(emprestimo.dataCriacao)
            return emprestimo;
        });
    }

    async cadastrar(emprestimo: Emprestimo): Promise<void> {
        const resposta = await this.clienteApi.post('', emprestimo);
        if (!resposta.ok) {
            const erro = await resposta.json();
            throw new Error(erro?.mensagem ?? 'Erro ao cadastrar empréstimo');
        }
    }

    async obterParcelasEmprestimo(id: null|string): Promise<Parcela[]> {
        const resposta = await this.clienteApi.get(`/${id}/parcelas`);
        if (!resposta.ok) {
            throw new Error('Erro ao obter as parcelas.');
        }
        const parcelas = await resposta.json();

        return parcelas.map((parcela: Parcela) => {
            parcela.dataVencimento = new Date(parcela.dataVencimento);
            parcela.dataPagamento = parcela.dataPagamento && new Date(parcela.dataPagamento);
            return parcela;
        });
    }

    async obterComId(id: null|string): Promise<Emprestimo[]> {
        const resposta = await this.clienteApi.get(`/${id}`);
        if (!resposta.ok) {
            throw new Error('Erro ao obter os dados do empréstimo.');
        }
        const emprestimos = await resposta.json();

        return emprestimos.map((emprestimo: Emprestimo) => {
            emprestimo.dataCriacao = new Date(emprestimo.dataCriacao!);

            return emprestimo;
        });
    }

    async pagarParcela(id: number, numeroParcela: number): Promise<void> {
        const resposta = await this.clienteApi.post(`/${id}/parcelas/${numeroParcela}/pagar`);
        if (!resposta.ok) {
            const erro = await resposta.json();
            throw new Error(erro.mensagem ?? 'Erro ao pagar as parcelas.');
        }
    }

    async obterTodosPorPeriodo(dataInicial: string, dataFinal: string): Promise<Emprestimo[]> {
        const resposta = await this.clienteApi.get('', {dataInicial, dataFinal});
        if (!resposta.ok) {
            throw new Error('Erro ao obter empréstimos');
        }
        const emprestimos = (await resposta.json());

        return emprestimos.map((emprestimo: any) => {
            emprestimo.dataCriacao = new Date(emprestimo.dataCriacao)
            return emprestimo;
        });
    }
}