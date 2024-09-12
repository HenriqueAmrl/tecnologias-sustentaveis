import { Usuario } from "../entidade/usuario";
import { SessaoUsuario } from "./sessao-usuario";

export class SessaoUsuarioEmSS implements SessaoUsuario {
    registrar(usuario: Usuario): void {
        sessionStorage.setItem('usuario', JSON.stringify(usuario));
    }

    obter(): Usuario | null {
        const usuario = sessionStorage.getItem('usuario');
        if (usuario) {
            return JSON.parse(usuario);
        }
        return null;
    }

    remover(): void {
        sessionStorage.removeItem('usuario');
    };
}
