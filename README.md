EasySet24 - Hotel Booking System
Installation Guide

1. Prerequisites
   PHP >= 8.1
   Composer
   Node.js & NPM
   MySQL
   
3. Clone & SetupBashgit clone https://github.com/tutisanitako/hotel_booking.git
cd hotel_booking

# Install PHP dependencies
composer install

# Install Frontend dependencies
npm install && npm run build

3. Environment Configuration
cp .env.example .env
php artisan key:generate

Configure your database credentials in the .env file:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=easyset24
DB_USERNAME=root
DB_PASSWORD=

5. Database & StorageBash# Run migrations to create tables
php artisan migrate
