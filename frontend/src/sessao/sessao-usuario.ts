import { Usuario } from "../entidade/usuario";

export interface SessaoUsuario {
    registrar(usuario: Usuario): void;

    obter(): Usuario | null;

    remover(): void;
}
