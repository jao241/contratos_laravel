# Documento Técnico

# Visão Geral

O projeto consiste em um sistema de gerenciamento de contratos desenvolvido utilizando Laravel e Vue.js.

A aplicação foi construída seguindo princípios de separação de responsabilidades, arquitetura em camadas e centralização das regras de negócio, visando facilitar manutenção, escalabilidade e testabilidade.

O sistema permite:
- gerenciamento de clientes;
- gerenciamento de serviços;
- gerenciamento de contratos;
- gerenciamento de itens de contrato;
- criação e visualização de histórico de mudanças no contrato;
- cálculo de valores;
- aplicação de regras de domínio;
- consumo via API REST.

---

# Estrutura da Aplicação

A aplicação foi organizada separando responsabilidades entre:
- camada HTTP;
- camada de serviços;
- camada de domínio;
- camada de persistência;
- frontend Vue.js.

Estrutura principal:

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

---

# Decisões Técnicas Tomadas

## Laravel como backend

O Laravel foi escolhido por:
- possuir excelente estrutura para APIs REST;
- integração nativa com ORM;
- suporte a policies;
- sistema robusto de validação;
- facilidade de testes automatizados.

---

## Vue.js no frontend

O frontend foi desenvolvido utilizando Vue.js para:
- separação entre frontend e backend;
- criação de uma SPA;
- componentização da interface;
- comunicação desacoplada via API REST.

---

## Arquitetura em Camadas

Foi utilizada uma arquitetura baseada em separação de responsabilidades.

### Controllers
Responsáveis apenas por:
- receber requisições;
- validar entrada;
- chamar serviços;
- retornar respostas.

A lógica de negócio não foi implementada nos controllers.

---

### Services

Os serviços concentram:
- regras de negócio;
- manipulação de entidades;
- operações complexas;
- cálculos.

Exemplos:
- `ContractService`
- `ContractItemService`
- `ContractCalculatorService`

---

### Policies

As policies foram utilizadas para centralizar regras de autorização.

Exemplos:
- impedir alteração de contratos cancelados;
- impedir remoção de serviços vinculados;
- impedir alteração de itens de contratos cancelados.

---

### Requests

Os Form Requests foram utilizados para:
- validação;
- sanitização;
- organização das regras de entrada.

---

# Organização das Camadas

## Camada HTTP

Responsável pela comunicação externa:
- Controllers
- Requests
- Responses

---

## Camada de Serviço

Responsável pela regra de negócio:
- criação de contratos;
- atualização;
- cálculos;
- aplicação de descontos.

---

## Camada de Persistência

Responsável pela comunicação com banco:
- Eloquent ORM;
- Models;
- Relacionamentos.

---

## Camada de Frontend

Responsável pela interface:
- páginas Vue;
- consumo da API.

---

# Implementação das Regras de Negócio

As regras de negócio foram centralizadas principalmente em:
- Services;
- Policies.

---

## Contratos cancelados

Contratos cancelados:
- não podem ser atualizados;
- não podem receber novos itens;
- não podem ter itens removidos.

Essa regra foi implementada utilizando Policies.

---

## Clientes inativos

Clientes com status `inactive`:
- não podem criar contratos.

Essa validação foi implementada no `ContractService`.

---

## Remoção de serviços

Serviços vinculados a contratos:
- não podem ser removidos.

Essa regra foi implementada na `ServicePolicy`.

---

## Cálculo de contratos

O cálculo do valor total foi separado em:
- serviço de cálculo;
- regras de desconto.

Foi criada a regra:
- `QuantityDiscountRule`

Responsável por aplicar desconto baseado na quantidade de itens.

---

## Histórico de Alterações dos Contratos

Toda alteração realizada em contratos é registrada automaticamente em histórico, permitindo rastreabilidade completa das operações realizadas no sistema.

Os registros de histórico são gerados para:

- criação de contratos;
- atualização de informações;
- cancelamento de contratos;
- alterações de status;
- modificações de campos relevantes.

Cada registro armazena:

- ação executada;
- campo alterado;
- valor anterior;
- novo valor;
- data da alteração.

Essa abordagem permite auditoria das operações e maior controle sobre o ciclo de vida dos contratos.

---

# Testes Automatizados

Foram implementados:
- testes unitários;
- cenários de sucesso;
- cenários de erro.

Os testes utilizam:
- SQLite em memória;
- factories;
- RefreshDatabase.

Objetivos:
- garantir integridade das regras;
- validar comportamento esperado;
- reduzir regressões.

---

# Dockerização

A aplicação foi dockerizada utilizando:
- PHP-FPM;
- Nginx;
- MySQL;
- Node.js.

O ambiente foi separado em serviços independentes:
- backend;
- frontend;
- banco;
- servidor web.

---

# API REST

A API foi estruturada seguindo padrões REST:
- endpoints organizados;
- respostas JSON;
- versionamento inicial em `/api/v1`.

Também foi implementada:
- paginação;
- eager loading;
- relacionamento entre entidades.

---

# O que Melhoraria com Mais Tempo

## Autenticação

Adicionar:
- Laravel Sanctum;
- autenticação JWT;
- controle de permissões.

---

## Histórico de alterações

Implementar:
- auditoria;
- rastreamento de alterações. (implementado parcialmente em contratos)

---

## Filtros avançados

Adicionar:
- filtros dinâmicos;
- ordenação;
- busca textual.

---

## Observabilidade

Adicionar:
- logs estruturados;
- métricas;
- monitoramento com Grafana e Prometheus.

---

## CI/CD

Criar pipeline automatizada:
- testes;
- lint;
- deploy.

---

## Interface

Melhorias planejadas para a interface:

- Adicionar filtros e busca nas listagens;
- Exibir mensagens amigáveis para erros e exceções da API;
- Implementar responsividade para dispositivos móveis;
- Exibir mensagens de validação nos campos do formulário;
- Agrupar ações da tabela em menus recolhíveis;
- Adicionar estados de loading durante requisições;
- Melhorar feedback visual para ações de sucesso;
- Implementar confirmação visual antes de exclusões;
- Melhorar organização visual dos contratos e itens;
- Adicionar paginação mais avançada no frontend.

---

## Melhorias arquiteturais

Possíveis melhorias:
- DTOs;
- repositories;
- eventos;
- filas;
- cache Redis;
- mensageria.

---

# Conclusão

O projeto foi desenvolvido com foco em:
- organização;
- separação de responsabilidades;
- escalabilidade;
- manutenção;
- boas práticas.

A arquitetura adotada facilita:
- evolução do sistema;
- inclusão de novas funcionalidades;
- manutenção futura;
- cobertura de testes.
