# ABE Challenge - Customer Loyalty System

A Laravel 12 application with Filament 5 admin panel for managing customer credit balances, bonus programs, and rewards.

## Tech Stack

- **Laravel 12** - PHP Framework
- **Filament 5** - Admin Panel
- **Livewire 4** - Reactive Components
- **PHP 8.2+** - Programming Language (PHP 8.5 in Sail environment)
- **SQLite/MySQL** - Database (MySQL 8.4 in Sail environment)
- **Vite** - Frontend Build Tool
- **Tailwind CSS** - Styling
- **Pest** - Testing Framework
- **Docker & Laravel Sail** - Containerized development environment (optional but recommended)

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Environment Configuration](#environment-configuration)
- [Database Setup](#database-setup)
- [Running the Application](#running-the-application)
- [Accessing the Application](#accessing-the-application)
- [Development Workflow](#development-workflow)
- [Using Laravel Sail (Docker Development)](#using-laravel-sail-docker-development)
- [Project Structure](#project-structure)
- [Features](#features)
- [Troubleshooting](#troubleshooting)
- [Testing](#testing)
- [Additional Resources](#additional-resources)

## Prerequisites

Before you begin, ensure you have the following installed on your machine:

**For Local Development (without Docker):**
- **PHP 8.2 or higher** with extensions: BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML
- **Composer 2.x** - PHP dependency manager
- **Node.js 22+** and **npm** - For frontend asset compilation
- **SQLite** (default, no setup needed) OR **MySQL 8.0+** / **PostgreSQL 13+**

**For Docker Development (with Laravel Sail):**
- **Docker** and **Docker Compose** - Required for Sail
- **Node.js 22+** and **npm** - For frontend asset compilation (optional, can run in container)
- Note: Sail provides PHP 8.5 and MySQL 8.4 automatically, so local PHP/MySQL installation is not required

## Installation

### Installation with Laravel Sail (Recommended for Docker Users)

Laravel Sail is already installed and configured in this project. If you prefer using Docker, follow these steps:

1. **Ensure Docker and Docker Compose are installed**
   - Docker Desktop or Docker Engine
   - Docker Compose v2+

2. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

3. **Configure Sail environment variables in `.env`**
   ```env
   APP_PORT=80
   VITE_PORT=5173
   FORWARD_DB_PORT=3306
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=abe_challenge
   DB_USERNAME=sail
   DB_PASSWORD=password
   WWWUSER=1000
   WWWGROUP=1000
   ```

4. **Start Sail containers**
   ```bash
   ./vendor/bin/sail up -d
   ```

5. **Install PHP dependencies**
   ```bash
   ./vendor/bin/sail composer install
   ```

6. **Generate application key**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

7. **Run database migrations**
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

8. **Install Node dependencies**
   ```bash
   ./vendor/bin/sail npm install
   ```

9. **Build frontend assets**
   ```bash
   ./vendor/bin/sail npm run build
   ```

10. **Seed the database** (optional, for sample data)
    ```bash
    ./vendor/bin/sail artisan db:seed
    ```

The application will be available at `http://localhost` (port 80).

### Quick Setup (For Local PHP Installation)

The easiest way to get started with local PHP installation is using the setup script:

```bash
composer run setup
```

This command will:
- Install PHP dependencies
- Copy `.env.example` to `.env`
- Generate application key
- Run database migrations
- Install Node dependencies
- Build frontend assets

**Note:** If you're using Sail, use Sail commands instead (see [Installation with Laravel Sail](#installation-with-laravel-sail-recommended-for-docker-users) above).

### Manual Setup

If you prefer to set up manually, follow these steps:

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd abe-challenge
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Install Node dependencies**
   ```bash
   npm install
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Run database migrations**
   ```bash
   php artisan migrate
   ```

8. **Seed the database** (optional, for sample data)
   ```bash
   php artisan db:seed
   ```

## Environment Configuration

The `.env` file contains important configuration. Key variables to review:

### Application Settings

- `APP_NAME` - Application name
- `APP_ENV` - Set to `local` for development
- `APP_DEBUG` - Set to `true` for development (shows detailed errors)
- `APP_URL` - Application URL
  - Local: `http://localhost:8000`
  - Sail: `http://localhost`

### Sail-Specific Environment Variables

If you're using Laravel Sail, configure these variables:

- `APP_PORT` - Application port (default: `80`)
- `VITE_PORT` - Vite dev server port (default: `5173`)
- `FORWARD_DB_PORT` - MySQL port exposed to host (default: `3306`)
- `WWWUSER` - User ID for file permissions (usually your system user ID, e.g., `1000`)
- `WWWGROUP` - Group ID for file permissions (usually your system group ID, e.g., `1000`)
- `SAIL_XDEBUG_MODE` - Xdebug mode (default: `off`, can be set to `develop,debug,coverage,profile,trace`)

### Database Configuration

**For Laravel Sail (MySQL 8.4)**
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=abe_challenge
DB_USERNAME=sail
DB_PASSWORD=password
```

**SQLite (Default for Local Development)**
```env
DB_CONNECTION=sqlite
```

No additional configuration needed. The database file will be created at `database/database.sqlite`.

**MySQL (Local Installation)**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**PostgreSQL**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Cache & Queue

- `CACHE_STORE` - Cache driver (default: `database`)
- `QUEUE_CONNECTION` - Queue driver (default: `database`)

## Database Setup

### Laravel Sail (MySQL 8.4)

If you're using Laravel Sail, MySQL 8.4 is automatically set up:

1. **Configure database in `.env`** (see [Environment Configuration](#environment-configuration))
2. **Start Sail containers**
   ```bash
   ./vendor/bin/sail up -d
   ```
3. **Run migrations**
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

The MySQL database is accessible from your host machine at `127.0.0.1:3306` (or the port specified in `FORWARD_DB_PORT`).

### SQLite (Default for Local Development)

SQLite is the default database for local PHP installations and requires no additional setup:

- The database file will be automatically created at `database/database.sqlite`
- Ensure the `database/` directory has write permissions
- No database server installation required

**Note:** SQLite is not available in Sail environment. Sail uses MySQL by default.

### MySQL/PostgreSQL (Local Installation)

If you prefer to use MySQL or PostgreSQL with local PHP installation:

1. **Create a database** using your database management tool
2. **Update `.env`** with your database connection details (see [Environment Configuration](#environment-configuration))
3. **Run migrations**
   ```bash
   php artisan migrate
   ```

## Running the Application

### Using Laravel Sail

If you're using Laravel Sail, the application runs automatically when containers are started:

1. **Start containers** (if not already running)
   ```bash
   ./vendor/bin/sail up -d
   ```

2. **Run Vite dev server** (for hot module replacement)
   ```bash
   ./vendor/bin/sail npm run dev
   ```

The application will be available at `http://localhost` (port 80).

**Note:** Sail serves the application automatically via Nginx, so you don't need to run `php artisan serve`.

**Useful Sail commands:**
- `./vendor/bin/sail up` - Start containers in foreground
- `./vendor/bin/sail up -d` - Start containers in background (detached)
- `./vendor/bin/sail down` - Stop containers
- `./vendor/bin/sail restart` - Restart containers
- `./vendor/bin/sail logs` - View container logs
- `./vendor/bin/sail logs -f` - Follow container logs

### Development Mode (Local PHP Installation)

For development with local PHP installation, use the dev script which runs multiple services concurrently:

```bash
composer run dev
```

This command starts:
- **PHP development server** on port 8000
- **Queue worker** for background jobs
- **Laravel Pail** for viewing logs in real-time
- **Vite dev server** with hot module replacement

The application will be available at `http://localhost:8000`

### Production Build

For production or when you need to test the built assets:

1. **Build assets**
   ```bash
   npm run build
   ```
   Or with Sail:
   ```bash
   ./vendor/bin/sail npm run build
   ```

2. **Serve the application** (local PHP only)
   ```bash
   php artisan serve
   ```
   **Note:** With Sail, the application is already served automatically.

## Accessing the Application

### Admin Panel

The Filament admin panel is available at:

- **Laravel Sail**: `http://localhost/administrator`
- **Local PHP**: `http://localhost:8000/administrator`

### Customer Panel

The customer panel is available at:

- **Laravel Sail**: `http://localhost/customer`
- **Local PHP**: `http://localhost:8000/customer`

### Test Credentials

After running the database seeder, you can use these credentials to log in:

**With Sail:**
```bash
./vendor/bin/sail artisan db:seed
```

**With Local PHP:**
```bash
php artisan db:seed
```

**Admin Panel:**
- **Email**: `administrator@abe-challenge.com`
- **Password**: `password`

**Customer Panel:**
- **Email**: `customer@abe-challenge.com`
- **Password**: `password`

## Development Workflow

### Code Formatting

Format your code using Laravel Pint:

**With Sail:**
```bash
./vendor/bin/sail composer run lint
```

**With Local PHP:**
```bash
composer run lint
```

### Running Tests

Run the test suite:

**With Sail:**
```bash
./vendor/bin/sail composer run test
```

**With Local PHP:**
```bash
composer run test
```

Or use Pest directly:

**With Sail:**
```bash
./vendor/bin/sail artisan test
```

**With Local PHP:**
```bash
php artisan test
```

### Database Migrations

Create a new migration:

**With Sail:**
```bash
./vendor/bin/sail artisan make:migration create_example_table
```

**With Local PHP:**
```bash
php artisan make:migration create_example_table
```

Run migrations:

**With Sail:**
```bash
./vendor/bin/sail artisan migrate
```

**With Local PHP:**
```bash
php artisan migrate
```

Rollback the last migration:

**With Sail:**
```bash
./vendor/bin/sail artisan migrate:rollback
```

**With Local PHP:**
```bash
php artisan migrate:rollback
```

### Creating Models

Create a new model with factory and migration:

**With Sail:**
```bash
./vendor/bin/sail artisan make:model Example -mf
```

**With Local PHP:**
```bash
php artisan make:model Example -mf
```

### Seeding Data

Seed the database with sample data:

**With Sail:**
```bash
./vendor/bin/sail artisan db:seed
```

**With Local PHP:**
```bash
php artisan db:seed
```

Seed a specific seeder:

**With Sail:**
```bash
./vendor/bin/sail artisan db:seed --class=DatabaseSeeder
```

**With Local PHP:**
```bash
php artisan db:seed --class=DatabaseSeeder
```

**Note:** When using Sail, prefix all `artisan`, `composer`, and `npm` commands with `./vendor/bin/sail` to run them inside the container.

## Using Laravel Sail (Docker Development)

Laravel Sail is already installed and pre-configured in this project. Sail provides a Docker-based development environment with PHP 8.5 and MySQL 8.4, eliminating the need for local PHP and database installations.

### Sail is Already Installed

This project comes with Sail pre-configured. The `docker-compose.yml` file is set up with:
- PHP 8.5 runtime
- MySQL 8.4 database
- Nginx web server
- All necessary PHP extensions

### Initial Setup

If this is your first time setting up the project with Sail:

1. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

2. **Configure Sail environment variables** (see [Environment Configuration](#environment-configuration))

3. **Start Sail containers**
   ```bash
   ./vendor/bin/sail up -d
   ```

4. **Install dependencies and set up the application** (see [Installation with Laravel Sail](#installation-with-laravel-sail-recommended-for-docker-users))

### Starting and Stopping Sail

**Start containers:**
```bash
./vendor/bin/sail up
```

**Start containers in background (detached mode):**
```bash
./vendor/bin/sail up -d
```

**Stop containers:**
```bash
./vendor/bin/sail down
```

**Restart containers:**
```bash
./vendor/bin/sail restart
```

### Running Commands

All commands should be prefixed with `./vendor/bin/sail` to run inside the container:

**Artisan commands:**
```bash
./vendor/bin/sail artisan [command]
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
./vendor/bin/sail artisan test
```

**Composer commands:**
```bash
./vendor/bin/sail composer [command]
./vendor/bin/sail composer install
./vendor/bin/sail composer update
./vendor/bin/sail composer run test
```

**NPM commands:**
```bash
./vendor/bin/sail npm [command]
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
./vendor/bin/sail npm run build
```

**PHP commands:**
```bash
./vendor/bin/sail php [command]
./vendor/bin/sail php artisan tinker
```

### Sail Alias (Optional)

For convenience, you can set up a `sail` alias to avoid typing `./vendor/bin/sail` every time:

**Bash/Zsh:**
Add to `~/.bashrc` or `~/.zshrc`:
```bash
alias sail='./vendor/bin/sail'
```

**Fish:**
Add to `~/.config/fish/config.fish`:
```fish
function sail
    ./vendor/bin/sail $argv
end
```

After setting up the alias, you can use:
```bash
sail up
sail artisan migrate
sail composer install
```

### Database Access

**Access MySQL from host machine:**
```bash
./vendor/bin/sail mysql
```

**Access MySQL with specific user:**
```bash
./vendor/bin/sail mysql -u root -p
```

**Connect from external tools:**
- Host: `127.0.0.1`
- Port: `3306` (or value from `FORWARD_DB_PORT`)
- Username: `sail` (or value from `DB_USERNAME`)
- Password: `password` (or value from `DB_PASSWORD`)
- Database: `abe_challenge` (or value from `DB_DATABASE`)

### Shell Access

Access the container shell:
```bash
./vendor/bin/sail shell
```

Or access as root:
```bash
./vendor/bin/sail root-shell
```

### Viewing Logs

**View all logs:**
```bash
./vendor/bin/sail logs
```

**Follow logs (real-time):**
```bash
./vendor/bin/sail logs -f
```

**View specific service logs:**
```bash
./vendor/bin/sail logs laravel.test
./vendor/bin/sail logs mysql
```

### Xdebug Configuration

Xdebug is available but disabled by default. To enable:

1. **Set Xdebug mode in `.env`:**
   ```env
   SAIL_XDEBUG_MODE=develop,debug
   ```

2. **Restart containers:**
   ```bash
   ./vendor/bin/sail restart
   ```

Available Xdebug modes: `develop`, `debug`, `coverage`, `profile`, `trace`

### Troubleshooting

**Port conflicts:**
If ports 80, 5173, or 3306 are already in use, change them in `.env`:
```env
APP_PORT=8080
VITE_PORT=5174
FORWARD_DB_PORT=3307
```

**Permission issues:**
If you encounter permission errors, ensure `WWWUSER` and `WWWGROUP` in `.env` match your system user/group IDs:
```bash
id -u  # Your user ID
id -g  # Your group ID
```

**Container rebuild:**
If you need to rebuild containers:
```bash
./vendor/bin/sail build --no-cache
./vendor/bin/sail up -d
```

**Clear everything and start fresh:**
```bash
./vendor/bin/sail down -v
./vendor/bin/sail up -d
```

For more information, see the [Laravel Sail documentation](https://laravel.com/docs/sail).

## Project Structure

Key directories and their purposes:

```
app/
├── Filament/          # Filament admin panel resources
│   ├── Resources/     # Resource definitions (Customers, Rewards, etc.)
│   ├── Pages/         # Custom admin pages
│   └── Widgets/       # Dashboard widgets
├── Models/            # Eloquent models
├── Support/           # Support classes (e.g., CreditBalanceCalculator)
├── Observers/         # Model observers
└── Providers/        # Service providers

database/
├── migrations/        # Database migrations
├── factories/         # Model factories
└── seeders/          # Database seeders

resources/
├── css/              # Stylesheets
├── js/               # JavaScript files
└── views/            # Blade templates

routes/
├── web.php           # Web routes
└── console.php       # Artisan commands
```

## Features

- **Customer Management** - Manage customers with credit balance tracking
- **Credit Transactions** - Track manual, bonus, and reward transactions
- **Bonus Programs** - Create and manage bonus programs for customers
- **Rewards System** - Set up rewards that customers can redeem with credits
- **Activity Logging** - Automatic logging of all system activities
- **Cached Credit Balance** - Optimized credit balance calculations with automatic cache invalidation
- **Observer Pattern** - Automatic cache invalidation when transactions are created, updated, or deleted

## Troubleshooting

### Permission Errors

If you encounter permission errors, ensure the following directories are writable:

```bash
chmod -R 775 storage bootstrap/cache
```

Or on Linux/Mac:

```bash
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Database Errors

- **SQLite**: Ensure the `database/` directory has write permissions
- **MySQL/PostgreSQL**: Verify database credentials in `.env` and ensure the database exists
- Clear config cache: `php artisan config:clear`

### Asset Compilation Errors

If you encounter issues with asset compilation:

1. Clear node modules and reinstall:
   ```bash
   rm -rf node_modules package-lock.json
   npm install
   ```

2. Rebuild assets:
   ```bash
   npm run build
   ```

### Cache Issues

Clear application cache:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

Or clear everything at once:

```bash
php artisan optimize:clear
```

### Vite Manifest Errors

If you see "Unable to locate file in Vite manifest" errors:

```bash
npm run build
```

Or for development:

```bash
npm run dev
```

## Testing

This project uses [Pest PHP](https://pestphp.com/) for testing.

### Running Tests

Run all tests:

```bash
composer run test
```

Or use Pest directly:

```bash
php artisan test
```

Run tests with coverage:

```bash
php artisan test --coverage
```

Run specific test files:

```bash
php artisan test tests/Feature/ExampleTest.php
```

### Writing Tests

Create a new test:

```bash
php artisan make:test ExampleTest --pest
```

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Livewire Documentation](https://livewire.laravel.com/docs)
- [Pest PHP Documentation](https://pestphp.com/docs)
- [Laravel Sail Documentation](https://laravel.com/docs/sail)

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


