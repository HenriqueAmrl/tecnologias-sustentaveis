import { Emprestimo } from "../entidade/emprestimo";
import { Formatador } from "../helper/formatador";

export class VisaoListagemEmprestimo {
    listarEmprestimos(emprestimos: Emprestimo[]) {
        let html = '';
        for (const emprestimo of emprestimos) {
            html += `
                <tr>
                    <td>${Formatador.formatarDataHora(emprestimo.dataCriacao!)}</td>
                    <td>${emprestimo.cliente.nome}</td>
                    <td>${Formatador.formatarCpf(emprestimo.cliente.cpf!)}</td>
                    <td>${Formatador.formatarDinheiro(emprestimo.valor)}</td>
                    <td>${emprestimo.formaPagamento.descricao}</td>
                    <td>${Formatador.formatarDinheiro(emprestimo.valorTotal!)}</td>
                    <td><a href="listar-parcelas.html?id=${emprestimo.id}" class="button button-clear" title="Ver mais">➕</a></td>
                </tr>
            `;
        }

        const tabela = document.querySelector('table tbody')!;
        tabela.innerHTML = html;
    }
    
    exibirErro(mensagem: string) {
        alert(mensagem);
    }
}