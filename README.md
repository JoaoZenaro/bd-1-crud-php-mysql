# PHP + MySQL + Docker
### Tarefa

### Configurando & Executando 
`docker-compose up` no shell dentro da pasta  root do projeto. 

> Se tiver erro: `Fatal error: Uncaught Error: Call to undefined function mysqli_connect() in /var/www/html/index.php:3 Stack trace: #0 {main} thrown in /var/www/html/index.php on line 3`
> 
> Abra o terminal interativo com o container que está rodando o serviço `www` (`docker exec -it php-docker-full-stack-www-1 "bash"`) e execute o comando: `docker-php-ext-install mysqli && docker-php-ext-enable mysqli && apachectl restart`
