<?php

namespace Asf\Api\Base;

use PDO;

abstract class Repositorio {
    protected PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
}
