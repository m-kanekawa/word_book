upd: # up containers (background)
	docker compose up -d

php: # login to dict-php container
	docker compose exec dict-php /bin/bash

stop: # stop containers
	docker compose stop

clean: # stop containers and remove
	docker compose stop
	docker compose rm

