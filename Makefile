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
	rsync -rvt ./code/dist/ --exclude '*.mp*' sharepic:/var/www/rahelrose.de/


