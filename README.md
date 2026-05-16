# Sistema de Gerenciamento de Contratos

Sistema de gerenciamento de contratos desenvolvido em Laravel com arquitetura em camadas, API REST, Vue.js, Docker e testes automatizados.

O projeto permite:

- gerenciamento de clientes;
- gerenciamento de serviços;
- gerenciamento de contratos;
- gerenciamento de itens do contrato;
- cálculo de valor total do contrato;
- interface SPA utilizando Vue.js.

---

# Funcionalidades Implementadas

## Clientes

- Cadastro de clientes
- Atualização de clientes
- Remoção de clientes
- Listagem paginada
- Relacionamento com contratos
- Regras de validação

---

## Serviços

- Cadastro de serviços
- Atualização de serviços
- Remoção de serviços
- Listagem paginada
- Relacionamento com itens do contrato

---

## Contratos

- Cadastro de contratos
- Atualização de contratos
- Cancelamento de contratos
- Listagem paginada
- Relacionamento com cliente
- Relacionamento com itens
- Cálculo de valor total
- Aplicação de regras de desconto
- Regra para impedir alteração de contratos cancelados
- Regra para impedir criação de contratos para clientes inativos

---

## Itens do Contrato

- Adição de itens ao contrato
- Atualização de itens
- Remoção de itens
- Relacionamento com serviços
- Cálculo de subtotal

---

## API REST

- Endpoints RESTful
- Versionamento inicial (`/api/v1`)
- Retorno em JSON
- Paginação
- API consumida pelo frontend Vue.js

---

## Frontend Vue.js

- Interface SPA
- Consumo da API REST
- Componentização
- Integração com Axios

---

## Regras de Negócio

- Policies do Laravel
- Contratos cancelados não podem ser alterados
- Clientes inativos não podem criar contratos
- Serviços vinculados não podem ser removidos

---

## Testes Automatizados

- Testes unitários dos serviços
- Cenários de sucesso
- Cenários de erro
- SQLite em memória para testes

---

## Estrutura Docker

- PHP 8.4 FPM
- Nginx
- MySQL 8
- Docker Compose
- Multi-stage Dockerfile
- Node 20

---

# Tecnologias Utilizadas

## Backend

- Laravel 12
- PHP 8.4
- Eloquent ORM
- Service Layer
- Policies
- Form Requests

---

## Frontend

- Vue.js
- Axios
- Vite

---

## Banco de Dados

- MySQL 8
- SQLite (testes)

---

## Infraestrutura

- Docker
- Docker Compose
- Nginx
- Node.js

---

## Testes

- PHPUnit
- Laravel Testing

---

# Estrutura do Projeto

```text
app/
├── Http/
│   ├── Controllers/
│   └── Requests/
├── Models/
├── Policies/
├── Providers/
├── Services/
│   └── Contract/
│       └── Rules/

database/
├── factories/
├── migrations/
└── seeders/

tests/
├── Feature/
└── Unit/

resources/
└── js/
    ├── pages/
    └── services/

docker/
└── nginx/
```

# Como Rodar o Projeto

## Pré-requisitos

Antes de iniciar, é necessário ter instalado:

- Docker
- Docker Compose

---

## 1. Clonar o repositório

```bash
git clone https://github.com/jao241/contratos_laravel
```

---

## 2. Entrar na pasta do projeto

```bash
cd contratos_laravel
```

---

## 3. Copiar o arquivo de ambiente

```bash
cp .env.example .env
```

---

## 4. Subir os containers Docker

```bash
docker compose up -d --build
```

---

## 5. Gerar chave da aplicação

```bash
docker compose exec app php artisan key:generate
```

---

## 6. Executar migrations

```bash
docker compose exec app php artisan migrate
```

---

## 7. Frontend Vue.js

O serviço Node já executa automaticamente:

- npm install
- npm run dev

Caso seja necessário reiniciar o frontend:

```bash
docker compose restart node
```

---

## 8. Acessar o projeto

### Backend/API

```text
http://localhost:8000/api/v1
```

### Frontend Vue.js

```text
http://localhost:8000
```

---

## Executando os testes

```bash
docker compose exec app php artisan test
```

---

## Parando os containers

```bash
docker compose down
```

---

# Serviços Docker

O projeto utiliza os seguintes serviços:

| Serviço | Descrição              |
| ------- | ---------------------- |
| app     | Container PHP/Laravel  |
| nginx   | Servidor web           |
| mysql   | Banco de dados MySQL   |
| node    | Frontend Vue.js + Vite |

---

# Endpoints da API

Todas as rotas da API utilizam o prefixo:

```text
/api/v1
```

As respostas são retornadas em formato JSON.

---

# Clientes

| Método | Endpoint | Descrição |
|---|---|---|
| GET | `/api/v1/clients` | Listar clientes |
| POST | `/api/v1/clients` | Criar cliente |
| GET | `/api/v1/clients/{client}` | Buscar cliente |
| PUT | `/api/v1/clients/{client}` | Atualizar cliente |
| PATCH | `/api/v1/clients/{client}` | Atualização parcial do cliente |
| DELETE | `/api/v1/clients/{client}` | Remover cliente |

---

# Serviços

| Método | Endpoint | Descrição |
|---|---|---|
| GET | `/api/v1/services` | Listar serviços |
| POST | `/api/v1/services` | Criar serviço |
| GET | `/api/v1/services/{service}` | Buscar serviço |
| PUT | `/api/v1/services/{service}` | Atualizar serviço |
| PATCH | `/api/v1/services/{service}` | Atualização parcial do serviço |
| DELETE | `/api/v1/services/{service}` | Remover serviço |

---

# Contratos

| Método | Endpoint | Descrição |
|---|---|---|
| GET | `/api/v1/contracts` | Listar contratos |
| POST | `/api/v1/contracts` | Criar contrato |
| GET | `/api/v1/contracts/{contract}` | Buscar contrato |
| PUT | `/api/v1/contracts/{contract}` | Atualizar contrato |
| PATCH | `/api/v1/contracts/{contract}` | Atualização parcial do contrato |
| DELETE | `/api/v1/contracts/{contract}` | Remover contrato |
| PATCH | `/api/v1/contracts/{contract}/cancel` | Cancelar contrato |

---

# Itens do Contrato

| Método | Endpoint | Descrição |
|---|---|---|
| POST | `/api/v1/contracts/{contract}/items` | Adicionar item ao contrato |
| PUT | `/api/v1/contract-items/{contractItem}` | Atualizar item do contrato |
| DELETE | `/api/v1/contract-items/{contractItem}` | Remover item do contrato |

---
