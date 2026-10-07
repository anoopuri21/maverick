<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Local setup

Two supported paths:

| | Guide |
|---|---|
| **Fresh clone** on a new machine | [`docs/WINDOWS_LARAGON_SETUP.md`](docs/WINDOWS_LARAGON_SETUP.md) |
| **Copying the whole project folder** off an existing machine (keeps `vendor/`, `.env`, `APP_KEY` byte-identical) | [`docs/WINDOWS_FOLDER_MIGRATION.md`](docs/WINDOWS_FOLDER_MIGRATION.md) |
| Shared hosting deploy | [`docs/SHARED_HOSTING.md`](docs/SHARED_HOSTING.md) |

Create the first Filament admin (the panel requires `is_admin = true`):

```bash
php artisan admin:create
```

### Things that surprise people

**PHP 8.4+ is required for an uncached config**, despite the `php: ^8.3` constraint in `composer.json`.
`config/database.php` references `Pdo\Mysql::ATTR_SSL_CA`, and the `Pdo\Mysql` class only exists in PHP 8.4+.
Laravel evaluates every config file on boot, so on PHP 8.3 with `pdo_mysql` loaded the app dies immediately with
`Class "Pdo\Mysql" not found` - for web requests *and* `php artisan`, regardless of `DB_CONNECTION`.
An environment running `php artisan config:cache` never evaluates the file and so can appear to work on 8.3;
that illusion ends the moment the cache is cleared. Check any machine with:

```bash
php -r "echo class_exists('Pdo\Mysql') ? 'OK (8.4+)' : 'TOO OLD';"
```

**Vite is not wired up.** No Blade template uses `@vite`; the front end is served from committed files in
`public/assets`, `public/css` and `public/js`, and Filament publishes its own assets during `composer install`.
`npm install` / `npm run build` are **not** needed to run the app - only to work on `resources/css` or `resources/js`.

**Site copy lives in `database/settings/`** (93 `spatie/laravel-settings` migrations), not in `database/seeders/`.
They run as part of `php artisan migrate`. Skipping `migrate` leaves the front end empty.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Shared hosting / production

See **[docs/SHARED_HOSTING.md](docs/SHARED_HOSTING.md)** for the full hardening checklist (config/route/view cache, APP_KEY rules, Cloudinary media, queues, OPCache).

Quick deploy (document root must be `public/`):

```bash
alias php='/opt/cpanel/ea-php83/root/usr/bin/php'
alias composer='/opt/cpanel/ea-php83/root/usr/bin/php /usr/local/bin/composer'
cd ~/demo.vsinfosys.in
git pull origin main
composer install --no-dev --optimize-autoloader --no-interaction
bash scripts/shared-hosting-optimize.sh
```

Never run `php artisan key:generate` on an existing production `.env`.
