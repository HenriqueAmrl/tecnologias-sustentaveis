<?php

namespace Asf\Api\Tests\Helper;

use PDO;

class BancoTeste {
    private const DATABASE = 'asf';

    private PDO $pdo;

    public function __construct() {
        $hostBd = getenv('MYSQL_HOST') ?: 'localhost';
        $this->pdo = new PDO("mysql:host={$hostBd};port=3306;dbname=" . self::DATABASE, 'root');
    }

    public function recriarBanco(): void {
        $this->pdo->exec('DROP DATABASE IF EXISTS ' . self::DATABASE);
        $this->pdo->exec($this->obterArquivoSql());
        $this->pdo->exec($this->obterArquivoSeeder());
    }

    private function obterArquivoSql(): string {
        return file_get_contents(__DIR__ . '/../../docs/bd/asf.sql');
    }

    private function obterArquivoSeeder(): string {
        return file_get_contents(__DIR__ . '/../../docs/bd/seeder.sql');
    }

    public function obterPDO(): PDO {
        return $this->pdo;
    }
}
