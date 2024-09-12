<?php

namespace Asf\Api\Visao;

use Asf\Api\Base\VisaoEmApi;
use Asf\Api\Entidade\Cliente;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class VisaoClienteEmApi extends VisaoEmApi implements VisaoCliente {
    public Request $req;
    public Response $res;

    public function __construct(Request $req, Response $res) {
        $this->req = $req;
        $this->res = $res;
    }

    public function obterFiltros(): array {
        $filtros = [];
        $queryParams = $this->req->getQueryParams();
        if (array_key_exists('cpf', $queryParams)) {
            $filtros['cpf'] = $queryParams['cpf'];
        }

        return $filtros;
    }

    public function listarClientes(array $clientes): void {
        $this->res = $this->sucesso($this->res, $clientes);
    }

    public function exibirSucessoClienteCadastrado(Cliente $cliente): void {
        $this->res = $this->sucesso($this->res, ['id' => $cliente->id], 201);
    }

    public function exibirErro(string $mensagem): void {
        $this->res = $this->erro($this->res, $mensagem);
    }
}
