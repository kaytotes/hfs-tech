# Setup

- Clone Repo.
- Setup .env `cp .env.example .env`
- Add Composer Deps:

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

- Build & Start: `./vendor/bin/sail up -d`
- Setup Frontend `./vendor/bin/sail yarn && ./vendor/bin/sail yarn dev`
- Migrate & Seed `./vendor/bin/sail artisan migrate:fresh --seed`
- Visit: `http://localhost`
- Run Tests `./vendor/bin/sail artisan test`

# Stack

- Laravel 10 (Laravel 11 is very fresh and changes scaffold and features a lot).
- Laravel Fortify.
- Laravel Sanctum.
- Vue 3.
- Tailwind.
- Vue Router.
- VueX.