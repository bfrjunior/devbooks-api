# devsbook

A DevBooks API é uma API social que permite aos desenvolvedores interagirem com outros membros da comunidade, compartilhando postagens, comentários e curtindo o conteúdo. Ela oferece funcionalidades semelhantes às do Facebook.
## 🚀 Começando

Para usar a DevBooks API, você deve atender aos seguintes requisitos:

PHP 8 ou superior
Composer
Laravel 7
Banco de dados MySQL
Outras dependências específicas do projeto


### 📋 Endpoints
A DevBooks API fornece os seguintes endpoints para interagir com a plataforma:

Autenticação
Login:
Endpoint: POST /auth/login
Descrição: Permite que os usuários façam login na plataforma. Os usuários podem fornecer suas credenciais (nome de usuário e senha) para autenticação.

Logout:
Endpoint: POST /auth/logout
Descrição: Permite que os usuários façam logout da plataforma. Após o logout, o usuário não terá mais acesso às funcionalidades da API que exigem autenticação.

Refresh Token:
Endpoint: POST /auth/refresh
Descrição: Renova o token de autenticação do usuário, estendendo a validade da sessão. Isso é útil para manter os usuários logados por um período mais longo sem a necessidade de fazer login novamente.
Gerenciamento de Usuários

Criar Usuário:
Endpoint: POST /user
Descrição: Permite que os usuários se registrem na plataforma. Os usuários podem fornecer informações como nome, e-mail e senha para criar uma conta.

Atualizar Usuário:
Endpoint: PUT /user
Descrição: Permite que os usuários atualizem suas informações de perfil, como nome, sobrenome e informações de contato.

Atualizar Avatar:
Endpoint: POST /user/avatar
Descrição: Permite que os usuários atualizem sua foto de perfil (avatar). Eles podem fazer o upload de uma nova imagem para representar sua identidade visual na plataforma.

Atualizar Capa:
Endpoint: POST /user/cover
Descrição: Permite que os usuários atualizem a imagem de capa do seu perfil. Isso permite que eles personalizem ainda mais a aparência do seu perfil.
Feed de Notícias e Interação Social

Ler Feed de Notícias:
Endpoint: GET /feed
Descrição: Retorna as postagens mais recentes do feed de notícias. Os usuários podem ver as atualizações de outros usuários que eles seguem.

Ler Feed de Usuário:
Endpoint: GET /user/feed ou GET /user/{id}/feed
Descrição: Retorna as postagens do feed de notícias de um usuário específico. Isso permite que os usuários visualizem o feed de um usuário específico.

Seguir Usuário:
Endpoint: POST /user/{id}/follow
Descrição: Permite que os usuários sigam outros usuários na plataforma. Isso permite que eles acompanhem as atividades e postagens dos usuários que seguem.

Ler Seguidores de Usuário:
Endpoint: GET /user/{id}/followers
Descrição: Retorna a lista de seguidores de um usuário específico. Os usuários podem ver quem está seguindo seu perfil.

Ler Fotos de Usuário:
Endpoint: GET /user/{id}/photos ou GET /user/photos
Descrição: Retorna as fotos compartilhadas por um usuário específico ou pelo próprio usuário, respectivamente.
Postagens e Interatividade

Criar Postagem:
Endpoint: POST /feed
Descrição: Permite que os usuários criem novas postagens no feed de notícias. Eles podem compartilhar texto, imagens e outros conteúdos.

Curtir Postagem:
Endpoint: POST /post/{id}/like
Descrição: Permite que os usuários curtam uma postagem específica. Isso indica que eles gostaram do conteúdo compartilhado.

Comentar em Postagem:
Endpoint: POST /post/{id}/comment
Descrição: Permite que os usuários adicionem comentários a uma postagem específica. Isso permite interações adicionais sobre o conteúdo compartilhado.
Pesquisa

Pesquisar Usuários e Conteúdo:
Endpoint: GET /search
Descrição: Permite que os usuários pesquisem outros usuários, postagens e conteúdo relacionado à plataforma. Eles podem encontrar informações e perfis relevantes.
Lembre-se de que essas descrições são fictícias e devem ser atualizadas com informações reais e detalhadas sobre cada endpoint da sua API. Certifique-se de incluir informações sobre os parâmetros, respostas esperadas e quaisquer requisitos de autenticação ou autorização.

### 🔧 Instalação
```
git clone https://github.com/bfrjunior/devbooks-api.git
```
* Acesse o diretório do projeto:
```
cd devbooks-api
```
* Instale as dependências com o Composer:
```
composer install
```
* Execute a migração do banco de dados para criar as tabelas necessárias:
```
php artisan migrate
```
* Inicie o servidor:
```
php artisan serve
```
A API estará disponível em http://localhost:8000 por padrão.


## 🛠️ Construído com

* Mysql
* [Laravel](https://laravel.com/)
* [PHP](https://www.php.net/)














