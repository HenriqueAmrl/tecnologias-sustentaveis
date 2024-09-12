import { RepositorioCliente } from "../repositorio/repositorio-cliente";
import { RepositorioEmprestimo } from "../repositorio/repositorio-emprestimo";
import { RepositorioFormaPagamento } from "../repositorio/repositorio-forma-pagamento";
import { Cliente } from "../entidade/cliente";
import { Emprestimo } from "../entidade/emprestimo";
import { Formatador } from "../helper/formatador";
import { VisaoCadastroEmprestimo } from "./visao-cadastro-emprestimo";

const valorMinimoEmprestimo = 500;

export class ControladoraCadastroEmprestimo {

    private visao: VisaoCadastroEmprestimo;
    private cliente?: Cliente;

    constructor() {
        this.visao = new VisaoCadastroEmprestimo();
    }

    configurarBuscaCliente() {
        this.visao.prepararPesquisa( this.pesquisarCliente );
    }

    configurarExibicaoParcelas() {
        this.visao.prepararExibicaoParcelas(this.exibirParcelas);
    }

    configurarLimpezaFormulario(): void {
        this.visao.prepararLimpezaFormulario();
    }

    pesquisarCliente = async () => {
        const cpf = this.visao.obterCpf();
        const repositorio = new RepositorioCliente();
        try {
            const resultado = await repositorio.pesquisar( cpf );
            this.visao.mostrarResultado( resultado );
            this.cliente = resultado || undefined;
        } catch (error) {
            this.visao.exibirCliente( ( error as Error ).message );
        }
    }

    private validarDados(): boolean {
        const valor = this.visao.obterValorEmprestimo();
        const idPagamento = this.visao.obterIdPagamentoSelecionado();

        if(!this.cliente) {
            this.visao.exibirErro('Insira um CPF válido');
            this.visao.desabilitarEnvio();
            
            return false;
        }
        if(valor === null) {
            this.visao.exibirErro('Preencha o valor do empréstimo');
            this.visao.desabilitarEnvio();
            
            return false;
        }
        if(idPagamento === null) {
            this.visao.exibirErro('Selecione uma forma de pagamento');
            this.visao.desabilitarEnvio();
            
            return false;
        }
        if(valor < valorMinimoEmprestimo || valor > this.cliente.limite!.disponivel) {
            this.visao.exibirErro(`O valor do empréstimo deve estar entre ${Formatador.formatarDinheiro(valorMinimoEmprestimo)} e ${Formatador.formatarDinheiro(this.cliente.limite!.disponivel)}`);
            this.visao.desabilitarEnvio();

            return false;
        }
        this.visao.exibirErro('');
        this.visao.habilitarEnvio();

        return true;
    }

    exibirParcelas = async () => {
        this.visao.ocultarParcelas();
        if (!this.validarDados()) {
            return;
        }

        const valor = this.visao.obterValorEmprestimo()!;
        const idPagamento = this.visao.obterIdPagamentoSelecionado()!;

        const repositorio = new RepositorioFormaPagamento();
        try {
            const parcelas = await repositorio.simular(idPagamento, valor);
            this.visao.exibirParcelas(parcelas);
        } catch (error) {
            this.visao.exibirErro((error as Error).message);
        }
    }

    async exibirFormasPagamento(): Promise<void> {
        const repositorio = new RepositorioFormaPagamento();
        try {
            const formasPagamento = await repositorio.obterTodos();
            this.visao.exibirFormasPagamento(formasPagamento);
        } catch (error) {
            this.visao.exibirErro( ( error as Error ).message );
        }
    }

    configurarCadastroEmprestimo(): void {
        this.visao.prepararCadastroEmprestimo(this.realizarEmprestimo);
    }

    realizarEmprestimo = async () => {
        if (!this.validarDados()) {
            return;
        }

        const idPagamento = this.visao.obterIdPagamentoSelecionado()!;
        const valor = this.visao.obterValorEmprestimo()!;
        const emprestimo: Emprestimo = {
            cliente: {
                id: this.cliente!.id!
            },
            formaPagamento: {
                id: idPagamento
            },
            valor
        }

        const repositorio = new RepositorioEmprestimo();
        try {
            await repositorio.cadastrar(emprestimo);
            this.visao.exibirSucesso();
        } catch (error) {
            this.visao.exibirErro((error as Error).message);
        }
    }
}