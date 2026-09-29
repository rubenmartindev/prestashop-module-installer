-include .env
export

HOST_UID        ?= $(shell id -u)
HOST_GID        ?= $(shell id -g)
PS_VERSION_TAG  ?= 9
PS_HTTP_PORT    ?= 80

COMPOSE_PROJECT_NAME  := prestashop-module-installer-$(subst .,-,$(PS_VERSION_TAG))
COMPOSE               := docker compose --project-name $(COMPOSE_PROJECT_NAME)

ARGS = $(filter-out $@, $(MAKECMDGOALS))

.DEFAULT_GOAL = help
.PHONY        : help build up down restart logs ps shell

## ——  🐳 🐧 The Docker Makefile 🐧 🐳 ——————————————————————————————————

help: ## Show this help
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

build: ## Build the PrestaShop image
	@$(COMPOSE) build --pull $(ARGS)

up: ## Start the PrestaShop container
	@mkdir -p "./.prestashop/$(PS_VERSION_TAG)"
	@$(COMPOSE) up --wait --detach $(ARGS)

down: ## Stop and remove the PrestaShop container
	@$(COMPOSE) down --remove-orphans $(ARGS)

down-hard: ## Stop and delete containers, volumes and the installation of PrestaShop
	@$(COMPOSE) down \
		--remove-orphans \
		--volumes \
		$(ARGS)
	@if [ -d "./.prestashop/$(PS_VERSION_TAG)" ]; then \
		rm -rf "./.prestashop/$(PS_VERSION_TAG)"; \
	fi

restart: ## Restart the PrestaShop instance
	@$(COMPOSE) restart $(ARGS)

logs: ## Follow the container logs
	@$(COMPOSE) logs --follow $(ARGS)

ps: ## Show the container status
	@$(COMPOSE) ps $(ARGS)

shell: ## Open a shell in the PrestaShop container
	@$(COMPOSE) exec --user www-data prestashop bash $(ARGS)

%:
	@:
