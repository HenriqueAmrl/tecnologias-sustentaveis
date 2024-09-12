import { RepositorioEmprestimo } from "../repositorio/repositorio-emprestimo";
import { VisaoListagemEmprestimo } from "./visao-listagem-emprestimo";

export class ControladoraListagemEmprestimo {
    private repositorio: RepositorioEmprestimo;
    private visao: VisaoListagemEmprestimo;

    constructor() {
        this.repositorio = new RepositorioEmprestimo();
        this.visao = new VisaoListagemEmprestimo();
    }

    async listarTodos(): Promise<void> {
        try {
            const emprestimos = await this.repositorio.obterTodos();
            this.visao.listarEmprestimos(emprestimos);
        } catch (erro) {
            const mensagem = (erro as Error).message;
            this.visao.exibirErro(mensagem);
        }
    }
}