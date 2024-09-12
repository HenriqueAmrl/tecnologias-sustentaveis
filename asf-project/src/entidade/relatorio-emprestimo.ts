import { MetricaEmprestimo } from "./metrica-emprestimo";

export type RelatorioEmprestimo = {
    id?: number;
    dataInicial: string;
    dataFinal: string;
    metricas: MetricaEmprestimo [];
    valorMedio?: number;
    valorTotal?: number;
};
