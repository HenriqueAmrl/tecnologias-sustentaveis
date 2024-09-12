<?php

namespace Asf\Api\Repositorio;

use Asf\Api\Base\Repositorio;
use Asf\Api\Entidade\Cliente;
use Asf\Api\Controladora\ControladoraEmprestimo;
use Asf\Api\Dto\LimiteCredito;
use PDO;

class RepositorioCliente extends Repositorio {
    public function obterTodos(array $filtros): array {
        $query = 'SELECT cliente.*, SUM(parcela.valor) as utilizado FROM cliente
                    LEFT JOIN emprestimo ON cliente.id = emprestimo.idCliente
                    LEFT JOIN parcela ON emprestimo.id = parcela.idEmprestimo AND parcela.dataPagamento is NULL
                ';
        if (isset($filtros['cpf'])) {
            $query .= ' WHERE cpf = :cpf';
        }
        $query .= ' GROUP BY cliente.id';
        $stmt = $this->pdo->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Cliente::class);
        $stmt->execute($filtros);

        $clientes = [];
        while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $cliente = new Cliente();
            $cliente->id = $linha['id'];
            $cliente->nome = $linha['nome'];
            $cliente->cpf = $linha['cpf'];
            $cliente->dataNascimento = $linha['dataNascimento'];
            $cliente->telefone = $linha['telefone'];
            $cliente->email = $linha['email'];
            $cliente->endereco = $linha['endereco'];

            $limite = new LimiteCredito();
            $limite->utilizado = $linha['utilizado'] ?? 0;
            $limite->disponivel = ControladoraEmprestimo::VALOR_MAXIMO_EMPRESTIMO - $limite->utilizado;

            $cliente->limite = $limite;

            $clientes[] = $cliente;
        }

        return $clientes;
    }

    public function obterComId($id): ?Cliente {
        $query = 'SELECT * from cliente WHERE id = :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Cliente::class);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public function salvar(Cliente $cliente): void {
        $query = <<<SQL
            INSERT INTO cliente (id, nome, cpf, dataNascimento, telefone, email, endereco)
            VALUES (:id, :nome, :cpf, :dataNascimento, :telefone, :email, :endereco)
            SQL;
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $cliente->id);
        $stmt->bindValue(':nome', $cliente->nome);
        $stmt->bindValue(':cpf', $cliente->cpf);
        $stmt->bindValue(':dataNascimento', $cliente->dataNascimento);
        $stmt->bindValue(':telefone', $cliente->telefone);
        $stmt->bindValue(':email', $cliente->email);
        $stmt->bindValue(':endereco', $cliente->endereco);
        $stmt->execute();

        $cliente->id = (int)$this->pdo->lastInsertId();
    }

    public function obterComCpf(string $cpf): ?Cliente {
        $query = 'SELECT * from cliente WHERE cpf = :cpf';
        $stmt = $this->pdo->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Cliente::class);
        $stmt->execute(['cpf' => $cpf]);

        return $stmt->fetch() ?: null;
    }

}
