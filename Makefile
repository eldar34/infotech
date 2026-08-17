.DEFAULT_GOAL := help

# Current user ID and group ID except MacOS where it conflicts with Docker abilities
ifeq ($(shell uname), Darwin)
    export UID=1000
    export GID=1000
else
    export UID=$(shell id -u)
    export GID=$(shell id -g)
endif

DOCKER_COMPOSE_DEV := docker compose -f docker/docker-compose.yml --env-file .env

init: build composer-install

build: ## Build docker image
	$(DOCKER_COMPOSE_DEV) build

up: ## Up the dev environment
	$(DOCKER_COMPOSE_DEV) up -d

down: ## Down the dev environment
	$(DOCKER_COMPOSE_DEV) down --remove-orphans

clear: ## Remove development docker containers and volumes
	$(DOCKER_COMPOSE_DEV) down --volumes --remove-orphans

shell: ## Get into container shell
	$(DOCKER_COMPOSE_DEV) exec -u $(UID):$(GID) app-php bash

composer-install: ## Run composer install
	$(DOCKER_COMPOSE_DEV) run --rm app-php composer install

composer-update: ## Run composer update
	$(DOCKER_COMPOSE_DEV) run --rm app-php composer update

migrate-up: ## Run migrations
	$(DOCKER_COMPOSE_DEV) run --rm app-php php yii migrate --interactive=0

migrate-down-all: ## Rollback all migrations
	$(DOCKER_COMPOSE_DEV) run --rm app-php php yii migrate/down all --interactive=0

migrate-refresh: migrate-down-all ## Refresh all migrations (down all and up)
	$(DOCKER_COMPOSE_DEV) run --rm app-php php yii migrate --interactive=0

fixtures-load: ## Load test fixtures into the database
	$(DOCKER_COMPOSE_DEV) run --rm app-php php yii fixture/load "*" --interactive=0

queue-run: ## Run outstanding queue jobs once
	$(DOCKER_COMPOSE_DEV) run --rm app-php php yii queue/run --isolate=0 -v

queue-listen: ## Listen to the queue daemon continuously
	$(DOCKER_COMPOSE_DEV) exec app-php php yii queue/listen

phpunit-test: ## Listen to the queue daemon continuously
	$(DOCKER_COMPOSE_DEV) exec app-php composer phpunit

# Output the help for each task, see https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)
