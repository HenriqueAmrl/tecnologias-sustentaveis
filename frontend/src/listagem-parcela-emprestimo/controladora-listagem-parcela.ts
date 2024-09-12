import { RepositorioEmprestimo } from "../repositorio/repositorio-emprestimo";
import { VisaoListagemParcela } from "./visao-listagem-parcela";

export class ControladoraListagemParcela {
    private repositorio: RepositorioEmprestimo;
    private visao: VisaoListagemParcela;

    constructor() {
        this.repositorio = new RepositorioEmprestimo();
        this.visao = new VisaoListagemParcela();
    }

    listarParcelas = async () => {
        const urlParams = new URLSearchParams(window.location.search);
        const idEmprestimo = urlParams.get('id');

        const repositorio = new RepositorioEmprestimo();
        try {
            const parcelas = await repositorio.obterParcelasEmprestimo(idEmprestimo);
            this.visao.listarParcelasEmprestimo(parcelas);
        } catch (error) {
            this.visao.exibirErro((error as Error).message);
        }
    }

    listarEmprestimoPorId = async () => {
        const urlParams = new URLSearchParams(window.location.search);
        const idEmprestimo = urlParams.get('id');

        const repositorio = new RepositorioEmprestimo();
        try {
            const dadosEmprestimo = await repositorio.obterComId(idEmprestimo);
            this.visao.listarDadosEmprestimo(dadosEmprestimo);
        } catch (error) {
            this.visao.exibirErro((error as Error).message);
        }
    }

    pagarParcela = async (idParcela: number) => {
        const urlParams = new URLSearchParams(window.location.search);
        const idEmprestimo = parseInt( urlParams.get('id')! );

        const repositorio = new RepositorioEmprestimo();
        try {
            await repositorio.pagarParcela(idEmprestimo, idParcela);
            this.visao.exibirSucesso();
        } catch (error) {
            this.visao.exibirErro((error as Error).message);
        }
    }

    configurarPagamentoParcela() {
        this.visao.prepararPagamento(this.pagarParcela);
    }
}