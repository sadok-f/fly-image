# Enabling Xdebug

## On macOS

- Create an alias for localhost network

```sh
sudo ifconfig en0 alias 10.254.254.254 255.255.255.0
```

- Run the Flyimg image with variable environment

```sh
docker run -itd -p 8080:80 -e PHP_XDEBUG_ENABLED=1 -e XDEBUG_CONFIG=remote_host=10.254.254.254 -e PHP_IDE_CONFIG=serverName=Xdebug -v (pwd):/var/www/html --name flyimg flyimg
```

- Add Xdebug params to php.ini

```sh
docker exec flyimg sh -c 'printf "xdebug.idekey=PHPSTORM
xdebug.default_enable=0
xdebug.remote_enable=1
xdebug.remote_autostart=0
xdebug.remote_connect_back=0
xdebug.profiler_enable=0
xdebug.profiler_output_dir=/var/www/html/xdebug
xdebug.remote_host=10.254.254.254
xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini'
```

- Restart PHP-fpm

```sh
docker exec flyimg s6-svc -r /var/run/s6/services/php-fpm
```

## PhpStorm

- Add new server with name `Xdebug`, Host: `localhost`, Port: `80`, Debuger: `Xdebug`
- Use Path mapping and map the local file path with the path on the server `/var/www/html`
- Add new PHP Remote Debug with Server: `Xdebug`, ide key (session id): `PHPSTORM`
- Add Breakpoints to test
