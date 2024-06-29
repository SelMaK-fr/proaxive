first-install:
	composer install --ignore-platform-reqs --no-dev

update-dep:
	composer update

update:
	git status
	vendor/bin/phinx migrate

require:
	composer require $(get)

migrate:
	vendor/bin/phinx migrate

seed:
	vendor/bin/phinx seed:run
