<?php

use Asf\Api\Controladora\ControladoraAutenticacao;
use Asf\Api\Controladora\ControladoraCliente;
use Asf\Api\Controladora\ControladoraEmprestimo;
use Asf\Api\Controladora\ControladoraFormaPagamento;
use Asf\Api\Controladora\ControladoraRelatorio;
use Asf\Api\Entidade\Usuario;
use Asf\Api\Infra\Middleware\MiddlewareAutenticacao;
use Asf\Api\Infra\Middleware\MiddlewareCors;
use Asf\Api\Repositorio\RepositorioCliente;
use Asf\Api\Repositorio\RepositorioEmprestimo;
use Asf\Api\Repositorio\RepositorioFormaPagamento;
use Asf\Api\Repositorio\RepositorioUsuario;
use Asf\Api\Servico\ServicoAutenticacao;
use Asf\Api\Servico\ServicoEmprestimo;
use Asf\Api\Servico\ServicoFormaPagamento;
use Asf\Api\Sessao\SessaoUsuarioEmArquivo;
use Asf\Api\Visao\VisaoAutenticacaoEmApi;
use Asf\Api\Visao\VisaoClienteEmApi;
use Asf\Api\Visao\VisaoEmprestimoEmApi;
use Asf\Api\Visao\VisaoFormaPagamentoEmApi;
use Asf\Api\Visao\VisaoRelatorioEmApi;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, false, false);
$app->addBodyParsingMiddleware();

// Conexão com o banco de dados
$hostBd = getenv('MYSQL_HOST') ?: 'localhost';
$pdo = $pdo = new PDO("mysql:host={$hostBd};port=3306;dbname=asf", 'root', null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$repositorioCliente = new RepositorioCliente($pdo);
$repositorioFormaPagamento = new RepositorioFormaPagamento($pdo);
$repositorioEmprestimo = new RepositorioEmprestimo($pdo);
$sessaoUsuario = new SessaoUsuarioEmArquivo();
$repositorioUsuario = new RepositorioUsuario($pdo);
$servicoAutenticacao = new ServicoAutenticacao($repositorioUsuario, $sessaoUsuario);

// Clientes
$app->group('/clientes', function(Group $group) use($repositorioCliente) {
    $group->get('', function(Request $req, Response $res) use($repositorioCliente) {
        $visao = new VisaoClienteEmApi($req, $res);
        $controladoraCliente = new ControladoraCliente($repositorioCliente, $visao);
        $controladoraCliente->obterTodos();
        return $visao->res;
    });

    $group->post('', function(Request $req, Response $res) use($repositorioCliente) {
        $visao = new VisaoClienteEmApi($req, $res);
        $controladoraCliente = new ControladoraCliente($repositorioCliente, $visao);
        $controladoraCliente->cadastrar($req->getParsedBody());
        return $visao->res;
    });
})->add(new MiddlewareAutenticacao($sessaoUsuario));

// Formas de Pagamento
$app->group('/formas-pagamento', function(Group $group) use($repositorioFormaPagamento) {
    $servico = new ServicoFormaPagamento();
    $group->get('', function(Request $req, Response $res) use($repositorioFormaPagamento, $servico) {
        $visao = new VisaoFormaPagamentoEmApi($req, $res);
        $controladoraFormaPagamento = new ControladoraFormaPagamento($repositorioFormaPagamento, $visao, $servico);
        $controladoraFormaPagamento->obterTodos();
        return $visao->res;
    });

    $group->post('/{id}/simular', function(Request $req, Response $res, array $args) use($repositorioFormaPagamento, $servico) {
        $visao = new VisaoFormaPagamentoEmApi($req, $res);
        $controladoraFormaPagamento = new ControladoraFormaPagamento($repositorioFormaPagamento, $visao, $servico);
        $controladoraFormaPagamento->simularParcelas($args['id'], $req->getParsedBody());
        return $visao->res;
    });
})->add(new MiddlewareAutenticacao($sessaoUsuario));

// Empréstimos
$app->group('/emprestimos', function(Group $group) use($repositorioEmprestimo, $repositorioCliente, $repositorioFormaPagamento, $sessaoUsuario) {
    $servicoEmprestimo = new ServicoEmprestimo($repositorioEmprestimo, $sessaoUsuario);

    $group->get('', function(Request $req, Response $res) use($repositorioEmprestimo, $repositorioCliente, $repositorioFormaPagamento, $servicoEmprestimo) {
        $visao = new VisaoEmprestimoEmApi($req, $res);
        $controladoraEmprestimo = new ControladoraEmprestimo($repositorioEmprestimo, $repositorioCliente, $repositorioFormaPagamento, $visao, $servicoEmprestimo);
        $controladoraEmprestimo->obterTodos();

        return $visao->res;
    });

    $group->post('', function(Request $req, Response $res) use($repositorioEmprestimo, $repositorioCliente, $repositorioFormaPagamento, $servicoEmprestimo) {
        $visao = new VisaoEmprestimoEmApi($req, $res);
        $controladoraEmprestimo = new ControladoraEmprestimo($repositorioEmprestimo, $repositorioCliente, $repositorioFormaPagamento, $visao, $servicoEmprestimo);
        $controladoraEmprestimo->cadastrar($req->getParsedBody());

        return $visao->res;
    });

    $group->get('/{id}', function(Request $req, Response $res, array $args) use($repositorioEmprestimo, $repositorioCliente, $repositorioFormaPagamento, $servicoEmprestimo) {
        $visao = new VisaoEmprestimoEmApi($req, $res);
        $controladoraEmprestimo = new ControladoraEmprestimo($repositorioEmprestimo, $repositorioCliente, $repositorioFormaPagamento, $visao, $servicoEmprestimo);
        $controladoraEmprestimo->obterComId($args['id']);
        return $visao->res;
    });

    $group->get('/{id}/parcelas', function(Request $req, Response $res, array $args) use($repositorioEmprestimo, $repositorioCliente, $repositorioFormaPagamento, $servicoEmprestimo) {
        $visao = new VisaoEmprestimoEmApi($req, $res);
        $controladoraEmprestimo = new ControladoraEmprestimo($repositorioEmprestimo, $repositorioCliente, $repositorioFormaPagamento, $visao, $servicoEmprestimo);
        $controladoraEmprestimo->obterParcelasDoEmprestimo($args['id']);
        return $visao->res;
    });

    $group->post('/{idEmprestimo}/parcelas/{numeroParcela}/pagar', function(Request $req, Response $res, array $args) use($repositorioEmprestimo, $repositorioCliente, $repositorioFormaPagamento, $servicoEmprestimo) {
        $visao = new VisaoEmprestimoEmApi($req, $res);
        $controladoraEmprestimo = new ControladoraEmprestimo($repositorioEmprestimo, $repositorioCliente, $repositorioFormaPagamento, $visao, $servicoEmprestimo);
        $controladoraEmprestimo->pagarParcela($args['idEmprestimo'], $args['numeroParcela']);
        return $visao->res;
    });
})->add(new MiddlewareAutenticacao($sessaoUsuario));

// Login
$app->group('/autenticacao', function(Group $group) use($servicoAutenticacao, $sessaoUsuario) {
    $group->post('/login', function(Request $req, Response $res) use($servicoAutenticacao) {
        $visao = new VisaoAutenticacaoEmApi($req, $res);
        $controladora = new ControladoraAutenticacao($visao, $servicoAutenticacao);
        $controladora->efetuarLogin();

        return $visao->res;
    });

    $group->post('/logout', function(Request $req, Response $res) use($servicoAutenticacao) {
        $visao = new VisaoAutenticacaoEmApi($req, $res);
        $controladora = new ControladoraAutenticacao($visao, $servicoAutenticacao);
        $controladora->efetuarLogout();

        return $visao->res;
    })->add(new MiddlewareAutenticacao($sessaoUsuario));
});

// Relatórios
$app->group('/relatorios', function(Group $group) use($repositorioEmprestimo) {
    $group->get('/emprestimos-por-periodo', function(Request $req, Response $res) use($repositorioEmprestimo) {
        $visao = new VisaoRelatorioEmApi($req, $res);
        $controladora = new ControladoraRelatorio($repositorioEmprestimo, $visao);
        $controladora->emprestimosPorPeriodo();

        return $visao->res;
    });
})->add(new MiddlewareAutenticacao($sessaoUsuario, Usuario::PERMISSAO_GERENTE));

// CORS
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->addMiddleware(new MiddlewareCors());

$app->run();
