EasySet24 - Hotel Booking System
Installation Guide

# 1. Prerequisites
   PHP >= 8.1
   Composer
   Node.js & NPM
   MySQL
   
# 2. Clone & SetupBash
git clone https://github.com/tutisanitako/hotel_booking.git
cd hotel_booking

# 3. Install PHP dependencies
composer install

# 4. Install Frontend dependencies
npm install && npm run build

# 5. Environment Configuration
cp .env.example .env
php artisan key:generate

# 6. Configure your database credentials in the .env file:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=easyset24
DB_USERNAME=root
DB_PASSWORD=

# 7. Run migrations to create tables
php artisan migrate

## 8. Custom Commands (Automation)


|           Command           |            Description                  |                Usage Example                |
| :-------------------------- | :-------------------------------------- | :------------------------------------------ |
| `php artisan data:generate` | Seeds the DB with hotels, rooms, and amenities. | `php artisan data:generate` |
| `php artisan admin:create` | Creates a Super Admin user. | `php artisan admin:create admin@test.com pass123` |
| `php artisan manager:create` | Creates a Hotel Manager user. | `php artisan manager:create manager@test.com pass123 "John Doe"` |
| `php artisan manager:assign` | Links a Manager to a specific Hotel ID. | `php artisan manager:assign manager@test.com 1` |
| `php artisan manager:promote` | Promotes an existing User to Manager. | `php artisan manager:promote user@test.com` |
| `php artisan manager:list` | Lists all managers and their assigned hotels. | `php artisan manager:list` |

