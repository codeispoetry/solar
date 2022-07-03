up:
	docker-compose up -d && echo Port 9099

build:
	docker-compose up --build -d

stop:
	docker-compose stop

shell:
	docker-compose exec webserver bash

down:
	docker-compose down

node-shell:
	docker-compose exec node bash

compile:
	docker-compose exec node npm run build:dev

log:
	docker-compose logs -f node

deploy:
	rsync -r code/dist pi:/var/www/html/solar

get-db:
	rsync pi:/var/www/html/solar/dist/data/solar.db code/dist/data/solar.db

