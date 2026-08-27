install:
	composer install

validate:
	composer validate

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin tests\DifferTest.php

test:
	composer exec --verbose phpunit tests -- --testdox

test-coverage:
	composer test-coverage

test-coverage-html:
	composer test-coverage-html

test-coverage-xml:
	composer test-coverage-xml
