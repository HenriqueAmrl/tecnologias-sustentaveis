import { Cliente } from "../entidade/cliente";
import { FormaPagamento } from "../entidade/forma-pagamento";
import { SimulacaoParcela } from "../entidade/simulacao-parcela";
import { Formatador } from "../helper/formatador";

export class VisaoCadastroEmprestimo {

    prepararPesquisa(funcao: () => void) {
        document.getElementById('pesquisa')!.addEventListener('blur', funcao);
    }

    obterCpf(): string {
        return (document.getElementById('pesquisa') as HTMLInputElement)!.value;
    }

    exibirCliente( texto: string ): void {
        document.getElementById( 'cliente' )!.innerHTML = texto;
    }

    mostrarResultado( cliente: Cliente|null ) {
        if( !cliente ) {
            this.exibirCliente( 'Cliente não encontrado.' );
            return;
        }
        const idade: number = this.calcularIdade(cliente.dataNascimento!);
        const limite = cliente.limite!;
        const percentualUtilizado = limite.utilizado / (limite.disponivel + limite.utilizado) * 100;
        const clienteExibicao: string =  `${cliente?.nome}, ${idade} anos<br>Limite disponível: ${Formatador.formatarDinheiro(limite.disponivel)} | Utilizado: ${Formatador.formatarDinheiro(limite.utilizado)} (${percentualUtilizado.toFixed(0)}%)`;
        this.exibirCliente( clienteExibicao );
    }

    calcularIdade( dataNascimento: string ): number {
        // Extrai as informações da data para manipular ao comparar com o dia atual
        const [anoNascimento, mesNascimento, diaNascimento] = dataNascimento.split("-").map(Number);

        // Obtém a data atual e separa dia, mês e ano para comparar com o aniversário
        const dataAtual = new Date();
        const anoAtual = dataAtual.getUTCFullYear();
        const mesAtual = dataAtual.getUTCMonth() + 1;
        const diaAtual = dataAtual.getUTCDate();

        // Calcula a idade 
        let idade = anoAtual - anoNascimento;

        // Verifica se o aniversário já passou neste ano, caso tenha passado, subtrai um da idade obtida pela diferença dos anos
        if (mesAtual < mesNascimento || (mesAtual === mesNascimento && diaAtual < diaNascimento)) {
            idade--;
        }

        return idade;
    }

    obterValorEmprestimo(): number|null {
        const valor = parseFloat((document.getElementById('valor') as HTMLInputElement).value);
        if( isNaN(valor) ) {
            return null;
        }

        return valor;
    }

    obterIdPagamentoSelecionado(): number|null {
        const valor = (document.getElementById('pagamento') as HTMLSelectElement).value;
        if( valor === '' ) {
            return null;
        }
        
        return parseInt(valor);
    }

    exibirErro( mensagem: string ): void {
        document.getElementById('erro')!.innerText = mensagem;
    }

    desabilitarEnvio(): void {
        (document.getElementById('realizarEmprestimo') as HTMLButtonElement).disabled = true;
    }

    habilitarEnvio(): void {
        (document.getElementById('realizarEmprestimo') as HTMLButtonElement).disabled = false;
    }

    exibirFormasPagamento(formasPagamento: FormaPagamento[]): void {
        const select = document.getElementById('pagamento') as HTMLSelectElement;
        formasPagamento.forEach((formaPagamento: FormaPagamento) => {
            const option = document.createElement('option');
            option.value = formaPagamento.id.toString();
            option.text = formaPagamento.descricao!;
            select.add(option);
        });
    }

    prepararExibicaoParcelas(callback: () => void): void {
        const valorInput = document.getElementById('valor')!;
        valorInput.addEventListener('keyup', callback);
        valorInput.addEventListener('blur', callback);
        
        document.getElementById('pagamento')!.addEventListener('change', callback);
    }

    exibirParcelas(parcelas: SimulacaoParcela[]): void {
        const elemento = document.getElementById('parcelas')!;
        elemento.innerHTML = '<h4>Parcelas</h4>';
        // exiba os dados em uma tabela
        const tabela = document.createElement('table');
        tabela.innerHTML = `
            <thead>
                <tr>
                    <th>Numero</th>
                    <th>Vencimento (data)</th>
                    <th>Valor (R$)</th>
                </tr>
            </thead>
        `;
        const tbody = document.createElement('tbody');
        parcelas.forEach((parcela: SimulacaoParcela) => {
            const linha = document.createElement('tr');
            linha.innerHTML = `
                <td>${parcela.numero}</td>
                <td>${Formatador.formatarData(parcela.dataVencimento)}</td>
                <td>${Formatador.formatarDinheiro(parcela.valor)}</td>
            `;
            tbody.appendChild(linha);
        });
        tabela.appendChild(tbody);
        elemento.appendChild(tabela);
    }

    ocultarParcelas(): void {
        const elemento = document.getElementById('parcelas')!;
        elemento.innerHTML = '';
    }

    prepararLimpezaFormulario(): void {
        document.getElementById('limpar')!.addEventListener('click', () => {
            (document.getElementById('pesquisa') as HTMLInputElement).value = '';
            (document.getElementById('valor') as HTMLInputElement).value = '';
            this.exibirCliente('');
            this.exibirErro('');
            this.desabilitarEnvio();
            this.ocultarParcelas();
        });
    }

    prepararCadastroEmprestimo(callback: () => void): void {
        document.getElementById('realizarEmprestimo')!.addEventListener('click', callback);
    }

    exibirSucesso(): void {
        alert('Empréstimo realizado com sucesso!');
        location.href = '/listar-emprestimos';
    }
}