Requirements:

1. Docker >= 17.05

How to

1. Run docker-compose up -d
2. Go into container: docker exec -it sf4_php_1 bash
3. Install dependencies: composer install
4. Run migraton: bin/console doctrine:migrations:migrate
5. Load data: bin/console doctrine:fixtures:load
6. Go to http://0.0.0.0:8080/api to test API
7. Example post data:

{
  "barberId": 1,
  "startAt": "2020-03-28 15:00",
  "endAt": "2020-03-28 17:00"
}