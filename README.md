# ABE Challenge - Customer Loyalty System

A Laravel 12 application with Filament 5 admin panel for managing customer credit balances, bonus programs, and rewards.

## Tech Stack

- **Laravel 12** - PHP Framework
- **Filament 5** - Admin Panel
- **Livewire 4** - Reactive Components
- **PHP 8.2+** - Programming Language
- **SQLite/MySQL** - Database
- **Vite** - Frontend Build Tool
- **Tailwind CSS** - Styling
- **Pest** - Testing Framework

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Environment Configuration](#environment-configuration)
- [Database Setup](#database-setup)
- [Running the Application](#running-the-application)
- [Accessing the Application](#accessing-the-application)
- [Development Workflow](#development-workflow)
- [Using Laravel Sail (Optional)](#using-laravel-sail-optional)
- [Project Structure](#project-structure)
- [Features](#features)
- [Troubleshooting](#troubleshooting)
- [Testing](#testing)
- [Additional Resources](#additional-resources)

## Prerequisites

Before you begin, ensure you have the following installed on your machine:

- **PHP 8.2 or higher** with extensions: BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML
- **Composer 2.x** - PHP dependency manager
- **Node.js 22+** and **npm** - For frontend asset compilation
- **SQLite** (default, no setup needed) OR **MySQL 8.0+** / **PostgreSQL 13+**
- **Optional**: Docker & Docker Compose (for Laravel Sail)

## Installation

### Quick Setup (Recommended)

The easiest way to get started is using the setup script:

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
- `APP_URL` - Application URL (default: `http://localhost:8000`)

### Database Configuration

**SQLite (Default)**
```env
DB_CONNECTION=sqlite
```

No additional configuration needed. The database file will be created at `database/database.sqlite`.

**MySQL**
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

### SQLite (Default)

SQLite is the default database and requires no additional setup:

- The database file will be automatically created at `database/database.sqlite`
- Ensure the `database/` directory has write permissions
- No database server installation required

### MySQL/PostgreSQL

If you prefer to use MySQL or PostgreSQL:

1. **Create a database** using your database management tool
2. **Update `.env`** with your database connection details (see [Environment Configuration](#environment-configuration))
3. **Run migrations**
   ```bash
   php artisan migrate
   ```

## Running the Application

### Development Mode

For development, use the dev script which runs multiple services concurrently:

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

2. **Serve the application**
   ```bash
   php artisan serve
   ```

## Accessing the Application

### Admin Panel

The Filament admin panel is available at:

```
http://localhost:8000/administrator
```

### Default Admin User

After running the database seeder, a default administrator account is created:

- **Email**: `administrator@abe-challenge.com`
- **Password**: You'll need to set a password manually or create a new user

To create an admin user manually:

```bash
php artisan make:filament-user
```

Or use Tinker:

```bash
php artisan tinker
```

Then create a user:

```php
$user = \App\Models\User::create([
    'name' => 'Administrator',
    'email' => 'admin@example.com',
    'password' => \Illuminate\Support\Facades\Hash::make('password'),
]);
```

## Development Workflow

### Code Formatting

Format your code using Laravel Pint:

```bash
composer run lint
```

### Running Tests

Run the test suite:

```bash
composer run test
```

Or use Pest directly:

```bash
php artisan test
```

### Database Migrations

Create a new migration:

```bash
php artisan make:migration create_example_table
```

Run migrations:

```bash
php artisan migrate
```

Rollback the last migration:

```bash
php artisan migrate:rollback
```

### Creating Models

Create a new model with factory and migration:

```bash
php artisan make:model Example -mf
```

### Seeding Data

Seed the database with sample data:

```bash
php artisan db:seed
```

Seed a specific seeder:

```bash
php artisan db:seed --class=DatabaseSeeder
```

## Using Laravel Sail (Optional)

Laravel Sail provides a Docker-based development environment. To use Sail:

1. **Install Sail** (if not already installed)
   ```bash
   composer require laravel/sail --dev
   ```

2. **Install Sail configuration**
   ```bash
   php artisan sail:install
   ```

3. **Start the containers**
   ```bash
   ./vendor/bin/sail up
   ```

4. **Run Artisan commands**
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

5. **Run Composer commands**
   ```bash
   ./vendor/bin/sail composer install
   ```

6. **Run npm commands**
   ```bash
   ./vendor/bin/sail npm install
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


