# Desafio Viagens Corporativas

Este projeto é uma aplicação full-stack para gerenciamento de pedidos de viagem corporativa, desenvolvida com Laravel (API REST) e Vue.js (interface interativa).

## Sumário

- [Requisitos](#requisitos)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Configuração do Ambiente](#configuração-do-ambiente)
  - [Pré-requisitos](#pré-requisitos)
  - [Instalação](#instalação)
- [Executando a Aplicação](#executando-a-aplicação)
  - [Backend (Laravel API)](#backend-laravel-api)
  - [Frontend (Vue.js)](#frontend-vuejs)
- [Funcionalidades](#funcionalidades)
- [Testes](#testes)
- [Considerações Finais](#considerações-finais)

## Requisitos

Conforme o desafio proposto, a aplicação atende aos seguintes requisitos:

### Backend (Laravel)

- Criação, consulta e listagem de pedidos de viagem.
- Atualização de status de pedidos (aprovado/cancelado) com restrição para administradores.
- Lógica de cancelamento após aprovação.
- Notificação por e-mail ao alterar o status do pedido.
- Autenticação de usuário com tokens (Laravel Sanctum).
- Relacionamento entre pedidos e usuários, permitindo que cada usuário gerencie suas próprias ordens.

### Frontend (Vue.js)

- Dashboard para exibição de pedidos.
- Formulário/modal para criação de novos pedidos.
- Interface para atualização de status de pedidos.
- Tela de login.
- Feedback ao usuário (mensagens de sucesso/erro, loading).

## Tecnologias Utilizadas

- **Backend:** Laravel 10, PHP 8.2, MySQL, Laravel Sanctum, MailHog
- **Frontend:** Vue.js 3, Pinia, Vue Router, Vite, Axios
- **Containerização:** Docker, Docker Compose

## Configuração do Ambiente

### Pré-requisitos

Certifique-se de ter o Docker e o Docker Compose instalados em sua máquina.

- [Docker Desktop](https://www.docker.com/products/docker-desktop)

### Instalação

1.  **Clone o repositório:**
    ```bash
    git clone https://github.com/lucasfernando7ii/v4Fly
    cd desafio-viagens
    ```

2.  **Configurar variáveis de ambiente:**
    Crie o arquivo `.env` a partir do `.env.example`:
    ```bash
    cp .env.example .env
    ```
    Edite o arquivo `.env` e configure as variáveis de ambiente necessárias, como as credenciais do banco de dados e as configurações de e-mail (MailHog).

3.  **Iniciar os containers Docker:**
    ```bash
    ./vendor/bin/sail up -d
    ```
    Este comando irá construir as imagens Docker (se necessário), iniciar os serviços (PHP, Nginx, MySQL, MailHog) e executá-los em segundo plano.

4.  **Instalar dependências do Composer (Laravel):**
    ```bash
    ./vendor/bin/sail composer install
    ```

5.  **Gerar a chave da aplicação Laravel:**
    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

6.  **Executar as migrações do banco de dados e popular com dados de exemplo (opcional):**
    ```bash
    .    ./vendor/bin/sail artisan migrate:fresh --seed
    Isso criará as tabelas necessárias e adicionará alguns usuários e pedidos de exemplo, incluindo as seguintes credenciais para teste:
    Para administrador: `email` => `admin@admin.com`, `password` => `12345678`
    Para usuário comum: `email` => `user@teste.com`, `password` => `12345678`

7.  **Instalar dependências do NPM (Vue.js):**
    ```bash
    ./vendor/bin/sail npm install
    ```

## Executando a Aplicação

### Backend (Laravel API)

O backend estará disponível em `http://localhost` após a inicialização dos containers Docker.

### Frontend (Vue.js)

Para iniciar o servidor de desenvolvimento do frontend:

```bash
./vendor/bin/sail npm run dev
```

O frontend será acessível em `http://localhost` (o Laravel serve os assets do Vite).

## Funcionalidades

- **Autenticação:** Acesse `http://localhost` e faça login. Use `admin@example.com` com a senha `password` para a conta de administrador, ou crie um novo usuário.
- **Criação de Pedidos:** No Dashboard, clique em "+ Adicionar Pedido".
- **Visualização de Pedidos:** Todos os pedidos são listados na tabela do Dashboard.
- **Atualização de Status (Admin):** Para usuários administradores, botões "Aprovar" e "Reprovar" aparecerão para pedidos com status "Pendente".
- **Notificações por E-mail:** Verifique o MailHog em `http://localhost:8025` para ver os e-mails de notificação de status.

## Testes

### Backend (Laravel)

Para executar os testes unitários e de funcionalidade do Laravel (PHPUnit):

```bash
./vendor/bin/sail artisan test
```

**(Nota: Os testes unitários não foram implementados neste projeto como parte do desenvolvimento inicial, mas esta é a instrução para executá-los caso sejam adicionados.)**


## Considerações Finais

Este projeto demonstra a integração de um backend Laravel com um frontend Vue.js utilizando Docker para orquestração. A aplicação atende aos requisitos principais de gerenciamento de pedidos de viagem, com foco em autenticação, autorização e fluxo de trabalho de status. Melhorias futuras podem incluir filtros avançados, paginação, edição/exclusão de pedidos e uma suíte completa de testes automatizados.
