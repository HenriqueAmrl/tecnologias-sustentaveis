import { test, expect } from '@playwright/test';
import { solicitarEmprestimoComValorCorreto, solicitarEmprestimoComValorIncorreto, solicitarEmprestimoSemFormaDePagamento, solicitarEmprestimoSemCliente, solicitarEmprestimoComValorCorretoEVerificaParcela } from './helper/emprestimo';
import { realizarLogin } from './helper/login';

test.beforeEach(async ({page}) => {
    await realizarLogin(page);
});

test.describe( 'Cadastrar Emprestimo', () => {

    test( 'Indica erro tentar solicitar empréstimo com valor menor que o permitido', async({ page }) => {
        await solicitarEmprestimoComValorIncorreto( page, '499', 'O valor do empréstimo deve estar entre' );
    } );

    test( 'Indica erro tentar solicitar empréstimo com valor maior que o permitido', async({ page }) => {
        await solicitarEmprestimoComValorIncorreto( page, '50001', 'O valor do empréstimo deve estar entre' );
    } );

    test( 'Indica erro tentar solicitar empréstimo sem forma de pagamento selecionada', async({ page }) => {
        await solicitarEmprestimoSemFormaDePagamento( page, '1000', 'Selecione uma forma de pagamento' );
    } );

    test( 'Indica erro tentar solicitar empréstimo sem cliente', async({ page }) => {
        await solicitarEmprestimoSemCliente( page, '500', 'Insira um CPF válido' );
    } );

    test( 'Cadastra empréstimo com os dados corretos', async({ page }) => {
        await solicitarEmprestimoComValorCorreto( page, '1000', 'Empréstimo realizado com sucesso!' );
    } );

    test( 'Cadastra empréstimo com os dados corretos e verifica as parcelas obtidas', async({ page }) => {
        await solicitarEmprestimoComValorCorretoEVerificaParcela( page, '3000', 'Parcelado 3x', 3 );
    } );

} );