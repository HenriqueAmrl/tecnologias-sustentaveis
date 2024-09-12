import { pesquisarCliente } from "./pesquisa";
import { expect } from "@playwright/test";

export async function solicitarEmprestimoComValorCorreto( page, valor, mensagemEsperada ) {
    // Dado que estou na tela de solicitação de empréstimos e eu pesquiso um cliente existente
    await pesquisarCliente( page, '68397724621',  'Carlos Henrique do Amaral Reis' );
    // Quando eu informo o valor para solicitar um empréstimo
    await page.fill( '#valor', valor );
    // E eu seleciono para escolher a forma de pagamento
    await page.locator('#pagamento').selectOption('Parcelado 3x');
    // Então eu vejo as parcelas do empréstimo listado na tela baseado no valor informado
    await expect(
        page.locator( '#parcelas' )
    ).toContainText( 'Parcelas' );
    // Então eu clico para realizar um empréstimo
    await page.click( '#realizarEmprestimo' );

    await new Promise(resolve => setTimeout(resolve, 2000));
    // E aguardo o alerta aparecer
    await page.once( 'dialog' , async (alert) => {
        await expect(  alert.message() ).toContain( mensagemEsperada );
    });
}

export async function solicitarEmprestimoComValorIncorreto( page, valor, mensagemEsperada ) {
    // Dado que estou na tela de solicitação de empréstimos e eu pesquiso um cliente existente
    await pesquisarCliente( page, '95201479774',  'Maria Eduarda Hottz' );
    // Quando eu informo o valor para solicitar um empréstimo
    await page.fill( '#valor', valor );
    // E eu seleciono para escolher a forma de pagamento
    await page.locator('#pagamento').selectOption('Parcelado 6x');
    // Então eu vejo a mensagem de erro esperada
    await expect(
        page.locator( '#erro' )
    ).toContainText( mensagemEsperada );
}

export async function solicitarEmprestimoSemFormaDePagamento( page, valor, mensagemEsperada ) {
    // Dado que estou na tela de solicitação de empréstimos e eu pesquiso um cliente existente
    await pesquisarCliente( page, '95201479774',  'Maria Eduarda Hottz' );
    // Quando eu informo o valor para solicitar um empréstimo
    await page.fill( '#valor', valor );
    // E eu seleciono para escolher a forma de pagamento
    await page.click('#pagamento');
    // Então eu vejo a mensagem de erro esperada
    await expect(
        page.locator( '#erro' )
    ).toContainText( mensagemEsperada );
}

export async function solicitarEmprestimoSemCliente( page, valor, mensagemEsperada ) {
    // Dado que estou na tela de solicitação de empréstimos e eu pesquiso um cliente existente
    await page.goto( 'http://localhost:5173/cadastrar-emprestimo.html' );
    // Quando eu informo o valor para solicitar um empréstimo
    await page.fill( '#valor', valor );
    // E eu seleciono para escolher a forma de pagamento
    await page.click('#pagamento');
    // Então eu vejo a mensagem de erro esperada
    await expect(
        page.locator( '#erro' )
    ).toContainText( mensagemEsperada );
}

export async function solicitarEmprestimoComValorCorretoEVerificaParcela( page, valor, parcelas, quantidadeParcelas ) {
    // Dado que estou na tela de solicitação de empréstimos e eu pesquiso um cliente existente
    await pesquisarCliente( page, '95201479774',  'Maria Eduarda Hottz' );
    // Quando eu informo o valor para solicitar um empréstimo
    await page.fill( '#valor', valor );
    // E eu seleciono para escolher a forma de pagamento
    await page.locator( '#pagamento' ).selectOption( parcelas );
    // Então eu vejo as parcelas do empréstimo listado na tela baseado no valor informado
    await expect(
        page.locator( '#parcelas tbody tr' )
    ).toHaveCount( quantidadeParcelas );
}