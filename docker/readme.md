## Como rodar o projeto
    - inserir todos os dumps na pasta 'dumps' dentro de 'docker'
    - docker-compose up -d 
    - docker exec -it <container-id do mysql> "/bin/bash"
### Dentro do terminal do container para realizar o dump faça

#### Senha root é "password" como definido no docker-compose 

    - mysql -u root -p  < /dumps/join3_security.sql
    - mysql -u root -p  < /dumps/join3_lcgit.sql