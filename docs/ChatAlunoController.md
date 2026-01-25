# ChatAlunoController

Controlador responsavel pelas operacoes de chat do aluno. Inclui listagem de conversas, interacoes com empresa, envio de mensagens para professor e listagem de mensagens por conversa.

## Base

Arquivos principais:
- `app/Http/Controllers/ChatAlunoController.php`
- `routes/api.php`

Namespace: `App\Http\Controllers`

## Endpoints

### 1) Listar conversas do aluno

`GET /api/aluno/conversas`

Parametros (query):
- `user_id` (integer, opcional) - id do aluno (usuario). Se omitido, usa o autenticado.
- `aluno_user_id` (integer, opcional) - alias para `user_id`.

Regras:
- `user_id` deve ser aluno.
- Aluno autenticado so pode consultar o proprio `user_id`.
- Retorna conversas sem repeticao por `empresa_id`.

Resposta (exemplo):
```json
[
  {
    "conversation_id": 10,
    "empresa_id": 2,
    "contato": {
      "id": 7,
      "nome": "Empresa X",
      "email": "contato@empresa.com"
    },
    "last_message": {
      "id": 101,
      "from": "user",
      "to": "professor",
      "role": "user",
      "body": "Ola",
      "created_at": "2026-01-22 18:10:00"
    },
    "updated_at": "2026-01-22 18:12:00"
  }
]
```

### 2) Listar interacoes aluno + empresa

`GET /api/aluno/conversas/alunoeempresa`

Parametros (query):
- `empresa_id` (integer, obrigatorio)
- `user_id` (integer, opcional) - id do aluno (usuario). Se omitido, usa o autenticado.
- `aluno_user_id` (integer, opcional) - alias para `user_id`.

Regras:
- `user_id` deve ser aluno.
- Aluno autenticado so pode consultar o proprio `user_id`.

Resposta (exemplo):
```json
[
  {
    "conversation_id": 10,
    "empresa_id": 2,
    "contato": {
      "id": 7,
      "nome": "Empresa X",
      "email": "contato@empresa.com"
    },
    "messages": [
      {
        "id": 101,
        "from": "user",
        "to": "professor",
        "role": "user",
        "body": "Ola",
        "created_at": "2026-01-22 18:10:00"
      }
    ],
    "updated_at": "2026-01-22 18:12:00"
  }
]
```

### 3) Enviar mensagem do aluno para professor

`POST /api/aluno/enviarmensagemaoprofessor`

Body (JSON):
- `mensagem` (string, obrigatorio)
- `empresa_id` (integer, obrigatorio)
- `user_id` (integer, obrigatorio) - id do aluno (usuario)
- `professor_id` (integer, opcional) - se nao enviado, tenta usar um professor da empresa
- `conversation_id` (integer, opcional) - se nao enviado, cria conversa

Regras:
- `user_id` deve ser aluno.
- Aluno autenticado so pode enviar como o proprio `user_id`.

Resposta (exemplo):
```json
{
  "success": true,
  "conversation_id": 10,
  "message": {
    "id": 101,
    "from": "user",
    "to": "professor",
    "role": "user",
    "body": "Ola professor",
    "created_at": "2026-01-22 18:10:00"
  },
  "professor_id": 5
}
```

### 4) Listar mensagens por conversa (aluno)

`GET /api/aluno/listarmensagembyidconversa`

Parametros (query):
- `conversation_id` (integer, obrigatorio)
- `user_id` (integer, obrigatorio) - id do aluno (usuario)

Regras:
- `conversation_id` deve pertencer ao `user_id`.
- `user_id` deve ser aluno.
- Aluno autenticado so pode consultar o proprio `user_id`.

Resposta (exemplo):
```json
{
  "conversation_id": 10,
  "empresa_id": 2,
  "contato": {
    "id": 7,
    "nome": "Empresa X",
    "email": "contato@empresa.com"
  },
  "messages": [
    {
      "id": 101,
      "from": "user",
      "to": "professor",
      "role": "user",
      "body": "Ola",
      "created_at": "2026-01-22 18:10:00"
    }
  ]
}
```

## Observacoes

- Campos de data retornam em formato `Y-m-d H:i:s`.
- Para proteger dados, as validacoes bloqueiam aluno tentando acessar outro `user_id`.
