import { RepositorioCliente } from "../repositorio/repositorio-cliente";
import { Cliente } from "../entidade/cliente";
import { VisaoCadastroCliente } from "./visao-cadastro-cliente";

export class ControladoraCadastroCliente {
    private visao: VisaoCadastroCliente;

    constructor() {
        this.visao = new VisaoCadastroCliente();
    }

    configurarCadastroCliente() {
        this.visao.prepararCadastro(this.cadastrarCliente);
    }

    cadastrarCliente = async () => {
        const dados = this.visao.obterDadosCadastro();
        if (!this.validarDados(dados)) {
            return;
        }
        const repositorio = new RepositorioCliente();
        try {
            await repositorio.cadastrar(dados);
            this.visao.exibirSucesso();
        } catch (error) {
            this.visao.exibirErro((error as Error).message);
        }
    }

    private validarDados(dados: Cliente): boolean {
        let erros = [];
        const validacoes = {
            nome: {
                descricao: 'nome',
                min: 5,
                max: 100
            },
            dataNascimento: {
                descricao: 'data de nascimento',
                min: 10,
                max: 10
            },
            cpf: {
                descricao: 'CPF',
                min: 11,
                max: 11
            },
            telefone: {
                descricao: 'telefone',
                min: 10,
                max: 15
            },
            email: {
                descricao: 'e-mail',
                min: 5,
                max: 100
            },
            endereco: {
                descricao: 'endereço',
                min: 5,
                max: 100
            }
        };
        for (const [campo, validacao] of Object.entries(validacoes)) {
            const dado = (dados as any)[campo]!;
            if ('max' in validacao && dado.length > validacao.max) {
                erros.push(`O campo ${validacao.descricao} deve ter no máximo ${validacao.max} caracteres`);
            }
            if ('min' in validacao && dado.length < validacao.min) {
                erros.push(`O campo ${validacao.descricao} deve ter no mínimo ${validacao.min} caracteres`);
            }
        }

        const dataNascimento = new Date(dados.dataNascimento);
        const dataAtual = new Date();
        if (dados.dataNascimento && dataNascimento >= dataAtual) {
            erros.push('Informe uma data de nascimento válida');
        }

        if (erros.length > 0) {
            this.visao.exibirErro(erros.join('\n'));
            return false;
        }

        return true;
    }
}
