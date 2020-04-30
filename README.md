Для разворачивание проекта:
1. Склонируйте репозиторий на локальную машину
2. Требования: PHP > 7.2.2
3. Переименуйте env.example в .env и укажите настройки базы данных
4. Запустите команду composer install
5. Запустите команду php artisan migrate
6. Запустите команду composer dump-autoload
7. Запустите команду php artisan db:seed
8. php artisan storage:link