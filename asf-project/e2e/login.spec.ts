import { test, expect } from '@playwright/test';
import { realizarLogin } from './helper/login';

test.describe('Realizar login', () => {
    test('Login com usuário válido', async({ page }) => {
        await realizarLogin(page);
    });

    test('Login com usuário inválido', async({ page }) => {
        await realizarLogin(page, 'usuario-invalido', '123456', 'Usuário ou senha inválidos');
    });

    test('Login com senha inválida', async({ page }) => {
        await realizarLogin(page, 'Carlos', 'senha-invalida', 'Usuário ou senha inválidos');
    });

    test('Login com usuário gerente', async({ page }) => {
        await realizarLogin(page, 'Thiago', '123456');
        await expect(
            page.locator('#relatorio')
        ).toBeVisible();
    });
});