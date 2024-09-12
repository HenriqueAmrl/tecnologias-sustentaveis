import { expect } from "@playwright/test";

export async function pesquisarCliente( page, cpf, mensagemEsperada ) {
    // Dado que estou na tela de pesquisa
    await page.goto( 'http://localhost:5173/cadastrar-emprestimo.html' );
    // Quando eu informo o cpf de um cliente existe
    await page.fill( '#pesquisa', cpf );
    // E eu aciono a opção "Pesquisar"
    await page.click( '#pesquisar' );
    // Então eu vejo o cliente listado na tela
    await expect(
        page.locator( '#cliente' )
    ).toContainText( mensagemEsperada );
}