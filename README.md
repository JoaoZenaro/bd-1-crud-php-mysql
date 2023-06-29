# PHP + MySQL + Docker

### Configurando & Executando Ambiente
`docker compose up` no shell dentro da pasta  root do projeto. 

> Se tiver erro: `Fatal error: Uncaught Error: Call to undefined function mysqli_report() in /var/www/html/services/conexoes.php:5 Stack trace: #0 /var/www/html/consulta.php(2): require_once() #1 {main} thrown in /var/www/html/services/conexoes.php on line 5`
> 
> Abra o terminal interativo com o container que está rodando o serviço `www` (`docker exec -it bd-1-crud-php-mysql-www-1 "bash"`) e execute o comando: `docker-php-ext-install mysqli && docker-php-ext-enable mysqli && apachectl restart`

# Tarefa
## Implementação de um CRUD interativo em PHP + MySQL

O objetivo principal é implementar um CRUD (Create, Read, Update, Delete) interativo em PHP + MySQL, baseando-se no sistema desenvolvido durante as aulas, conforme descrito no arquivo 'Roteiro PHP + MySQL (2023)'.

## CRUD de Produtos

O CRUD será utilizado para o cadastro, consulta, atualização e exclusão de produtos, armazenando os dados em tabelas do MySQL. O nome do banco de dados deverá ser `crud_produtos`.

A tabela de produtos terá a seguinte estrutura:

| Campo          | Tipo        | Restrição                                   |
|----------------|-------------|---------------------------------------------|
| codigo_prd     | INT         | Autoincremento, chave primária               |
| descricao_prd  | VARCHAR(50) | Único (UNIQUE) e NOT NULL                    |
| data_cadastro  | DATE        | NOT NULL, valor default será a data atual    |
| preco          | DECIMAL(10, 2) | NOT NULL, não pode ser negativo, valor default 0.0 |
| ativo          | Booleano    | NOT NULL, valor default TRUE                  |
| unidade        | CHAR(5)     | Valor default 'un'                            |
| tipo_comissao  | ENUM('s', 'f', 'p') | NOT NULL, valor default                        |
| codigo_ctg     | INT         | NOT NULL, chave estrangeira para a tabela de categorias |
| foto           | LONGBLOB    |                                             |

O campo `tipo_comissao` é do tipo enumerado e pode ser implementado através de botões de rádio. Seus valores representam as seguintes opções:

- 's': Sem comissão
- 'f': Comissão fixa
- 'p': Percentual de comissão

O campo `ativo` representa se o produto está ativo ou não no cadastro e pode ser implementado através de uma caixa de verificação.

O campo `codigo_ctg` será uma chave estrangeira, fazendo a restrição de integridade referencial com o campo de mesmo nome na tabela `categoria`. Na interface web, deverá ser mostrada uma listagem das categorias para que o usuário possa fazer a escolha.

## Tabela de Categorias

A tabela de categorias terá a seguinte estrutura:

| Campo          | Tipo        | Restrição                                   |
|----------------|-------------|---------------------------------------------|
| codigo_ctg     | INT         | NOT NULL, chave primária                     |
| descricao_ctg  | VARCHAR(50) | Único (UNIQUE) e NOT NULL                    |

Não é necessário criar um CRUD para as categorias. Pode-se cadastrar algumas categorias manualmente, diretamente na tabela.

O CRUD deverá reproduzir o comportamento final do sistema mostrado no roteiro já citado, mas sendo aplicado para o cadastro de produtos.
