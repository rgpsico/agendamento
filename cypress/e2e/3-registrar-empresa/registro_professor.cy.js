describe('Registro de Professor', () => {
    beforeEach(() => {
        cy.visit('http://localhost:8000/agendamento');
    });

    it('deve registrar um professor com sucesso', () => {
        // Verifica se o modal de boas-vindas aparece
        cy.get('#welcomeModal', { timeout: 5000 }).should('be.visible');

        // Clica no botão de fechar o modal de boas-vindas
        cy.get('#fecharModalBoasVindas').click();

        // Garante que o modal foi fechado
        cy.get('#welcomeModal').should('not.be.visible');

        // Clica no botão que abre o modal de registro
        cy.get('[data-bs-target="#registerModal"]', { timeout: 5000 }).click();

        // Verifica se o modal de registro está visível
        cy.get('#registerModal', { timeout: 5000 }).should('be.visible');

        // Clica na aba de professor
        cy.get('#professor-tab', { timeout: 5000 }).should('be.visible').click();

        // Preenche o formulário
        cy.get('#professorNome').type('Professor Teste');
        cy.get('#professorEmail').type('professor.teste@example.com');
        cy.get('#professorSenha').type('123456');

        // Submete o formulário
        cy.get('#professorForm').submit(); // Substitua '#professorForm' pelo ID real do formulário

        // Verifica se o modal de boas-vindas aparece após o registro
        cy.contains('Bem-vindo!').should('be.visible');

        // Clica no botão que fecha o modal
        cy.get('[data-bs-dismiss="modal"]').click();

        // Verifica se o modal não está mais visível
        cy.get('#modalBoasVindas').should('not.be.visible');
    });
});
