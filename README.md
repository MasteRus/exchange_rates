Тестовое задание, выполненное на Laravel. 

"Нужно написать web сервис, который конвертирует валюты. 
В качестве источника курса можно использовать SOAP сервис ЦБ. Примеры запросов в файле"
```
### Должны работать следующие 3 запроса
GET http://localhost/100RUB/asUSD
# В ответ ожидается JSON вида { currency: "USD", sum: 1.44 }

###
GET http://localhost/15USD/asRUB
# В ответ ожидается JSON вида { currency: "RUB", sum: 1040.69 }

###
GET http://localhost/1USD/asEUR

# В ответ ожидается JSON вида { currency: "EUR", sum: 0.88 }

### Запрос для получения курса валют
POST /DailyInfoWebServ/DailyInfo.asmx HTTP/1.1
Host: www.cbr.ru
Content-Type: text/xml; charset=utf-8
SOAPAction: "http://web.cbr.ru/GetCursOnDateXML"

<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
    <soap:Body>
        <GetCursOnDateXML xmlns="http://web.cbr.ru/">
            <On_date>2020-06-11</On_date>
        </GetCursOnDateXML>
    </soap:Body>
</soap:Envelope>
```
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
php artisan config:clear && php artisan cache:clear
```

Также можно запустить тесты через Makefile
```
make test
```
