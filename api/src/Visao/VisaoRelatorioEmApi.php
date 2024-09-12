<?php

namespace Asf\Api\Visao;

use Asf\Api\Base\VisaoEmApi;
use Asf\Api\Dto\Relatorio\EmprestimosPorPeriodo;
use DateTime;
use DateTimeInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class VisaoRelatorioEmApi extends VisaoEmApi implements VisaoRelatorio {
    public Request $req;
    public Response $res;

    public function __construct(Request $req, Response $res) {
        $this->req = $req;
        $this->res = $res;
    }

    public function obterDataInicial(): ?DateTimeInterface {
        $data = $this->req->getQueryParams()['dataInicial'] ?? null;
        $dateTime = DateTime::createFromFormat('Y-m-d', $data);
        if ($dateTime === false) {
            return null;
        }

        return $dateTime->setTime(0, 0, 0);
    }

    public function obterDataFinal(): ?DateTimeInterface {
        $data = $this->req->getQueryParams()['dataFinal'] ?? null;
        $dateTime = DateTime::createFromFormat('Y-m-d', $data);
        if ($dateTime === false) {
            return null;
        }

        return $dateTime->setTime(23, 59, 59);
    }

    public function exibirRelatorioEmprestimosPorPeriodo(EmprestimosPorPeriodo $dados): void {
        $this->res = $this->sucesso($this->res, $dados);
    }

    public function exibirErro(string $mensagem): void {
        $this->res = $this->erro($this->res, $mensagem);
    }
}
