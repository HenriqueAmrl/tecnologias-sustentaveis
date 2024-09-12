<?php

namespace Asf\Api\Visao;

use Asf\Api\Base\VisaoEmApi;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class VisaoFormaPagamentoEmApi extends VisaoEmApi implements VisaoFormaPagamento {
    public Request $req;
    public Response $res;

    public function __construct(Request $req, Response $res) {
        $this->req = $req;
        $this->res = $res;
    }

    public function listarFormasPagamento(array $formasPagamento): void {
        $this->res = $this->sucesso($this->res, $formasPagamento);
    }

    public function exibirParcelas(array $parcelas): void{
        $this->res =  $this->sucesso($this->res, $parcelas);
    }
}
