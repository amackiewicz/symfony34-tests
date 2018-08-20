
help:  ## Show this help message.
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

run:  ## Runs Symfony webserver
	$(MAKE) check-security
	php bin/console server:run

logs:  ## start to output logs to stdout
	php bin/console server:logs

check-security:  ## checking security bugs in dependencies
	DB_HOST=webcitron.eu php bin/console security:check

db-sync-struct:
	php bin/console doctrine:generate:entities AppBundle
	php bin/console doctrine:schema:update --force

db-load-data:
	php bin/console doctrine:fixtures:load --no-interaction