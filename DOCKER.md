# Docker Deployment Guide

This guide provides detailed instructions for deploying EMA Alert Bot using Docker.

## Quick Start

```bash
# 1. Clone and navigate to project
git clone <repository-url>
cd EmaAlertBot

# 2. Copy environment file
cp .env.docker .env

# 3. Generate application key
docker-compose run --rm app php artisan key:generate

# 4. Edit .env and add your Telegram bot token
nano .env  # or use your preferred editor
# Set: TELEGRAM_BOT_TOKEN=your-bot-token-here

# 5. Build and start containers
docker-compose up -d

# 6. Access the application
# Open http://localhost:8080 in your browser
# Login: admin@example.com
# Password: password
```

## Container Architecture

### Services

1. **app** (PHP 8.2-FPM)
   - Runs Laravel application
   - Processes queue jobs via Supervisor
   - Runs scheduled tasks (EMA sync every 5 minutes)
   - Exposed port: 9000 (internal)

2. **nginx** (Alpine)
   - Web server serving Laravel public directory
   - Proxies PHP requests to app container
   - Exposed port: 8080 (external)

3. **mysql** (Optional, commented out)
   - MySQL 8.0 database server
   - Use instead of SQLite for production
   - Exposed port: 3306

4. **postgres** (Optional, commented out)
   - PostgreSQL 15 database server
   - Alternative to MySQL/SQLite
   - Exposed port: 5432

## Configuration Options

### Using SQLite (Default)

SQLite is pre-configured and requires no additional setup:

```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
```

### Using MySQL

1. Uncomment MySQL service in `docker-compose.yml`
2. Update `.env`:
```dotenv
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=ema_alert_bot
DB_USERNAME=ema_user
DB_PASSWORD=ema_password
```

3. Uncomment volumes section at the bottom of `docker-compose.yml`:
```yaml
volumes:
  mysql-data:
```

4. Restart containers:
```bash
docker-compose down
docker-compose up -d
```

### Using PostgreSQL

1. Uncomment PostgreSQL service in `docker-compose.yml`
2. Update `.env`:
```dotenv
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=ema_alert_bot
DB_USERNAME=ema_user
DB_PASSWORD=ema_password
```

3. Uncomment volumes section
4. Restart containers

## Common Operations

### Viewing Logs

```bash
# All logs
docker-compose logs -f

# Application logs only
docker-compose logs -f app

# Nginx logs
docker-compose logs -f nginx

# Last 100 lines
docker-compose logs --tail=100 app
```

### Executing Artisan Commands

```bash
# General syntax
docker-compose exec app php artisan <command>

# Examples
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan queue:work
docker-compose exec app php artisan config:cache
```

### Accessing Container Shell

```bash
# Access app container
docker-compose exec app bash

# Once inside, you can run commands directly
php artisan migrate
composer install
```

### Restarting Services

```bash
# Restart all services
docker-compose restart

# Restart specific service
docker-compose restart app
docker-compose restart nginx
```

### Stopping and Removing Containers

```bash
# Stop containers (preserves data)
docker-compose stop

# Stop and remove containers (preserves data in volumes)
docker-compose down

# Remove everything including volumes (DANGER: deletes database)
docker-compose down -v
```

## Supervisor Configuration

The app container uses Supervisor to manage multiple processes:

1. **php-fpm**: Handles PHP requests
2. **queue-worker**: Processes Laravel queue jobs (2 workers)
3. **scheduler**: Runs Laravel scheduled tasks

To view Supervisor status:

```bash
docker-compose exec app supervisorctl status
```

## Troubleshooting

### Container won't start

```bash
# Check logs
docker-compose logs app

# Rebuild container
docker-compose up -d --build

# Check for port conflicts
netstat -an | grep 8080
```

### Permission errors

```bash
# Fix storage permissions
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/storage
```

### Database connection errors

```bash
# For SQLite, ensure database file exists
docker-compose exec app touch /var/www/html/database/database.sqlite
docker-compose exec app php artisan migrate

# For MySQL/PostgreSQL, check service is running
docker-compose ps
docker-compose logs mysql  # or postgres
```

### Queue jobs not processing

```bash
# Check supervisor status
docker-compose exec app supervisorctl status queue-worker:*

# Restart queue workers
docker-compose exec app supervisorctl restart queue-worker:*

# View queue worker logs
docker-compose exec app tail -f /var/www/html/storage/logs/queue-worker.log
```

### Scheduler not running

```bash
# Check scheduler status
docker-compose exec app supervisorctl status scheduler

# Restart scheduler
docker-compose exec app supervisorctl restart scheduler

# View scheduler logs
docker-compose exec app tail -f /var/www/html/storage/logs/scheduler.log
```

## Production Deployment

### 1. SSL/TLS Setup

Use a reverse proxy (e.g., Traefik, nginx-proxy, or Caddy) in front of the application:

```yaml
# Example with nginx-proxy
version: '3.8'

services:
  nginx-proxy:
    image: nginxproxy/nginx-proxy
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - /var/run/docker.sock:/tmp/docker.sock:ro
      - ./certs:/etc/nginx/certs
    networks:
      - proxy

  app:
    environment:
      - VIRTUAL_HOST=yourdomain.com
      - LETSENCRYPT_HOST=yourdomain.com
      - LETSENCRYPT_EMAIL=admin@yourdomain.com
    networks:
      - proxy
      - ema-network

networks:
  proxy:
  ema-network:
```

### 2. Environment Variables

Update `.env` for production:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
LOG_LEVEL=error
DB_SEED=false  # After initial setup
```

### 3. Security Hardening

```bash
# Change default admin password immediately
# Use strong passwords for database
# Keep dependencies updated
# Regular backups of database
# Monitor logs for suspicious activity
```

### 4. Backup Strategy

```bash
# Backup SQLite database
docker-compose exec app cp /var/www/html/database/database.sqlite /var/www/html/database/database.backup.sqlite

# Export database with script
docker-compose exec app php artisan backup:run  # if using spatie/laravel-backup

# Backup with host system
cp database/database.sqlite backups/database-$(date +%Y%m%d).sqlite
```

### 5. Monitoring

```bash
# Setup monitoring with Prometheus/Grafana or similar tools
# Monitor key metrics:
# - Container health
# - Queue job success/failure rates
# - API response times
# - Alert delivery success
```

## Updating the Application

```bash
# 1. Pull latest changes
git pull origin main

# 2. Rebuild containers
docker-compose up -d --build

# 3. Run migrations
docker-compose exec app php artisan migrate --force

# 4. Clear caches
docker-compose exec app php artisan optimize:clear
docker-compose exec app php artisan optimize
```

## Performance Tuning

### Increase Queue Workers

Edit `docker/supervisord.conf`:

```ini
[program:queue-worker]
numprocs=4  # Increase from 2 to 4
```

Rebuild and restart:
```bash
docker-compose up -d --build
```

### Optimize Laravel

```bash
# Cache configuration
docker-compose exec app php artisan config:cache

# Cache routes
docker-compose exec app php artisan route:cache

# Cache views
docker-compose exec app php artisan view:cache
```

### Adjust PHP-FPM Settings

Edit `Dockerfile` to add custom php-fpm pool configuration for better performance.

## Support

For issues specific to Docker deployment, check:
1. Container logs: `docker-compose logs`
2. Supervisor logs inside container
3. Laravel logs in `storage/logs/`
4. GitHub issues or project documentation

## Additional Resources

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Supervisor Documentation](http://supervisord.org/)
