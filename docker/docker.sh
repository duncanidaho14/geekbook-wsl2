cd project/ && composer install
cd project/ && npm install
cd project/ && php bin/console cache:clear
cd project/ && php bin/console cache:warmup
cd project/ && php bin/console doctrine:database:create
cd project/ && php bin/console doctrine:migration:migrate