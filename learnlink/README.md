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

The default `.env.example` uses SQLite. Ensure `database/database.sqlite` exists before migration. For MySQL, set `DB_CONNECTION=mysql` and provide `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`.

## Ubuntu deployment

1. Install PHP 8.3 with `mbstring`, `xml`, `curl`, `zip`, `sqlite3` or `mysql`, Composer, Node.js, and Nginx.
2. Clone the repository, create `.env` from `.env.example`, set `APP_ENV=production`, `APP_DEBUG=false`, a production `APP_URL`, and production database/mail credentials.
3. Run `composer install --no-dev --optimize-autoloader`, `npm ci`, `npm run build`, `php artisan migrate --force`, and `php artisan storage:link`.
4. Set the web-server document root to `learnlink/public`, not the project root. Ensure the PHP-FPM user can write to `storage` and `bootstrap/cache`.
5. Cache production configuration after `.env` is complete:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

User uploads are runtime data at `storage/app/public/uploads` and are deliberately ignored by Git. Back them up separately from the source repository.

## Verification

```bash
php artisan test
php artisan route:list
```
