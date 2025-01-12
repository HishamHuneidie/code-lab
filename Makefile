# Make config
.DEFAULT_GOAL := default

# Variables
DOCKER_COMPOSE = docker compose
DOCKER_MAIN_CONTAINER = app
DOCKER_DB_CONTAINER = db
DOCKER_PREFIX = code-lab
DB_USER = db_user
DB_NAME = code-lab-db
DB_PASS = 123
TREE_FILE = tree.txt

# Show a small explanation of this Makefile and its commands
.PHONY: help
help:
	php ./bin/make_help help

# Build containers and install composer dependencies
.PHONY: build
build:
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} down
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} up -d --build
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} exec ${DOCKER_MAIN_CONTAINER} composer install
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} exec ${DOCKER_MAIN_CONTAINER} npm install
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} exec ${DOCKER_MAIN_CONTAINER} npm run dev
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} exec ${DOCKER_MAIN_CONTAINER} bin/make_help hello

# Start containers
.PHONY: start
start:
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} stop
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} up -d
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} exec ${DOCKER_MAIN_CONTAINER} bin/make_help hello

# Restart containers
.PHONY: restart
restart:
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} restart
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} exec ${DOCKER_MAIN_CONTAINER} bin/make_help hello

# Stop containers
.PHONY: stop
stop:
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} stop

# Remove containers
.PHONY: remove
remove:
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} down

# Show logs
.PHONY: logs
logs:
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} logs -f --tail=30

# Access to the main container terminal
.PHONY: bash
bash:
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} exec ${DOCKER_MAIN_CONTAINER} bash

# Access to the database container terminal
.PHONY: db-bash
db-bash:
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} exec ${DOCKER_DB_CONTAINER} mariadb -u ${DB_USER} -D ${DB_NAME} -p

# Clean cache
.PHONY: composer-clear
composer-clear:
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} exec ${DOCKER_MAIN_CONTAINER} php bin/console cache:clear

# Install a vendor package
.PHONY: composer-add
composer-add:
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} exec ${DOCKER_MAIN_CONTAINER} composer require ${vendor}

# Install a npm package
.PHONY: npm-add
npm-add:
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} exec ${DOCKER_MAIN_CONTAINER} npm i ${package}

# Update/Generate directory tree
.PHONY: tree
tree:
	${DOCKER_COMPOSE} -p ${DOCKER_PREFIX} exec ${DOCKER_MAIN_CONTAINER} tree -L 6 -I "var|vendor|node_modules|public" > ${TREE_FILE}

# Update/Generate directory tree before commit
.PHONY: tree-commit
tree-commit:
	make tree
	git add ${TREE_FILE}

# This command is showed when the used command does not exist
.DEFAULT:
	php ./bin/make_help errorCommand ${MAKECMDGOALS}
