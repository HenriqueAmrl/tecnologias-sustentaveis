import { RepositorioAutenticacao } from "../repositorio/repositorio-autenticacao";
import { PermissaoUsuario } from "../entidade/usuario";
import { SessaoUsuario } from "../sessao/sessao-usuario";
import { SessaoUsuarioEmSS } from "../sessao/sessao-usuario-em-ss";
import { VisaoAutenticacao } from "./visao-autenticacao";

export class ControladoraAutenticacao {
    visao: VisaoAutenticacao;
    sessao: SessaoUsuario;

    constructor() {
        this.visao = new VisaoAutenticacao();
        this.sessao = new SessaoUsuarioEmSS();
    }

    configurarLogin() {
        this.visao.prepararLogin(this.realizarLogin);
    }

    realizarLogin = async () => {
        const credenciais = this.visao.obterCredenciais();
        if (credenciais.login === '' || credenciais.senha === '') {
            this.visao.exibirErroLogin('Preencha os campos de login e senha');
            return;
        }

        const repositorio = new RepositorioAutenticacao();
        try {
            const usuario = await repositorio.login(credenciais);
            this.sessao.registrar(usuario);
            this.visao.exibirInicio();
        } catch (error) {
            this.visao.exibirErroLogin((error as Error).message);
        }
    }

    configurarLogout() {
        this.visao.prepararLogout(this.realizarLogout);
    }

    realizarLogout = async () => {
        const repositorio = new RepositorioAutenticacao();
        try {
            await repositorio.logout();
            this.sessao.remover();
            this.visao.exibirLogin();
        } catch (error) {
            this.visao.exibirErroLogout((error as Error).message);
        }
    }

    validarLogin(): void {
        if (this.sessao.obter() === null) {
            this.visao.exibirLogin();
        }
    }

    validarPermissao(): void {
        const usuario = this.sessao.obter();
        if (usuario?.permissao != PermissaoUsuario.GERENTE) {
            this.visao.ocultarBotaoRelatorio();
        }
    }
}
