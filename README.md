# php-framework
Simple PHP Framework based on composer PSR-04 standard

# Installation
1. Generate custom docker image
```
docker build -t php82-cli -f Dockerfile-Php-Cli .
docker build -t php82-fpm -f Dockerfile-Php-Fpm .
```

3. Up the container
``` docker compose up -d ```

4. Install composer and required library
``` docker compose exec php-fpm composer install ```

# Testing
- http://localhost:8080/v1/user/login

# How to use this framework
- Refer /app/routes.php
- Refer sample class /app/api/v1/User/Auth.php

# Supported Database
- Refer /app/helpers
   1. Mysql
   2. MongoDB
