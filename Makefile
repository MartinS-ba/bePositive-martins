-include .env
export

ROOT_DIR=./

############################################################
# PROJECT ##################################################
############################################################
.PHONY: project
project: install setup

.PHONY: init
init:
	cp config/local.neon.example config/local.neon

.PHONY: install
install:
	composer install

.PHONY: setup
setup:
	mkdir -p var/tmp var/log
	chmod 0777 var/tmp var/log

.PHONY: clean
clean:
	find var/tmp \( -path 'var/tmp/proxies' -prune \) -o -mindepth 1 ! -name '.gitignore' -exec rm -rf {} +
	find var/log -mindepth 1 ! -name '.gitignore' -type f -or -type d -exec rm -rf {} +

############################################################
# DEVELOPMENT ##############################################
############################################################
.PHONY: qa
qa: cs phpstan

.PHONY: cs
cs:
	vendor/bin/codesniffer app tests

.PHONY: csf
csf:
	vendor/bin/codefixer app tests

.PHONY: phpstan
phpstan:
	vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=512M

.PHONY: tests
tests:
	vendor/bin/tester -s -p php --colors 1 -C tests

.PHONY: coverage
coverage:
	vendor/bin/tester -s -p phpdbg --colors 1 -C --coverage ./coverage.xml --coverage-src ./app tests

.PHONY: dev
dev:
	XDEBUG_MODE=debug NETTE_DEBUG=1 NETTE_ENV=dev php -S 0.0.0.0:8000 -t www

.PHONY: build
build:
	composer install
	NETTE_DEBUG=1 bin/console orm:schema-tool:drop --force --full-database
	NETTE_DEBUG=1 bin/console migrations:migrate --no-interaction
	NETTE_DEBUG=1 bin/console orm:generate-proxies --no-interaction

############################################################
# DEPLOYMENT ###############################################
############################################################
.PHONY: deploy
deploy:
	$(MAKE) clean
	$(MAKE) project
	$(MAKE) build
	$(MAKE) clean

############################################################
# DOCKER ###################################################
############################################################


.docker-common:
	@if [ "$(PROJECT_ROOT)" != "." ] && [ "$(PROJECT_ROOT)" != "$(shell pwd)" ]; then \
		rsync -a --delete .docker $(PROJECT_ROOT) ;\
		cp -fp docker-compose.yml $(PROJECT_ROOT) ;\
		cp -fp .env $(PROJECT_ROOT) ;\
	fi

docker-down:
	make .docker-common
	cd $(PROJECT_ROOT) && docker-compose -f docker-compose.yml down

docker-up:
	make .docker-common
	cd $(PROJECT_ROOT) && docker-compose -f docker-compose.yml up --force-recreate --build
