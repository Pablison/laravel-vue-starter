
# Laravel + Vue (Quasar) Starter

Este é um projeto **Full Stack** desenvolvido como um teste prático de competências. A aplicação consiste em uma API RESTful robusta construída com **Laravel** e um frontend SPA moderno utilizando **Vue.js 3** e **Quasar Framework**.

O projeto simula um sistema de gerenciamento de usuários com autenticação, controle de acesso (ACL) e interface responsiva.

---

## 🚀 Tecnologias Utilizadas

### Backend (API)
- **PHP 8.2+**
- **Laravel 10+**
- **MySQL 8.0**
- **Docker & Laravel Sail** (Ambiente de desenvolvimento containerizado)
- **JWT Auth** (`tymon/jwt-auth`) para autenticação segura sem estado (stateless)

### Frontend (SPA)
- **Vue.js 3** (Composition API)
- **Quasar Framework 2** (Vite)
- **Pinia** (Gerenciamento de Estado)
- **Axios** (Integração com API)
- **SCSS** (Estilização)

---

## 📋 Funcionalidades

- **Autenticação JWT:** Login seguro com persistência de token e redirecionamento automático.
- **Controle de Acesso (ACL):**
  - Rotas protegidas (Guarda de Rotas no Vue Router).
  - Diferenciação entre usuários **Admin** e **Comum**.
  - Interface adaptativa (botões e menus visíveis apenas para quem tem permissão).
- **CRUD de Usuários:**
  - Listagem de usuários em tabela de dados.
  - Criação, Edição e Exclusão de usuários (Restrito a Administradores).
  - Feedback visual com notificações (Toast) e janelas de confirmação (Dialogs).
- **Dashboard:** Layout responsivo com menu lateral (Drawer) e barra de navegação.
- **Background Jobs:** Simulação de envio de e-mail de boas-vindas utilizando Filas (Queues) do Laravel.

---

## 🛠️ Instalação e Configuração

Siga os passos abaixo para rodar o projeto em sua máquina local.

**Pré-requisitos:**
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado e rodando (ou Docker no WSL2).
- [Node.js](https://nodejs.org/) (versão 18 ou superior).

### 1. Clonar o Repositório

```bash
git clone [https://github.com/Pablison/laravel-vue-starter.git](https://github.com/Pablison/laravel-vue-starter.git)
cd laravel-vue-starter
````

### 2\. Configurar o Backend (Laravel)

O backend utiliza o **Laravel Sail**, portanto não é necessário ter PHP ou Composer instalados na sua máquina local.

1.  Navegue até a pasta do backend:

    ```bash
    cd backend
    ```

2.  Crie o arquivo de ambiente:

    ```bash
    cp .env.example .env
    ```

3.  Instale as dependências do PHP (utilizando um container temporário):

    ```bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
    ```

4.  Suba o ambiente Docker:

    ```bash
    ./vendor/bin/sail up -d
    ```

5.  Gere a chave da aplicação e o segredo do JWT:

    ```bash
    ./vendor/bin/sail artisan key:generate
    ./vendor/bin/sail artisan jwt:secret
    ```

6.  Execute as migrações e popule o banco de dados (Seeders):

    ```bash
    ./vendor/bin/sail artisan migrate --seed
    ```

> A API estará rodando em: `http://localhost:8000`

-----

### 3\. Configurar o Frontend (Vue/Quasar)

1.  Abra um **novo terminal** e navegue até a pasta do frontend:

    ```bash
    cd ../frontend
    ```

2.  Instale as dependências do Node:

    ```bash
    npm install
    ```

3.  Inicie o servidor de desenvolvimento:

    ```bash
    npm run dev
    ```

> A aplicação estará acessível em: `http://localhost:9000`

-----

## 👤 Credenciais de Acesso

O banco de dados é inicializado com os seguintes usuários para teste:

| Tipo | E-mail | Senha | Permissões |
| :--- | :--- | :--- | :--- |
| **Administrador** | `admin@teste.com` | `123456` | Acesso total (Criar, Editar, Excluir) |
| **Usuário Comum** | `user1@teste.com` | `123456` | Apenas visualização |

-----

## 🧪 Comandos Úteis

  - **Parar os containers do Backend:**

    ```bash
    # Dentro da pasta backend/
    ./vendor/bin/sail down
    ```

  - **Rodar comandos Artisan (ex: criar controller):**

    ```bash
    ./vendor/bin/sail artisan make:controller NomeDoController
    ```

  - **Monitorar Logs (ex: ver envio de e-mail simulado):**

    ```bash
    tail -f storage/logs/laravel.log
    ```

-----

Desenvolvido por **Pablison**.
