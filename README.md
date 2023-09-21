# Projeto PetShop
    Projeto foi feito utilizando CodeIgniter 2.2.6

## Pré-Requisitos

Antes de começar, você deve ter o seguinte instalado:

- Servidor MySQL
- PHP>=7
- Composer

## Como rodar o projeto

1. **Faça o pull do projeto**
2. **Instalar dependências**
    Rodar o comando: 
    ```code
    composer install

3. **Crie um Banco de Dados:**

   Você precisará criar um banco de dados vazio para o projeto. Você pode fazer isso usando uma ferramenta de gerenciamento de banco de dados, como o phpMyAdmin, ou executando o seguinte comando SQL:

   ```sql
   CREATE DATABASE petshopw16;

4. **Importe o schema:**

    No diretório do projeto, importar o arquivo petshopw16.sql.
    ```sql
    mysql -u seu_usuario -p petshopw16 < petshopw16.sql

5. **Configurar conexão**
    Abra o arquivo application/config/database.php no seu projeto CodeIgniter e atualize as informações de conexão com o banco de dados. 
    ```php
    $db['default'] = array(
        'hostname' => 'localhost',
        'username' => 'seu_usuario',
        'password' => 'sua_senha',
        'database' => 'nomedobanco',
        // ...
    );

6. **JWT**
    Para fins de exemplo e facilidade na execução do projeto, a chave secreta JWT foi incluída no código. No entanto, é altamente recomendável que você siga práticas de segurança adequadas e armazene a chave secreta JWT de forma segura, em um arquivo de ambiente (.env).

7. **Login na aplicação** 
    Para realizar o primeiro login como administrado utilizar as credenciais:
    *email: admin@gmai.com*
    *senha: 1234567*

