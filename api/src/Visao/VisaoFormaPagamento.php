<?php

namespace Asf\Api\Visao;

interface VisaoFormaPagamento {

    public function listarFormasPagamento(array $formasPagamento): void;

    public function exibirParcelas(array $parcelas): void;

}
