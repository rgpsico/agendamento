describe('Registro de Professor', () => {
    beforeEach(() => {
        cy.visit('http://localhost:8000/agendamento'); // página inicial onde tem o botão de registrar
    });

    it('deve registrar um professor com sucesso', () => {
        // Abre o modal de registro

        // cy.get('#welcomeModal').should('be.visible');

        // clica no botão de fechar
        setTimeout(() => {
            cy.get('#fecharModalBoasVindas').click();

            // garante que o modal foi fechado
            //    cy.get('#modalBoasVindas').should('not.be.visible');

            cy.get('#registerModal').click();
            cy.get('#professor-tab').click();
        }, 1000);

        setTimeout(() => {
            // garante que o modal foi fechado
            //    cy.get('#modalBoasVindas').should('not.be.visible');

            cy.get('#professor-tab').click();
        }, 2000);

        // cy.get('#professorNome').type('Professor Teste');
        // cy.get('#professorEmail').type('professor.teste@example.com');
        // cy.get('#professorSenha').type('123456');
        // cy.get('form').submit();

        // // Verifica se o modal de boas-vindas aparece
        // cy.contains('Bem-vindo!').should('be.visible');

        // // Clica no botão que fecha o modal
        // cy.get('[data-bs-dismiss="modal"]').click();

        // // Verifica se o modal não está mais visível
        // cy.get('#modalBoasVindas').should('not.be.visible');
    });
});
