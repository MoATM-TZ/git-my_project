# LearnLink

LearnLink is a Laravel application for sharing, finding, downloading, voting on, and discussing learning materials. It replaces the legacy procedural PHP pages with routes, controllers, Blade views, Eloquent models, migrations, and Laravel's built-in authentication/session middleware.

## What was migrated

- `index.php`, `search.php`, `search_live.php`, and `resource-detail.php` are now home, search, and resource controllers/views.
- Uploads use `Storage::disk('public')`, with metadata saved in `resources`.
- Login, registration, logout, and profile editing use Laravel authentication and CSRF protection.
- Comments, votes, reports, and the reputation ledger are relational models backed by migrations.
- `resources/views/layouts/app.blade.php` replaces the shared header/footer/app shell; Vite builds the CSS and JavaScript from `resources/`.

## Local setup

Requirements: PHP 8.3+, Composer, Node.js 20+, and either SQLite or MySQL.

```bash
cd learnlink
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm ci
npm run build
php artisan storage:link
php artisan serve
```

The shipped `.env.example` targets the Ubuntu/MySQL deployment. Set a unique `APP_KEY`, the VM's public `APP_URL`, and a strong `DB_PASSWORD` before caching configuration.

## Ubuntu deployment

1. Install PHP 8.3 FPM with `mbstring`, `xml`, `curl`, `zip`, and `mysql` extensions, plus Composer, Node.js, MySQL 8.0, and Nginx. Laravel 13 declares PHP 8.3 as its minimum version, so PHP 8.2 is not supported by this lockfile.
2. Clone the repository, create `.env` from `.env.example`, set a production `APP_URL`, `DB_PASSWORD`, and production mail credentials. The template already defaults to `APP_ENV=production`, `APP_DEBUG=false`, and the `learnlink` MySQL database using `laravel_user`.
3. Run `composer install --no-dev --optimize-autoloader`, `npm ci`, `npm run build`, `php artisan migrate --force`, and `php artisan storage:link`.
4. Set the web-server document root to `learnlink/public`, not the project root. Ensure the PHP-FPM user can write to `storage` and `bootstrap/cache`.
5. Cache production configuration after `.env` is complete:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Install [`deploy/nginx/learnlink.conf`](deploy/nginx/learnlink.conf) as the Nginx site (adjust the PHP-FPM socket if needed). It includes the required `try_files $uri $uri/ /index.php?$query_string;` rewrite and a 25 MB upload limit for the application's 20 MB upload limit.

Give PHP-FPM write access only to Laravel's runtime directories, then validate and reload Nginx:

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo find storage bootstrap/cache -type d -exec chmod 775 {} \;
sudo find storage bootstrap/cache -type f -exec chmod 664 {} \;
sudo nginx -t && sudo systemctl reload nginx
```

User uploads are runtime data at `storage/app/public/uploads` and are deliberately ignored by Git. Back them up separately from the source repository.

## Verification

```bash
php artisan test
php artisan route:list
```
