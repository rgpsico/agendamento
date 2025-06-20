# Agendamento

Este projeto é uma aplicação web desenvolvida em [Laravel](https://laravel.com/) para gerenciar agendamentos de aulas e outros recursos administrativos. O sistema utiliza módulos independentes (via **nwidart/laravel-modules**) e integra funcionalidades de agenda, gerenciamento de alunos, blog e pagamentos.

## Principais Funcionalidades

- Cadastro e controle de usuários (alunos e professores).
- Agendamento de aulas com registro de data, horário e modalidade.
- Integração de pagamentos (ex.: Stripe e gateways locais).
- Módulo **Alunoadmin** para área do aluno, com listagem de aulas e fotos.
- Módulo **Blog** para criação de publicações.
- Interface feita com Vue.js e Inertia.js.

## Requisitos

- PHP >= 8.1
- Composer
- Node.js e npm
- Um banco de dados configurado (MySQL ou outro compatível)

## Instalação

1. Clone este repositório.
2. Instale as dependências PHP:
   ```bash
   composer install
   ```
3. Instale as dependências JavaScript:
   ```bash
   npm install
   ```
4. Copie o arquivo `.env.example` para `.env` e ajuste as configurações de banco e serviços.
5. Gere a chave da aplicação:
   ```bash
   php artisan key:generate
   ```
6. Execute as migrações do banco de dados:
   ```bash
   php artisan migrate
   ```

## Executando o Projeto

Inicie o servidor de desenvolvimento com:
```bash
php artisan serve
```
A aplicação estará disponível em `http://localhost:8000`.

## Testes

Os testes são escritos com PHPUnit. Para executá-los, utilize:
```bash
phpunit
```

## Licença

Este projeto utiliza a licença [MIT](https://opensource.org/licenses/MIT).
