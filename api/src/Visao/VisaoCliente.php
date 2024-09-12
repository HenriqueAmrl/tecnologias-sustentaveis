<?php

namespace Asf\Api\Visao;

use Asf\Api\Entidade\Cliente;

interface VisaoCliente {
    public function obterFiltros(): array;

    public function listarClientes(array $clientes): void;

    public function exibirSucessoClienteCadastrado(Cliente $cliente): void;

    public function exibirErro(string $mensagem): void;
}
