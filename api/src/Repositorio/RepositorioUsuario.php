<?php

namespace Asf\Api\Repositorio;

use Asf\Api\Base\Repositorio;
use Asf\Api\Entidade\Usuario;
use PDO;

class RepositorioUsuario extends Repositorio {
    public function obterPorLogin(string $login): ?Usuario {
        $query = 'SELECT * from usuario WHERE nome = :nome OR email = :email';
        $stmt = $this->pdo->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Usuario::class);
        $stmt->execute(['nome' => $login, 'email' => $login]);

        return $stmt->fetch() ?: null;
    }
}
