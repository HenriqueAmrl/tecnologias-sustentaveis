export enum StatusParcela {
    STATUS_EM_ABERTO = 1,
    STATUS_EM_ATRASO = 2,
    STATUS_PAGA = 3
}

export type Parcela = {
    id: number;
    dataVencimento: Date;
    valor: number;
    status: StatusParcela;
    dataPagamento: Date;
    nomePagador: string;
}