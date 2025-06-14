describe('Tela de Login', () => {
    beforeEach(() => {
        // Visita a página de login antes de cada teste
        cy.visit('http://localhost:8000/login');
    });


    // it('deve acessar a página de login', () => {
    //     cy.contains('Bem-vindo de volta!').should('be.visible');
    //     cy.get('input[name=email]').should('be.visible');
    //     cy.get('input[name=senha]').should('be.visible');
    //     cy.get('button[type=submit]').should('contain', 'Login');
    // });

    // it('deve mostrar erros de validação quando os campos estão vazios', () => {
    //     cy.get('button[id=login]').click();
    //     cy.contains('O campo email é obrigatório').should('be.visible');
    //     cy.contains('O campo senha é obrigatório').should('be.visible');
    // });

    // it('deve mostrar erro ao tentar logar com dados inválidos', () => {
    //     cy.get('input[name=email]').type('email@email.com');
    //     cy.get('input[name=senha]').type('senhaerrada');
    //     cy.get('button[id=login]').click();
    //     cy.contains('Senha inválida').should('be.visible');
    // });

    // it('deve redirecionar para alunoadmin após login bem-sucedido como aluno', () => {
    //     // Intercepta a requisição POST para /login e simula uma resposta bem-sucedida
    //     cy.intercept('POST', '/login', {
    //         statusCode: 200,
    //         body: { tipo_usuario: 'Aluno' }
    //     }).as('loginRequest');

    //     // Visita a página de login e preenche o formulário
    //     cy.visit('http://localhost:8000/login');
    //     cy.get('input[name=email]').type('alunoteste@123.com');
    //     cy.get('input[name=senha]').type('123456');

    //     // Submete o formulário (ajustado para garantir que o CSRF token seja incluído)
    //     cy.get('button[id=login]').click();

    //     // Aguarda a requisição e verifica o redirecionamento
    //     cy.contains('Dashboard').should('be.visible');
    // });

    it('deve redirecionar para dashboard após login bem-sucedido como professor', () => {
        // Simula um login bem-sucedido como professor
        cy.intercept('POST', '/login', {
            statusCode: 200,
            body: { tipo_usuario: 'Professor' }
        }).as('loginRequest');

        cy.get('input[name=email]').type('professor01@gmail.com');
        cy.get('input[name=senha]').type('123456');
        cy.get('button[id=login]').click();

        cy.contains('Bem-vindo').should('be.visible');
    });

    // it('deve permitir navegação para a página de registro', () => {
    //     cy.get('a[href*="registerAluno"]').click();
    //     cy.url().should('include', '/home/registerAluno');
    // });
});