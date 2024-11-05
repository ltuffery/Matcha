DC := docker compose

all: up

up:
	$(DC) up --build

down:
	$(DC) down

fclean: down
	@docker image rm $$(docker image ls -aq)  2>/dev/null || echo No image to delete
	@docker volume rm $$(docker volume ls -q) 2>/dev/null || echo No volume to delete
	docker system prune -af --volumes
	rm -rf websocket/node_modules
	rm -rf frontend/node_modules

re: fclean up

.PHONY: all re clean fclean up down