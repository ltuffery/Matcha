DC := docker compose -f ./docker-compose.core.yml

all: set_app_host dev

dev:
	$(DC) -f ./docker-compose.dev.yml up --build

prod: set_app_host
	$(DC) -f ./docker-compose.prod.yml up --build

down:
	$(DC) down

fclean: down
	@docker image rm $$(docker image ls -aq)  2>/dev/null || echo No image to delete
	@docker volume rm $$(docker volume ls -q) 2>/dev/null || echo No volume to delete
	docker system prune -af --volumes
	rm -rf websocket/node_modules
	rm -rf frontend/node_modules
	rm -rf frontend/dist
	rm -rf api/storage/photos/*

re: fclean dev

set_app_host:
	@if [ "$(wildcard .env)" = "" ]; then \
		(echo ".env Not Found"; false); \
	else \
		sed -i '/APP_HOST/d' .env; \
		echo -e "\nAPP_HOST=$(shell hostname -i)" >> .env; \
	fi

.PHONY: all re clean fclean up down