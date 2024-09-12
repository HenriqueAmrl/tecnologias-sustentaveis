import { Credenciais } from "../entidade/credenciais";

export class VisaoAutenticacao {
    prepararLogin(funcao: () => void) {
        document.getElementById('form-login')!.addEventListener('submit', (event) => {
            event.preventDefault();
            funcao();
        });
    }

    prepararLogout(funcao: () => void) {
        document.getElementById('logout')!.addEventListener('click', funcao);
    }

    obterCredenciais(): Credenciais {
        const login = (document.getElementById('login') as HTMLInputElement).value;
        const senha = (document.getElementById('senha') as HTMLInputElement).value;
        return { login, senha };
    }

    exibirErroLogin(mensagem: string): void {
        document.getElementById('erro')!.innerText = mensagem;
    }

    exibirErroLogout(mensagem: string): void {
        alert(mensagem);
    }

    exibirInicio(): void {
        window.location.href = '/';
    }

    exibirLogin(): void {
        window.location.href = '/login';
    }

    ocultarBotaoRelatorio(): void {
        document.getElementById('relatorio')?.remove();
    }
}
