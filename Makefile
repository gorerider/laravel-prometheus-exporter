restart:
	@make stop
	@make start

start:
	@docker-compose up --build -d app

shell:
	@docker-compose exec app bash

stop:
	@docker-compose down
