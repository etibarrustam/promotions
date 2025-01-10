COMMON_COMPOSE_FILE := .docker/docker-compose.yaml
JSON_COMPOSE_FILE := .docker/docker-compose.json.yaml
DB_COMPOSE_FILE := .docker/docker-compose.db.yaml
COMPOSE_CMD := $(shell if [ -x "$$(command -v docker-compose)" ]; then echo "docker-compose"; else echo "docker compose"; fi)
ENV_FILE ?= .env.db
DB_CONTAINER ?= database

composer-install:
	@echo "Installing Composer dependencies..."
	$(COMPOSE_CMD) -f $(COMMON_COMPOSE_FILE) run --rm app composer install
	@echo "Dependencies installed successfully."

db-seed:
	@echo "Seeding the database using $(ENV_FILE) and container $(DB_CONTAINER)..."

	@ATTEMPTS=0; \
	DB_HOST=$$(grep DB_HOST $(ENV_FILE) | cut -d '=' -f2); \
	DB_ROOT_PASSWORD=$$(grep DB_ROOT_PASSWORD $(ENV_FILE) | cut -d '=' -f2); \
	while ! docker exec $(DB_CONTAINER) mysql -h "$$DB_HOST" -u root -p"$$DB_ROOT_PASSWORD" -e "SELECT 1" >/dev/null 2>&1; do \
		if [ $$ATTEMPTS -ge 20 ]; then \
			echo "Error: MySQL in container '$(DB_CONTAINER)' did not become ready in time. Check your database configuration or logs for issues."; \
			exit 1; \
		fi; \
		echo "Waiting for MySQL in container '$(DB_CONTAINER)' to become ready (attempt $$ATTEMPTS)..."; \
		sleep 1; \
		ATTEMPTS=$$((ATTEMPTS+1)); \
	done

	@echo "Seeding the database..."
	@DB_NAME=$$(grep DB_NAME $(ENV_FILE) | cut -d '=' -f2); \
	DB_ROOT_PASSWORD=$$(grep DB_ROOT_PASSWORD $(ENV_FILE) | cut -d '=' -f2); \
	DB_HOST=$$(grep DB_HOST $(ENV_FILE) | cut -d '=' -f2); \
	TABLE_EXISTS=$$(docker exec $(DB_CONTAINER) mysql -h "$$DB_HOST" -u root -p"$$DB_ROOT_PASSWORD" -D "$$DB_NAME" -e "SHOW TABLES LIKE 'products';" -s --skip-column-names); \
		if [ -n "$$TABLE_EXISTS" ]; then \
			echo "Database '$$DB_NAME' in container '$(DB_CONTAINER)' is already configured. Skipping seeding."; \
		else \
			echo "Seeding the database '$$DB_NAME' in container '$(DB_CONTAINER)'..."; \
			docker exec -i $(DB_CONTAINER) mysql -h "$$DB_HOST" -u root -p"$$DB_ROOT_PASSWORD" "$$DB_NAME" < .docker/db/db_seed.sql || { \
				echo "Error: Failed to seed the database in container '$(DB_CONTAINER)'."; \
				exit 1; \
			}; \
			echo "Database '$$DB_NAME' in container '$(DB_CONTAINER)' seeded successfully!"; \
		fi
	@echo "Database seeding process completed in container '$(DB_CONTAINER)'!"

json-setup:
	@echo "Setting up JSON data source..."

	@if [ -f .env.json ]; then \
		echo ".env.json already exists. Skipping generation."; \
	else \
		if [ ! -f .env.dist.json ]; then \
			echo "Error: .env.dist.json not found!"; \
			exit 1; \
		fi; \
		echo "Generating .env.json from .env.dist.json..."; \
		cp .env.dist.json .env.json; \
		echo "Environment file generated successfully: .env.json"; \
	fi

	@cat .env.json

	@echo "Starting JSON data source with .env.json..."
	$(COMPOSE_CMD) --env-file .env.json -f $(COMMON_COMPOSE_FILE) -f $(JSON_COMPOSE_FILE) up -d
	@echo "Installing dependencies..."
	$(MAKE) composer-install
	@echo "JSON data source started successfully."
	@APP_PORT=$$(grep APP_PORT .env.json | cut -d '=' -f2); \
	echo "You can use the URL http://localhost:$$APP_PORT to access the endpoints"

db-setup:
	@echo "Setting up database data source..."

	@if [ -f .env.db ]; then \
		echo ".env.db already exists. Skipping generation."; \
	else \
		if [ ! -f .env.dist.db ]; then \
			echo "Error: .env.dist.db not found!"; \
			exit 1; \
		fi; \
		echo "Generating .env.db from .env.dist.db..."; \
		cp .env.dist.db .env.db; \
	fi

	@echo "Starting database data source with .env.db..."
	$(COMPOSE_CMD) --env-file .env.db -f $(COMMON_COMPOSE_FILE) -f $(DB_COMPOSE_FILE) up -d
	@echo "Installing dependencies..."
	$(MAKE) composer-install
	$(MAKE) db-seed ENV_FILE=.env.db DB_CONTAINER=promotions_db
	@APP_PORT=$$(grep APP_PORT .env.db | cut -d '=' -f2); \
	echo "You can use the URL http://localhost:$$APP_PORT to access the endpoints"

test:
	@if [ -f .env.testing ]; then \
		echo ".env.testing already exists. Skipping generation."; \
	else \
		if [ ! -f .env.dist.testing ]; then \
			echo "Error: .env.dist.testing not found!"; \
			exit 1; \
		fi; \
		echo "Generating .env.testing from .env.dist.testing..."; \
		cp .env.dist.testing .env.testing; \
		echo "Environment file generated successfully: .env.testing"; \
	fi
	@echo "Starting test environment..."
	$(COMPOSE_CMD) --env-file .env.testing -f $(COMMON_COMPOSE_FILE) -f .docker/docker-compose.test.yaml up -d
	$(MAKE) db-seed ENV_FILE=.env.testing DB_CONTAINER=promotions_test_db_host
	@echo "Running tests..."
	$(COMPOSE_CMD) --env-file .env.testing -f $(COMMON_COMPOSE_FILE) -f .docker/docker-compose.test.yaml exec app vendor/bin/phpunit --testdox $(ARGS) || true
	$(MAKE) test-clean
	@echo "Tests completed successfully."

test-clean:
	@echo "Cleaning up test environment..."
	$(COMPOSE_CMD) --env-file .env.testing -f $(COMMON_COMPOSE_FILE) -f .docker/docker-compose.test.yaml down -v
	@echo "Removing  database data folder..."
	@if [ -d .docker/.db_data_test ]; then \
		rm -rf .docker/.db_data_test; \
		echo ".docker/.db_data_test has been removed."; \
	else \
		echo ".docker/.db_data_test does not exist. Skipping."; \
	fi
	@echo "Test environment cleaned up."

stop:
	@echo "Stopping all services using $(ENV_FILE)..."
	$(COMPOSE_CMD) --env-file $(ENV_FILE) -f $(COMMON_COMPOSE_FILE) down
	@echo "All services stopped."

clean:
	@echo "Cleaning up resources using $(ENV_FILE)..."
	$(COMPOSE_CMD) --env-file $(ENV_FILE) -f $(COMMON_COMPOSE_FILE) down -v
	@echo "Removing  database data folder..."
	@if [ -d .docker/.db_data ]; then \
		rm -rf .docker/.db_data; \
		echo ".docker/.db_data has been removed."; \
	else \
		echo ".docker/.db_data does not exist. Skipping."; \
	fi
	@echo "Clean-up completed."