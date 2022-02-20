#Setup
```
docker-compose up -d
docker exec -ti app composer install
```
Mysql boots up slowly, may refuse connection on first few attempts.
```
docker exec -ti app composer project-setup
```

#Example
## Create project
From a lumen command the aggregate will be called and an event will be stored and dispatched to rabbitmq.
```
docker exec -ti app php artisan project:create test_project
```

Queue worker needs to be started to retrieve the message from rabbitmq.
```
docker exec -ti app php artisan queue:work
```

After the commands are executed the app database projects table should contain a new entry.