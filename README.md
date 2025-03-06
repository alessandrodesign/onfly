# Onfly – Desafio

#### by. Alessandro P. Souza

Este projeto Full Stack utiliza:

- **Back-end:** Laravel (API REST com autenticação via Sanctum, Reverb para broadcast)
- **Front-end:** Vue.js (Vite, Vue Router e Axios)
- **Infraestrutura:** Docker (PHP, MariaDB, Nginx, Redis)

A aplicação gerencia pedidos de viagem corporativa com dois níveis de acesso: **admin** e **client**.

---

## Pré-Requisitos

- Docker e Docker Compose

---

## Estrutura do Projeto

- **/backend:** Projeto Laravel
- **/frontend:** Projeto Vue.js
- **/docker-compose.yml:** Configuração dos containers

---

## Back-end (Laravel)

### 1. Clonando o Repositório

No terminal, clone o repositório e navegue até a pasta do backend:

```bash
git clone https://github.com/alessandrodesign/onfly.git
cd onfly/www/backend
```

### 2. Configuração do Ambiente

- **.env:**  
  Copie o arquivo de exemplo e configure as variáveis necessárias:

  ```bash
  cp .env.example .env
  ```

- **Variáveis de Banco de Dados:**  
  Ajuste conforme o seu docker-compose:

  ```
  DB_CONNECTION=mysql
  DB_HOST=mariadb
  DB_PORT=3306
  DB_DATABASE=onfly
  DB_USERNAME=alessandro
  DB_PASSWORD=MUpw@*56
  ```

- **Variáveis de Broadcast (Reverb):**

  ```
  BROADCAST_DRIVER=reverb

  REVERB_APP_ID=local
  REVERB_APP_KEY=localkey
  REVERB_APP_SECRET=localsecret
  REVERB_HOST=127.0.0.1
  REVERB_PORT=6001
  REVERB_SCHEME=http
  ```

### 3. Instalar as Dependências do Laravel

No diretório do backend, execute:

```bash
composer install
```

**Execute as migrações** (as migrações do Sanctum serão executadas juntamente com as demais):

```bash
php artisan migrate
```

> **Observação:**  
> As rotas protegidas da API poderão ser autenticadas com os tokens pessoais emitidos pelo Sanctum. Certifique-se de
> verificar o middleware adequado nas rotas (geralmente `auth:sanctum`).

### 4. Executar as Migrations e Seeds

Crie as tabelas e insira os dados iniciais (a seed já cria usuários admin e client):

```bash
php artisan migrate
php artisan db:seed
```

> **Seed Inicial:**  
> Os usuários iniciais criados são:
> - **Admins:**
>   - admin1@admin.com / senha: senha123
>   - admin2@admin.com / senha: senha123
> - **Clients:**
>   - user1@user.com / senha: senha123
>   - user2@user.com / senha: senha123

### 5. Executar o Servidor de Broadcast (Reverb)

Para transmitir eventos via canais, execute o servidor Reverb:

```bash
php artisan reverb:start
```

ou

```bash
php artisan reverb:start --debug
```

> **Dica:** Se estiver usando Docker, veja a seção abaixo para mapear a porta 6001.

### 8. Executar a API (via Docker ou Localmente)

#### Usando Docker:

No diretório raiz do projeto, execute:

```bash
docker-compose up -d
```

Isso iniciará os serviços necessários.

---

## Passo a Passo – Front-end (Vue.js)

### 1. Navegar para a Pasta do Frontend

```bash
cd onfly/www/frontend
```

### 2. Instalar as Dependências do Front-end

```bash
npm install
```

### 3. Configurar as Variáveis de Ambiente do Front-end

Crie ou edite o arquivo **.env** na raiz do projeto frontend e defina as variáveis (lembre-se de usar o
prefixo `VITE_`):

```
BASE_URL=http://127.0.0.1
VITE_REVERB_APP_KEY=localkey
VITE_REVERB_HOST=localhost
VITE_REVERB_PORT=6001
```

### 4. Executar o Projeto Vue.js

Inicie o servidor de desenvolvimento:

```bash
npm run dev
```

O projeto estará disponível (por exemplo, em http://localhost:5173).

---

## Docker Compose – Expondo as Portas

Verifique que o arquivo **docker-compose.yml** está mapeando as portas corretamente. No serviço do PHP, adicione:

```yaml
services:
  php:
    # ... outras configurações
    ports:
      - "6001:6001"   # Exposição da porta para o Reverb
```

Após alterações, reinicie os containers:

```bash
docker-compose down
docker-compose up -d
```

---

## Outras Configurações Importantes

- **Rotas e Autenticação:**  
  O back-end utiliza middleware `auth:sanctum` para proteger as rotas da API. Configure as rotas de acordo com o Sanctum
  para emissão e validação de tokens.

- **Broadcast e Eventos:**  
  Os eventos (como `NewOrderEvent` e `OrderStatusChangedEvent`) implementam a interface `ShouldBroadcast` e são
  transmitidos via canais privados. A autorização está definida em **routes/channels.php**.

- **Front-end e Laravel Echo:**  
  Caso deseje utilizar notificações em tempo real, configure o Laravel Echo no front-end (veja o exemplo em *
  *src/echo.js**) para conectar ao servidor Reverb.

- **Menus Diferenciados:**  
  O **App.vue** do front-end exibe menus distintos para usuários admin e client com base na role armazenada
  no `localStorage`.

- **Criação de Pedidos:**  
  A view **OrderForm.vue** (acessível em `/order/new`) permite que o cliente crie pedidos de viagem, enviando os dados
  para a API.

---

## Testando a Aplicação

1. **Backend:**
    - Use ferramentas como Postman para testar as rotas da API protegidas pelo Sanctum.
    - Utilize as credenciais dos usuários da seed para realizar login e obter tokens.

2. **Front-end:**
    - Acesse o endereço do servidor de desenvolvimento (ex.: http://localhost:5173).
    - Faça login e teste as funcionalidades dos dashboards para admin e client.
    - Crie um novo pedido através da view de criação.

---

## Considerações Finais

- Certifique-se de que todas as dependências estão instaladas corretamente tanto para o back-end quanto para o
  front-end.
- Siga os passos de configuração do Docker para garantir que os serviços se comuniquem corretamente.
- Ajuste as variáveis de ambiente conforme o seu ambiente de desenvolvimento.
- Consulte a [documentação do Laravel Sanctum](https://laravel.com/docs/sanctum)
  e [Laravel Reverb](https://laravel.com/docs/broadcasting) para mais detalhes ou customizações.

Esta documentação passo a passo deve ajudá-lo a colocar o projeto funcionando com autenticação via Sanctum e com as
seeds de usuários iniciais, além de todas as funcionalidades implementadas. Caso surjam dúvidas ou a necessidade de
ajustes adicionais, consulte a documentação oficial dos respectivos pacotes.

---