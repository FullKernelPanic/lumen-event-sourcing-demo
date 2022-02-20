## Setup

```
docker-compose up -d
docker exec -ti app composer install
```

Mysql boots up slowly, may refuse connection on first few attempts.

```
docker exec -ti app composer project-setup
```

## Example

### Create a project

From a lumen command the aggregate will be called and an event will be stored and dispatched to rabbitmq.

```
docker exec -ti app php artisan project:create test_project
```

Queue worker needs to be started to retrieve the message from rabbitmq.

```
docker exec -ti app php artisan queue:work
```

After the commands are executed the app database projects table should contain a new entry.

![PlantUML model](http://www.plantuml.com/plantuml/png/VLBDIiGm4BxlKynH3xv03xAoU11MyEVWGKIcoT16jqcSJ7RrxHqJ8YrASoXb-7xc-v9j4cJ9qs63HWB3Rd-p0JrzoDuYVy4-J-JBoA9WZudW-aXU7XLDPVwHAHCyODyxkRrVGgzYt9J9m257U68dbQG-S75PrQ43P749FKGERsIaCqnOaHMYs9cj4fnFn09t5RyhSBl4juqwi2v553FE9EppalfIkEo348GZyI9FEVJ3VQLFCw8VwchahHBgVyFFdNNFcFk3IoDCb18E119gNsVfl4dC2cfvNF1hBHM5xXJu_V_42bLJqQ7QUJerzxGQiWOisYlfsZeQZODHtRLIb-lfO_XpSxybRFMZp_0R)