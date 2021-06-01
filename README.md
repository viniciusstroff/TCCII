# TCCII
 Projeto de TCC II de Sistemas de Informação



para rodar o projeto

- composer install
- npm install
- alterar os dados do .env para um banco de dados local 
- php artisan migrate
- php artisan serve
- php artisan key:generate.


dev

- npm run watch
- php artisan serve
- npm run hot (hot-reload)


rodar processo de fila

- php artisan queue:work --queue={nome_da_fila} ou php artisan queue:listen 
