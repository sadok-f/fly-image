## Run Unit Tests

```sh
docker exec flyimg vendor/bin/phpunit
```

Generate Html Code Coverage:

```sh
docker exec flyimg vendor/bin/phpunit --coverage-html build/html
```
