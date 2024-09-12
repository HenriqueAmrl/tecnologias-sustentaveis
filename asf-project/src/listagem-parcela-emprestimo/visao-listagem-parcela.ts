import { Formatador } from "../helper/formatador";
import { Parcela, StatusParcela } from "../entidade/parcela";
import { Emprestimo } from "../entidade/emprestimo";

export class VisaoListagemParcela {

    listarParcelasEmprestimo(parcelas: Parcela[]): void {
        let html = '';
        for (const parcela of parcelas) {
            html += `
                <tr>
                    <td>${parcela.id}</td>
                    <td>${Formatador.formatarData(parcela.dataVencimento!)}</td>
                    <td>${Formatador.formatarDinheiro(parcela.valor)}</td>
                    <td>
                        ${ 
                            parcela.status == StatusParcela.STATUS_EM_ABERTO
                            ? 'Em aberto'
                            : parcela.status == StatusParcela.STATUS_EM_ATRASO 
                                ? 'Em atraso' 
                                : 'Paga'
                        }
                    </td>
                    <td>
                        ${ 
                            parcela.dataPagamento == null && parcela.nomePagador == null
                            ? `<button class="botao-pagar" data-id="${parcela.id}">Pagar</button>`
                            : Formatador.formatarData(parcela.dataPagamento!) + ' | Por: ' + parcela.nomePagador
                        }
                    </td>
                </tr>
            `;
        }

        const tabela = document.querySelector('table tbody')!;

        tabela.innerHTML = html;
    }

    listarDadosEmprestimo(dados: Emprestimo[]): void {
        const output = document.querySelector('output')!;
        let html = '';
        for (const emprestimo of dados) {
            html += `
                <p>
                    <b>Cliente:</b> ${emprestimo.cliente.nome} | <b>CPF:</b> ${Formatador.formatarCpf(emprestimo.cliente.cpf!)}  </br>
                    <b>Forma de pagamento:</b> ${emprestimo.formaPagamento.descricao} </br>
                    <b>Valor:</b> ${Formatador.formatarDinheiro(emprestimo.valor)} | <b>Valor com juros:</b> ${Formatador.formatarDinheiro(emprestimo.valorTotal!)} </br>
                </p>
            `;
        }

        output.innerHTML = html;
    }

    exibirErro( mensagem: string ): void {
        alert(mensagem);
    }

    prepararPagamento(funcao: (idParcela: number) => void) {
        document.getElementById('parcela')!.addEventListener('click', (ev) => {
            const target = (ev.target as HTMLButtonElement);
            if( !target.classList.contains('botao-pagar') )
                return;
            ev.preventDefault();
            funcao( parseInt(target.dataset.id!) );
        });
    }

    exibirSucesso(): void {
        alert('Parcela paga com sucesso!');
        location.reload();
    }
}