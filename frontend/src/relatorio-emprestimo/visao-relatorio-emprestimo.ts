import { Formatador } from "../helper/formatador";
import { RelatorioEmprestimo } from "../entidade/relatorio-emprestimo";
import { Chart, registerables } from 'chart.js';
import { Emprestimo } from "../entidade/emprestimo";
Chart.register(...registerables);

export class VisaoRelatorioEmprestimo {

    private grafico: Chart | undefined;

    prepararRelatorio(funcao: () => void) {
        const data = new Date();
        const primeiroDia = new Date(data.getFullYear(), data.getMonth(), 1);
        const ultimoDia = new Date(data.getFullYear(), data.getMonth() + 1, 0);

        const primeiroDiaDoMes = primeiroDia.toISOString().split('T')[0];
        const ultimoDiaDoMes = ultimoDia.toISOString().split('T')[0];

        (document.getElementById('dataInicial') as HTMLInputElement).value = primeiroDiaDoMes;
        (document.getElementById('dataFinal') as HTMLInputElement).value = ultimoDiaDoMes;

        document.getElementById('gerar')!.addEventListener('click', (ev) => {
            ev.preventDefault();
            funcao();
        });
    }

    prepararMostrarDados(funcao: () => void) {
        document.getElementById('mostrarDados')!.addEventListener('click', (ev) => {
            ev.preventDefault();
            funcao();
        });
    }

    obterDadosRelatorio() : {dataInicial: string, dataFinal: string} {
        return {
            dataInicial: (document.getElementById('dataInicial') as HTMLInputElement).value,
            dataFinal: (document.getElementById('dataFinal') as HTMLInputElement).value
        };
    }

    exibirErro(mensagem: string): void {
        document.getElementById('erro')!.innerText = mensagem;
    }

    renderizarGrafico(dadosRelatorio: RelatorioEmprestimo): void {
        if (this.grafico) {
            this.grafico.destroy();
        }

        const ctx = document.getElementById('grafico') as HTMLCanvasElement;
        this.grafico = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dadosRelatorio.metricas.map( metrica => Formatador.formatarData( new Date (metrica.data) ) ),
                datasets: [{
                    label: '# Empréstimos por período',
                    data: dadosRelatorio.metricas.map( metrica => metrica.valor ),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    visualizarDadosEmprestimo(dadosRelatorio: RelatorioEmprestimo): void {
        const output = document.querySelector('output')!;
        let html = '';
        html += `
            <p>
                </br> <b>Total do Período (R$):</b> ${Formatador.formatarDinheiro(dadosRelatorio.valorTotal!)}  | 
                <b>Média do Período (R$):</b> ${Formatador.formatarDinheiro(dadosRelatorio.valorMedio!)} </br>
            </p>
        `;
        output.innerHTML = html;
    }

    exibirDados(dadosRelatorio: Emprestimo[]): void {
        let sequencial = 1;

        const elemento = document.getElementById('dados')!;
        const tabela = document.createElement('table');
        tabela.innerHTML = `
            <thead>
                <tr>
                    <th>#</th>
                    <th>Data </th>
                    <th>Total (R$)</th>
                </tr>
            </thead>
        `;
        const tbody = document.createElement('tbody');
        dadosRelatorio.forEach((emprestimo: Emprestimo) => {
            const linha = document.createElement('tr');
            linha.innerHTML = `
                <td>${sequencial}</td>
                <td>${Formatador.formatarDinheiro(emprestimo.valorTotal!)}</td>
                <td>${Formatador.formatarData(emprestimo.dataCriacao!)}</td>
            `;
            sequencial++;
            tbody.appendChild(linha);
        });
        tabela.appendChild(tbody);
        elemento.appendChild(tabela);
    }

}