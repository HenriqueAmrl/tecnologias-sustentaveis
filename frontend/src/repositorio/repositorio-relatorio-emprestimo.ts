import { ClienteApi } from "../api/cliente-api";
import { RelatorioEmprestimo } from "../entidade/relatorio-emprestimo";

export class RepositorioRelatorioEmprestimo {
    private clienteApi: ClienteApi;

    public constructor() {
        this.clienteApi = new ClienteApi('/relatorios');
    }

    async buscarEmprestimoPorPeriodo( dataInicial: string, dataFinal: string ): Promise< RelatorioEmprestimo|null > {
        const resposta = await this.clienteApi.get('/emprestimos-por-periodo', {dataInicial, dataFinal});
        if ( ! resposta.ok ) {
            throw new Error( 'Erro ao consultar o relatório de emprestimos.' );
        }
        const relatorio = await resposta.json();
        return relatorio || null;
    }

}

