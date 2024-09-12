<?php

namespace Asf\Api\Repositorio;

use Asf\Api\Base\Repositorio;
use Asf\Api\Entidade\FormaPagamento;
use PDO;

class RepositorioFormaPagamento extends Repositorio {
    public function obterTodos(): array {
        $query = 'SELECT * from formaPagamento';
        $stmt = $this->pdo->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, FormaPagamento::class);
        $stmt->execute();

        return $stmt->fetchAll() ?: [];
    }
    public function obterComId($id): ?FormaPagamento {
        $query = 'SELECT * from formaPagamento WHERE id = :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, FormaPagamento::class);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }
}
