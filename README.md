# Teste Mattos e Cia

## Passos para Configurar e Rodar o Projeto

### 1. Clonar o Repositório

Clone este repositório para o seu ambiente local.

### 2. Acesse a pasta do projeto.

cd teste-mattos-e-cia

### 3. Crie o arquivo .env com o conteúdo do .env.example
mv .env.example .env

### 4. Use o Docker Compose para construir e iniciar os contêineres.
docker-compose up --build

### 5. Acesse o shell do contêiner do Laravel.
docker exec -it laravel-app bash

### 6. Dentro do contêiner, instale as dependências do Composer.
composer install

### 7. Ainda dentro do contêiner, execute as migrações do banco de dados.
php artisan migrate

Após seguir os passos acima, a aplicação estará disponível em http://localhost:8080.
