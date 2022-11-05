Тестовое задание, выполненное на Laravel. 

"Нужно написать web сервис, который конвертирует валюты. 
В качестве источника курса можно использовать SOAP сервис ЦБ. Примеры запросов в файле"

В основном делал упор на расширяемость и взаимозаменяемость, поэтому реализовал 2 источника курса валют, 
которые меняются через файл .env;

Инструкция по установке:

```
composer install
cd exchange_rates
cp .env.example .env
```


При смене источника в .env файле не забывайте очистить конфиги и кэш 
```
php artisan config:cache && php artisan config:clear && php artisan cache:clear
```

Также можно запустить тесты через Makefile
```
make test
```
