<?php

namespace Asf\Api\Base;

use Slim\Psr7\Response;

abstract class VisaoEmApi {
    protected function sucesso(Response $res, $dados, int $codigo = 200): Response {
        $res->getBody()->write(json_encode($dados));
        $res = $res->withStatus($codigo);
        $res = $res->withHeader('content-type', 'application/json');

        return $res;
    }

    protected function erro(Response $res, string $mensagem, int $codigo = 400): Response {
        $res->getBody()->write(json_encode(['mensagem' => $mensagem]));
        $res = $res->withStatus($codigo);
        $res = $res->withHeader('content-type', 'application/json');

        return $res;
    }
}
