test: backend-test
test-unit: backend-test-unit
test-feature: backend-test-feature

backend-test:
	docker exec exchange-rates-app php artisan test
backend-test-unit:
	docker exec exchange-rates-app php artisan test --testsuite=Unit
backend-test-feature:
	docker exec exchange-rates-app php artisan test --testsuite=Feature
