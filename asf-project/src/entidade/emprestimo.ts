import { Cliente } from "./cliente";
import { FormaPagamento } from "./forma-pagamento";

export type Emprestimo = {
    id?: number;
    dataCriacao?: Date;
    cliente: Partial<Cliente>;
    formaPagamento: Partial<FormaPagamento>;
    valor: number;
    valorTotal?: number;
}