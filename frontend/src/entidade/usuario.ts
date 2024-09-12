export enum PermissaoUsuario {
    COMUM = 1,
    GERENTE = 2,
};

export type Usuario = {
    id: number;
    permissao: PermissaoUsuario;
};
