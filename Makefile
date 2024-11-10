first-install:
	composer install --ignore-platform-reqs --no-dev

update-dep:
	composer update

update:
	git pull
	vendor/bin/phinx migrate

full-update:
	git pull
	composer update
	vendor/bin/phinx migrate

require:
	composer require $(get)

migrate:
	vendor/bin/phinx migrate

migrate-status:
	vendor/bin/phinx status

seed:
	vendor/bin/phinx seed:run
