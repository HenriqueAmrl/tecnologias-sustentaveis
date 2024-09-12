import { Cliente } from "../entidade/cliente";

export class VisaoCadastroCliente {
    prepararCadastro(funcao: () => void) {
        document.getElementById('form-cliente')!.addEventListener('submit', (ev) => {
            ev.preventDefault();
            funcao();
        });
    }

    obterDadosCadastro(): Cliente {
        return {
            nome: (document.getElementById('nome') as HTMLInputElement).value,
            cpf: (document.getElementById('cpf') as HTMLInputElement).value,
            dataNascimento: (document.getElementById('dataNascimento') as HTMLInputElement).value,
            telefone: (document.getElementById('telefone') as HTMLInputElement).value,
            email: (document.getElementById('email') as HTMLInputElement).value,
            endereco: (document.getElementById('endereco') as HTMLInputElement).value
        };
    }

    exibirErro(mensagem: string): void {
        document.getElementById('erro')!.innerText = mensagem;
    }

    exibirSucesso(): void {
        alert('Cliente cadastrado com sucesso!');
        location.href = '/index';
    }
}