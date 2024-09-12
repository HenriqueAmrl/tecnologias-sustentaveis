import { RepositorioEmprestimo } from "../repositorio/repositorio-emprestimo";
import { RepositorioRelatorioEmprestimo } from "../repositorio/repositorio-relatorio-emprestimo";
import { VisaoRelatorioEmprestimo } from "./visao-relatorio-emprestimo";

export class ControladoraRelatorioEmprestimo {
    private repositorio: RepositorioRelatorioEmprestimo;
    private visao: VisaoRelatorioEmprestimo;

    constructor() {
        this.repositorio = new RepositorioRelatorioEmprestimo();
        this.visao = new VisaoRelatorioEmprestimo();
    }

    configurarRelatorio() {
        this.visao.prepararRelatorio(this.buscarDadosRelatorio);
    }

    configurarMostrarDados() {
        this.visao.prepararMostrarDados(this.mostrarDados);
    }

    buscarDadosRelatorio = async () => {
        const dados = this.visao.obterDadosRelatorio();

        const repositorio = new RepositorioRelatorioEmprestimo();
        try {
            const dadosRelatorio = await repositorio.buscarEmprestimoPorPeriodo(dados.dataInicial, dados.dataFinal);
            this.visao.renderizarGrafico(dadosRelatorio!);
            this.visao.visualizarDadosEmprestimo(dadosRelatorio!);
        } catch (error) {
            this.visao.exibirErro((error as Error).message);
        }
    }

    mostrarDados = async () => {
        const dados = this.visao.obterDadosRelatorio();

        const repositorioEmprestimo = new RepositorioEmprestimo();
        try {
            const dadosRelatorio = await repositorioEmprestimo.obterTodosPorPeriodo(dados.dataInicial, dados.dataFinal);
            this.visao.exibirDados(dadosRelatorio);
        } catch (error) {
            this.visao.exibirErro((error as Error).message);
        }
    }

}