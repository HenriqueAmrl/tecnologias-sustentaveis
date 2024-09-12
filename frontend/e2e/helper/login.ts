import { expect } from "@playwright/test";

export async function realizarLogin(page, usuario: string = 'Carlos', senha: string = '123456', erroEsperado?: string) {
    await page.goto('http://localhost:5173/login.html');
    await page.fill('#login', usuario);
    await page.fill('#senha', senha);
    await page.click('.entrar');
    
    if (erroEsperado) {
        await expect(
            page.locator('#erro')
        ).toContainText(erroEsperado);
    } else {
        await expect(
            page.locator('#logout')
        ).toBeVisible();
    }
}