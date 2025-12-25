# EasySet24 - Hotel Booking System
# Installation Guide

`git clone https://github.com/tutisanitako/hotel_booking.git`

`cd hotel_booking`


`composer install`

`npm install && npm run build`


`cp .env.example .env`

`php artisan key:generate`


`php artisan migrate`

## Custom Commands (Automation)


|           Command           |            Description                  |                Usage Example                |
| :-------------------------- | :-------------------------------------- | :------------------------------------------ |
| `php artisan data:generate` | Seeds the DB with hotels, rooms, and amenities. | `php artisan data:generate` |
| `php artisan admin:create` | Creates a Super Admin user. | `php artisan admin:create admin@test.com pass123` |
| `php artisan manager:create` | Creates a Hotel Manager user. | `php artisan manager:create manager@test.com pass123 "John Doe"` |
| `php artisan manager:assign` | Links a Manager to a specific Hotel ID. | `php artisan manager:assign manager@test.com 1` |
| `php artisan manager:promote` | Promotes an existing User to Manager. | `php artisan manager:promote user@test.com` |
| `php artisan manager:list` | Lists all managers and their assigned hotels. | `php artisan manager:list` |

