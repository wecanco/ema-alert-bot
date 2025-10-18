## EMA Alert Bot

EMA Alert Bot یک سامانه مانیتورینگ ارز دیجیتال با بک‌اند Laravel 11 است که از APIهای رایگان صرافی‌ها (Binance، OKX، Bybit، KuCoin، Huobi) برای واکشی داده‌های کندل استفاده می‌کند، میانگین متحرک نمایی (EMA) را محاسبه می‌کند و در صورت لمس EMA 50 (یا مقدار تنظیم‌شده) اعلان تلگرام ارسال می‌کند. مدیریت دارایی‌ها، تایم‌فریم‌ها، کاربران و یکپارچه‌سازی‌ها از طریق پنل مدیریتی Blade انجام می‌گیرد.

## امکانات کلیدی

- **پنل تحت وب** با احراز هویت Breeze برای مدیریت دارایی‌ها، واچ‌ها، تایم‌فریم‌ها، کاربران و تنظیمات تلگرام.
- **محاسبه EMA** توسط سرویس اختصاصی و منطق زمان‌بندی‌شده برای بررسی دوره‌ای بازار.
- **هشدار تلگرام** با ذخیره رویدادها و جلوگیری از ارسال‌های تکراری با ذخیره `last_alert_at`.
- **صف‌های لاراول** برای پردازش موازی بررسی واچ‌ها (Queue connection: database).
- **Seeders** برای ایجاد تایم‌فریم‌ها و تنظیمات پیش‌فرض (EMA 50).
- **سرویس MarketData قابل توسعه** با پشتیبانی چندصرافی (Binance، OKX، Bybit، KuCoin، Huobi) و تزریق وابستگی از طریق Service Provider.

## پیش‌نیازها

- PHP >= 8.2
- Composer 2.x
- SQLite (پیش‌فرض پروژه) یا پایگاه‌داده دیگر با تنظیم کانکشن مناسب.
- Node.js برای build فرانت (در صورت نیاز).
- Git (در صورت نیاز به کنترل نسخه).

## راه‌اندازی سریع

```bash
# نصب وابستگی‌ها
composer install
npm install

# کپی فایل محیط و تولید کلید
cp .env.example .env
php artisan key:generate

# ایجاد فایل دیتابیس SQLite (در صورت نیاز)
touch database/database.sqlite

# اجرای مایگریشن‌ها و Seeders
php artisan migrate --seed

# build منابع فرانت (Breeze)
npm run build

# اجرای سرور توسعه و صف‌ها
php artisan serve
php artisan queue:work
```

## پیکربندی محیط

در فایل `.env` مقادیر زیر را تنظیم کنید:

```dotenv
APP_URL=https://example.com
TELEGRAM_BOT_TOKEN=your-bot-token
MARKETDATA_PROVIDER=binance
BINANCE_API_URL=https://api.binance.com/api/v3
BYBIT_API_URL=https://api.bybit.com/v5/market
KUCOIN_API_URL=https://api.kucoin.com/api/v1
OKX_API_URL=https://www.okx.com/api/v5
HUOBI_API_URL=https://api.huobi.pro/market
QUEUE_CONNECTION=database
DB_CONNECTION=sqlite # یا کانکشن دلخواه
```

## استفاده از پنل مدیریت

- پس از اجرای `php artisan migrate --seed` کاربر ادمین `admin@example.com` با رمز `password` ایجاد می‌شود.
- برای دسترسی به پنل، به `/dashboard` لاگین کنید.
- مسیرهای مدیریتی در فضای نام `admin.` قرار دارند:
  - `admin.assets.*`
  - `admin.asset-watches.*`
  - `admin.users.*`
  - `admin.timeframes.*`
  - `admin.strategy-configs.*`
  - `admin.integrations.*`
  - `admin.telegram.settings`

## پردازش پس‌زمینه

- دستور زمان‌بندی شده `app:sync-asset-ema` و Job `CheckEmaAlerts` هر پنج دقیقه اجرا می‌شوند (Console Kernel).
- برای اجرای زمان‌بندی‌ها از `php artisan schedule:work` یا cron سیستم استفاده کنید.
- صف‌ها را با `php artisan queue:work` فعال نگه دارید تا jobهای EMA پردازش شوند.

## تست‌ها و نگهداری

- تست‌ها با `vendor\bin\phpunit` اجرا می‌شوند (پوشش پایه Breeze).
- برای حذف cache: `php artisan optimize:clear`.
- برای بازتولید cache: `php artisan optimize`.

## توسعه بیشتر

- افزودن سرویس‌های بازار جدید: پیاده‌سازی `App\Services\MarketData\Contracts\MarketDataClient` و ثبت در Service Provider.
- توسعه Telegram: پیاده‌سازی webhook و تأیید chat_id کاربران.
- افزودن تست‌های Feature برای پنل مدیریت و هشدارها.

## مجوز

این پروژه تحت مجوز MIT منتشر شده است.
