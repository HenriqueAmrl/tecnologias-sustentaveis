type LimiteCredito = {
    utilizado: number;
    disponivel: number;
}

export type Cliente = {
    id?: number;
    nome: string;
    cpf: string;
    dataNascimento: string;
    telefone?: string;
    email?: string;
    endereco?: string;
    limite?: LimiteCredito;
};
