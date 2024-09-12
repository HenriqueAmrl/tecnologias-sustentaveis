import { test, expect } from '@playwright/test';
import { pesquisarCliente } from './helper/pesquisa';
import { realizarLogin } from './helper/login';

test.beforeEach(async ({page}) => {
    await realizarLogin(page);
});

test.describe( 'Pesquisar Cliente', () => {

    test( 'Encontra cliente existente', async({ page }) => {
        await pesquisarCliente( page, '95201479774',  'Maria Eduarda Hottz' );
    } );

    test( 'Indica cliente não encontrado', async({ page }) => {
        await pesquisarCliente( page, '00000000000',  'Cliente não encontrado' );
    } );

    test( 'Indica cliente não encontrado ao buscar informando o campo vazio', async({ page }) => {
        await pesquisarCliente( page, ' ',  'Cliente não encontrado' );
    } );

    test( 'Encontra cliente existente pesquisando com cpf com caracteres especiais', async({ page }) => {
        await pesquisarCliente( page, '683.977.246-21',  'Carlos Henrique do Amaral Reis' );
    } );

} );