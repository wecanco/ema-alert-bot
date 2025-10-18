# EMA Alert Bot

A cryptocurrency monitoring system built with Laravel 11 backend that uses free exchange APIs (Binance, OKX, Bybit, KuCoin, Huobi) to fetch candle data, calculate Exponential Moving Average (EMA), and send Telegram notifications when price touches the configured EMA level (default EMA 50). Asset management, timeframes, users, and integrations are handled through a Blade-based admin panel.

## Key Features

- **Web-based Admin Panel** with Breeze authentication for managing assets, watches, timeframes, users, and Telegram settings
- **Multi-Exchange Support** - Seamlessly connect to Binance, OKX, Bybit, KuCoin, and Huobi using their free public APIs
- **EMA Calculation** via dedicated service with scheduled market monitoring logic
- **Telegram Alerts** with event logging and duplicate prevention via `last_alert_at` tracking
- **Laravel Queues** for parallel watch checking (Queue connection: database)
- **Seeders** for creating default timeframes and strategy configurations (EMA 50)
- **Extensible MarketData Service** with dependency injection through Service Provider
- **Docker Support** - Full containerization with Docker Compose for easy deployment

## Prerequisites

- PHP >= 8.2
- Composer 2.x
- SQLite (project default) or another database with proper connection setup
- Node.js for frontend builds (if needed)
- Git (optional, for version control)

**Or use Docker:**
- Docker Engine 20.10+
- Docker Compose 2.x+

## Quick Start (Traditional)

```bash
# Install dependencies
composer install
npm install

# Copy environment file and generate key
cp .env.example .env
php artisan key:generate

# Create SQLite database file
touch database/database.sqlite

# Run migrations and seeders
php artisan migrate --seed

# Build frontend assets (Breeze)
npm run build

# Start development server and queues
php artisan serve
php artisan queue:work
```

## Quick Start (Docker)

### Option 1: Using Makefile (Recommended)

```bash
# Clone the repository
git clone <repository-url>
cd EmaAlertBot

# Run complete setup (copies env, builds, migrates, seeds)
make setup

# The application will be available at http://localhost:8080
# Login: admin@example.com / password

# Don't forget to add your Telegram bot token in .env
# TELEGRAM_BOT_TOKEN=your-bot-token-here
```

### Option 2: Manual Docker Setup

```bash
# Clone the repository
git clone <repository-url>
cd EmaAlertBot

# Copy environment file
cp .env.docker .env

# Add your Telegram bot token to .env
# TELEGRAM_BOT_TOKEN=your-bot-token-here

# Build and start containers
docker-compose up -d

# Generate application key
docker-compose exec app php artisan key:generate

# Run migrations and seeders
docker-compose exec app php artisan migrate --seed

# The application will be available at http://localhost:8080
```

### Docker Services

The Docker setup includes:
- **app**: PHP 8.2-FPM with Laravel application, queue worker, and scheduler
- **nginx**: Web server serving the application on port 8080
- **Optional databases**: MySQL 8.0 or PostgreSQL 15 (commented out by default, SQLite is used)

### Docker Management

#### Using Makefile Commands (Recommended)

```bash
# View all available commands
make help

# Common operations
make up              # Start containers
make down            # Stop containers
make restart         # Restart containers
make logs            # View all logs
make logs-app        # View application logs
make shell           # Access container shell

# Artisan commands
make migrate         # Run migrations
make seed            # Run seeders
make fresh           # Fresh migration with seed
make optimize        # Optimize application
make clear           # Clear all caches

# Testing
make test            # Run tests

# Maintenance
make backup          # Backup database
make queue-restart   # Restart queue workers
make supervisor      # Check supervisor status
```

#### Using Docker Compose Directly

```bash
# View logs
docker-compose logs -f app

# Stop containers
docker-compose down

# Restart containers
docker-compose restart

# Access application container
docker-compose exec app bash

# Run artisan commands
docker-compose exec app php artisan <command>

# Clear cache
docker-compose exec app php artisan optimize:clear
```

## Environment Configuration

Configure the following values in `.env` file:

```dotenv
APP_URL=https://example.com
TELEGRAM_BOT_TOKEN=your-bot-token

# Market Data Provider (binance, bybit, kucoin, okx, huobi)
MARKETDATA_PROVIDER=binance

# Queue and Database
QUEUE_CONNECTION=database
DB_CONNECTION=sqlite # or mysql, pgsql
```

### Exchange Selection

Each asset can be configured to use a different exchange. The API URLs are hardcoded in the exchange client classes:
- **Binance**: `https://api.binance.com/api/v3`
- **Bybit**: `https://api.bybit.com/v5/market`
- **KuCoin**: `https://api.kucoin.com/api/v1`
- **OKX**: `https://www.okx.com/api/v5`
- **Huobi**: `https://api.huobi.pro/market`

## Using the Admin Panel

- After running `php artisan migrate --seed`, an admin user is created: `admin@example.com` with password `password`
- Access the panel by logging in at `/dashboard`
- Admin routes are namespaced under `admin.*`:
  - `admin.assets.*` - Manage trading pairs and exchanges
  - `admin.asset-watches.*` - Configure EMA watches
  - `admin.users.*` - User management
  - `admin.timeframes.*` - Trading timeframes
  - `admin.strategy-configs.*` - EMA configurations
  - `admin.integrations.*` - Integration settings
  - `admin.telegram.settings` - Telegram bot configuration

### Creating Assets

When creating a new asset in the admin panel (`/admin/assets/create`), you can:
1. Select the exchange from a dropdown (all supported exchanges are listed)
2. Enter the trading symbol (e.g., BTCUSDT)
3. Set base currency and active status

The form displays each exchange with its API URL for reference.

## Background Processing

- Scheduled command `app:sync-asset-ema` and Job `CheckEmaAlerts` run every five minutes (Console Kernel)
- Use `php artisan schedule:work` or system cron to run scheduled tasks
- Keep queues active with `php artisan queue:work` to process EMA jobs

**With Docker**: Queue worker and scheduler are automatically started by Supervisor inside the container.

## Testing & Maintenance

```bash
# Run tests
./vendor/bin/phpunit

# Clear cache
php artisan optimize:clear

# Rebuild cache
php artisan optimize

# With Docker
docker-compose exec app php artisan test
docker-compose exec app php artisan optimize:clear
```

## Project Structure

```
├── app/
│   ├── Http/Controllers/Admin/    # Admin panel controllers
│   ├── Jobs/                       # Queue jobs for EMA checks
│   ├── Models/                     # Eloquent models
│   ├── Services/
│   │   ├── MarketData/            # Exchange client implementations
│   │   │   ├── BinanceClient.php
│   │   │   ├── BybitClient.php
│   │   │   ├── KucoinClient.php
│   │   │   ├── OkxClient.php
│   │   │   ├── HuobiClient.php
│   │   │   └── MarketDataManager.php
│   │   └── Alerts/                # Alert dispatching logic
│   └── Console/Commands/          # Artisan commands
├── config/
│   └── marketdata.php             # Exchange configuration
├── database/
│   ├── migrations/                # Database schema
│   └── seeders/                   # Default data
├── docker/
│   ├── nginx/default.conf         # Nginx configuration
│   ├── supervisord.conf           # Supervisor for queue/scheduler
│   └── docker-entrypoint.sh       # Container startup script
├── resources/views/admin/         # Blade templates
├── docker-compose.yml             # Docker orchestration
├── Dockerfile                     # Container image definition
└── .dockerignore                  # Docker build exclusions
```

## Adding New Exchanges

To add support for a new exchange:

1. Create a new client class implementing `App\Services\MarketData\Contracts\MarketDataClient`:
```php
class NewExchangeClient implements MarketDataClient
{
    public static function getBaseUrl(): string
    {
        return 'https://api.newexchange.com';
    }

    public static function getDisplayName(): string
    {
        return 'New Exchange';
    }

    public static function getKey(): string
    {
        return 'newexchange';
    }

    public function candles(string $symbol, string $interval, int $limit = 100): array
    {
        // Implementation
    }

    public function latestPrice(string $symbol): float
    {
        // Implementation
    }
}
```

2. Register it in `config/marketdata.php`:
```php
'providers' => [
    // ...
    'newexchange' => [
        'class' => NewExchangeClient::class,
    ],
],
```

3. Add it to `MarketDataManager::$availableClients` array

## Further Development

- **Add new market services**: Implement `App\Services\MarketData\Contracts\MarketDataClient` and register in Service Provider
- **Telegram enhancements**: Implement webhook and user chat_id verification
- **Add Feature tests**: For admin panel and alert system
- **Multiple EMA strategies**: Support for EMA crossovers and custom indicators
- **WebSocket support**: Real-time price monitoring for faster alerts

## Deployment

### Docker Deployment (Recommended)

1. Set up production environment:
```bash
cp .env.example .env
# Edit .env with production values
```

2. Build and start:
```bash
docker-compose up -d
```

3. Configure reverse proxy (nginx/Apache) to forward traffic to port 8080

### Traditional Deployment

1. Deploy Laravel application to server
2. Configure web server (nginx/Apache) to serve `public/` directory
3. Set up supervisor to run queue worker and scheduler
4. Configure cron job for Laravel scheduler:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## Troubleshooting

**Queue jobs not processing:**
- Check `php artisan queue:work` is running
- With Docker: `docker-compose logs app` to check supervisor status

**Telegram alerts not sending:**
- Verify `TELEGRAM_BOT_TOKEN` in `.env`
- Check bot settings in admin panel (`/admin/telegram/settings`)
- Ensure chat IDs are configured for users

**Exchange API errors:**
- Verify internet connectivity
- Check exchange API status pages
- Review symbol format (different exchanges use different formats)

**Docker issues:**
- Ensure ports 8080 is not already in use
- Check container logs: `docker-compose logs`
- Rebuild containers: `docker-compose up -d --build`

## Security Notes

- Change default admin password immediately after first login
- Use strong passwords for production environments
- Keep `APP_DEBUG=false` in production
- Regularly update dependencies: `composer update` and `npm update`
- Restrict admin panel access via firewall rules if needed
- Use HTTPS in production (configure reverse proxy with SSL/TLS)

## License

This project is licensed under the MIT License.

## Support

For issues, questions, or contributions, please open an issue on the project repository.
