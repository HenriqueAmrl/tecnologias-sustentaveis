<?php

namespace Asf\Api\Repositorio;

use Asf\Api\Base\Repositorio;
use Asf\Api\Dto\Relatorio\EmprestimosPorPeriodo;
use Asf\Api\Dto\Relatorio\MetricaEmprestimo;
use Asf\Api\Dto\ParcelaListagem;
use Asf\Api\Entidade\Cliente;
use Asf\Api\Entidade\Emprestimo;
use Asf\Api\Entidade\Parcela;
use Asf\Api\Entidade\FormaPagamento;
use DateTimeInterface;
use PDO;

class RepositorioEmprestimo extends Repositorio {
    public function obterTodos(?DateTimeInterface $inicio = null, ?DateTimeInterface $fim = null): array {
        $where = '';
        $parametros = [];
        if ($inicio) {
            $where .= ' WHERE emprestimo.dataCriacao >= :inicio';
            $parametros['inicio'] = $inicio->format('Y-m-d H:i:s');
        }
        if ($fim) {
            $where .= $where ? ' AND' : ' WHERE';
            $where .= ' emprestimo.dataCriacao <= :fim';
            $parametros['fim'] = $fim->format('Y-m-d H:i:s');
        }

        $query = "SELECT 
                emprestimo.*,
                cliente.id AS cliente_id,
                cliente.nome AS cliente_nome,
                cliente.cpf AS cliente_cpf,
                cliente.dataNascimento AS cliente_dataNascimento,
                formaPagamento.id AS formaPagamento_id,
                formaPagamento.descricao AS formaPagamento_descricao,
                formaPagamento.numeroParcelas AS formaPagamento_numeroParcelas,
                formaPagamento.juros AS formaPagamento_juros
            FROM emprestimo
            JOIN cliente ON emprestimo.idCliente = cliente.id
            JOIN formaPagamento ON emprestimo.idFormaPagamento = formaPagamento.id
            {$where}
            ORDER BY dataCriacao DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($parametros);

        $emprestimos = [];
        while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $emprestimo = new Emprestimo();
            $emprestimo->id = $linha['id'];
            $emprestimo->dataCriacao = $linha['dataCriacao'];
            $emprestimo->valor = $linha['valor'];
            $emprestimo->valorTotal = $linha['valorTotal'];

            $cliente = new Cliente();
            $cliente->id = $linha['cliente_id'];
            $cliente->nome = $linha['cliente_nome'];
            $cliente->cpf = $linha['cliente_cpf'];
            $cliente->dataNascimento = $linha['cliente_dataNascimento'];
            $emprestimo->cliente = $cliente;

            $formaPagamento = new FormaPagamento();
            $formaPagamento->id = $linha['formaPagamento_id'];
            $formaPagamento->descricao = $linha['formaPagamento_descricao'];
            $formaPagamento->numeroParcelas = $linha['formaPagamento_numeroParcelas'];
            $formaPagamento->juros = $linha['formaPagamento_juros'];
            $emprestimo->formaPagamento = $formaPagamento;

            $emprestimos[] = $emprestimo;
        }

        return $emprestimos;
    }

    public function salvar(Emprestimo $emprestimo): void {
        $query = <<<SQL
            INSERT INTO emprestimo (idCliente, idFormaPagamento, dataCriacao, valor, valorTotal)
            VALUES (:idCliente, :idFormaPagamento, :dataCriacao, :valor, :valorTotal)
            SQL;
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':idCliente', $emprestimo->cliente->id);
        $stmt->bindValue(':idFormaPagamento', $emprestimo->formaPagamento->id);
        $stmt->bindValue(':dataCriacao', $emprestimo->dataCriacao);
        $stmt->bindValue(':valor', $emprestimo->valor);
        $stmt->bindValue(':valorTotal', $emprestimo->valorTotal);
        $stmt->execute();

        $emprestimo->id = (int)$this->pdo->lastInsertId();
    }

    /**
     * @param Parcela[] $parcelas
     * @return void
     */
    public function salvarParcelas(array $parcelas): void {
        $query = 'INSERT INTO parcela (idEmprestimo, numero, dataVencimento, valor) VALUES ';
        $params = [];
        foreach ($parcelas as $key => $parcela) {
            if ($key > 0) {
                $query .= ', ';
            }

            $query .= "(:idEmprestimo{$key}, :numero{$key}, :dataVencimento{$key}, :valor{$key})";
            $params["idEmprestimo{$key}"] = $parcela->emprestimo->id;
            $params["numero{$key}"] = $parcela->numero;
            $params["dataVencimento{$key}"] = $parcela->dataVencimento;
            $params["valor{$key}"] = $parcela->valor;
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
    }

    public function obterComId(int $id): array {
        $query = 'SELECT 
                emprestimo.*,
                cliente.id AS cliente_id,
                cliente.nome AS cliente_nome,
                cliente.cpf AS cliente_cpf,
                cliente.dataNascimento AS cliente_dataNascimento,
                formaPagamento.id AS formaPagamento_id,
                formaPagamento.descricao AS formaPagamento_descricao,
                formaPagamento.numeroParcelas AS formaPagamento_numeroParcelas,
                formaPagamento.juros AS formaPagamento_juros
            FROM emprestimo
            JOIN cliente ON emprestimo.idCliente = cliente.id
            JOIN formaPagamento ON emprestimo.idFormaPagamento = formaPagamento.id
            WHERE emprestimo.id = :id
            ORDER BY dataCriacao DESC';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $emprestimos = [];
        while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $emprestimo = new Emprestimo();
            $emprestimo->id = $linha['id'];
            $emprestimo->dataCriacao = $linha['dataCriacao'];
            $emprestimo->valor = $linha['valor'];
            $emprestimo->valorTotal = $linha['valorTotal'];

            $cliente = new Cliente();
            $cliente->id = $linha['cliente_id'];
            $cliente->nome = $linha['cliente_nome'];
            $cliente->cpf = $linha['cliente_cpf'];
            $cliente->dataNascimento = $linha['cliente_dataNascimento'];
            $emprestimo->cliente = $cliente;

            $formaPagamento = new FormaPagamento();
            $formaPagamento->id = $linha['formaPagamento_id'];
            $formaPagamento->descricao = $linha['formaPagamento_descricao'];
            $formaPagamento->numeroParcelas = $linha['formaPagamento_numeroParcelas'];
            $formaPagamento->juros = $linha['formaPagamento_juros'];
            $emprestimo->formaPagamento = $formaPagamento;

            $emprestimos[] = $emprestimo;
        }

        return $emprestimos;
    }

    public function obterParcelasEmprestimo(int $id): array {
        $query = 'SELECT
                    parcela.*,
                    usuario.nome AS nomePagador
                FROM parcela
                LEFT JOIN usuario ON parcela.idUsuarioPagamento = usuario.id
                WHERE parcela.idEmprestimo = :id
                ORDER BY parcela.id ASC';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $parcelas = [];
        while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $status = Parcela::STATUS_EM_ABERTO;
            if ($linha['dataPagamento']) {
                $status = Parcela::STATUS_PAGA;
            } elseif ($linha['dataVencimento'] < date('Y-m-d')) {
                $status = Parcela::STATUS_EM_ATRASO;
            }

            $parcela = new ParcelaListagem();
            $parcela->id = $linha['numero'];
            $parcela->status = $status;
            $parcela->valor = $linha['valor'];
            $parcela->dataVencimento = $linha['dataVencimento'];
            $parcela->dataPagamento = $linha['dataPagamento'];
            $parcela->nomePagador = $linha['nomePagador'];

            $parcelas[] = $parcela;
        }

        return $parcelas;
    }

    public function relatorioPorPeriodo(DateTimeInterface $inicio, DateTimeInterface $fim): EmprestimosPorPeriodo {
        $query = <<<SQL
            SELECT
                DATE(emprestimo.dataCriacao) AS data,
                SUM(emprestimo.valorTotal) AS valor
            FROM emprestimo
            WHERE emprestimo.dataCriacao BETWEEN :inicio AND :fim
            GROUP BY DATE(emprestimo.dataCriacao)
            ORDER BY data ASC
            SQL;
        
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':inicio', $inicio->format('Y-m-d H:i:s'));
        $stmt->bindValue(':fim', $fim->format('Y-m-d H:i:s'));
        $stmt->execute();

        $metricas = [];
        $valorTotal = 0;
        while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $metrica = new MetricaEmprestimo($linha['data'], $linha['valor']);
            $metricas[] = $metrica;
            $valorTotal += $metrica->valor;
        }
        $valorMedio = $metricas ? $valorTotal / count($metricas) : 0;

        return new EmprestimosPorPeriodo($metricas, $valorTotal, $valorMedio);
    }

    public function registrarPagamento(int $idEmprestimo, int $numeroParcela, int $idUsuarioPagamento): bool {
        $query = <<<SQL
            UPDATE parcela
            SET dataPagamento = :dataPagamento, idUsuarioPagamento = :idUsuarioPagamento
            WHERE idEmprestimo = :idEmprestimo AND numero = :numero
            SQL;
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':dataPagamento', date('Y-m-d H:i:s'));
        $stmt->bindValue(':idUsuarioPagamento', $idUsuarioPagamento);
        $stmt->bindValue(':idEmprestimo', $idEmprestimo);
        $stmt->bindValue(':numero', $numeroParcela);
        return $stmt->execute();
    }
}
