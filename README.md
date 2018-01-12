Sistema Simples de Pedidos
======


Projeto Exemplo Symfony
  - CRUD de produtos
  - CRUD de clientes
  - Criação de pedidos e adição de itens no carrinho
    - obs: Tomei a liberdade de adicionar um atributo status na entidade Pedido para garantir que não 
    seja adicionado mais nenhum item após o pedido fechado

# Ferramentas utilizadas

  - Symfony Versão 2.8 com Doctrine 2.5 (a versão 2.7 estava dando um bug de timeout ao atualizar o projeto via composer)
  - StofDoctrineExtensionsBundle para criação de slugs em produto e em cliente.
  - Bootstrap v3
  - jQuery com plugins:
    - Datatables: Pesquisa de registro por qualquer atributo
    - Masked Input: Máscara nos campos de data
    - Select 2: Facilitador de busca em elementos select
  



### Instalação

    - Clonar o projeto
```sh
$ composer update
```
