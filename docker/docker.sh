composer install
npm install
php bin/console cache:clear
php bin/console cache:warmup
php bin/console doctrine:database:create
php bin/console doctrine:migration:migrate
exec apache2-foreground