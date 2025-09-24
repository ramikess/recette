ifneq (,)
.error This Makefile requires GNU Make.
endif

# Commande Docker Compose
DOCKER_COMPOSE_V2_EXISTS := $(shell command -v docker compose 2> /dev/null)
DOCKER_COMPOSE_CMD = $(if $(DOCKER_COMPOSE_V2_EXISTS),docker compose,docker-compose)
DOCKER_COMPOSE = $(DOCKER_COMPOSE_CMD) -p networth
YARN = $(DOCKER_COMPOSE_CMD) -p networth run yarn

# Environnement par défaut
ENV=dev

# ----------------------
# BASH DANS LES CONTENEURS
# ----------------------

bash-php:
	docker exec -ti networth-php bash

bash-nginx:
	docker exec -ti networth-nginx bash

bash-db:
	docker exec -ti networth-database bash

bash-yarn: ## connexion en bash dans le container yarn
	$(YARN) bash
