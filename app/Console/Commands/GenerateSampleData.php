<?php

namespace App\Console\Commands;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Amenity;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateSampleData extends Command
{
    protected $signature = 'data:generate';
    protected $description = 'Generate sample hotels, rooms, and amenities';

    public function handle()
    {
        $this->info('Generating sample data...');

        $amenities = [
            ['name' => 'Free WiFi', 'icon' => 'wifi', 'category' => 'room'],
            ['name' => 'Air Conditioning', 'icon' => 'ac', 'category' => 'room'],
            ['name' => 'Bathroom', 'icon' => 'bathroom', 'category' => 'room'],
            ['name' => 'Room Service', 'icon' => 'service', 'category' => 'room'],
            ['name' => 'Tea/Coffee Machine', 'icon' => 'coffee', 'category' => 'room'],
            ['name' => 'Parking Available', 'icon' => 'parking', 'category' => 'hotel'],
            ['name' => 'Fitness Center', 'icon' => 'fitness', 'category' => 'hotel'],
            ['name' => 'Restaurant', 'icon' => 'restaurant', 'category' => 'hotel'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::firstOrCreate($amenity);
        }

        $this->info('Amenities created!');

        $sampleImages = [
            'hotels/hotel1.jpg',
            'hotels/hotel2.jpg',
            'hotels/hotel3.jpg',
            'hotels/hotel4.jpg',
        ];

        $hotels = [
            [
                'name' => 'Radisson Blu Gothenburg',
                'slug' => 'radisson-blu-gothenburg',
                'description' => 'Experience unique opportunity! Standard rooms with modern amenities and stunning city views. Located in the heart of Gothenburg, our hotel offers exceptional service and comfortable accommodations.',
                'location' => 'Gothenburg',
                'address' => 'Södra Hamngatan 59, Gothenburg, Sweden',
                'distance_from_center' => 5.0,
                'rating' => 8,
                'price_per_night' => 125,
                'main_image' => $sampleImages[0] ?? null,
                'breakfast_included' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'First Hotel G Stockholm',
                'slug' => 'first-hotel-g-stockholm',
                'description' => 'Stylish and roomy family home in Stockholm. A perfect blend of contemporary design and Swedish hospitality. Enjoy our spa facilities and gourmet restaurant.',
                'location' => 'Stockholm',
                'address' => 'Gävlegatan 18, Stockholm, Sweden',
                'distance_from_center' => 5.0,
                'rating' => 9,
                'price_per_night' => 240,
                'main_image' => $sampleImages[1] ?? null,
                'breakfast_included' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Grand Hotel Berlin',
                'slug' => 'grand-hotel-berlin',
                'description' => 'Luxury hotel in the center of Berlin. Experience world-class service and elegant rooms with breathtaking views of the city.',
                'location' => 'Berlin',
                'address' => 'Friedrichstrasse 158-164, Berlin, Germany',
                'distance_from_center' => 2.0,
                'rating' => 9,
                'price_per_night' => 180,
                'main_image' => $sampleImages[2] ?? null,
                'breakfast_included' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Paris Marriott Opera',
                'slug' => 'paris-marriott-opera',
                'description' => 'Elegant hotel near the Opera Garnier. Discover Parisian charm in our beautifully appointed rooms and suites.',
                'location' => 'Paris',
                'address' => '4 Rue de Caumartin, Paris, France',
                'distance_from_center' => 3.0,
                'rating' => 8,
                'price_per_night' => 200,
                'main_image' => $sampleImages[3] ?? null,
                'breakfast_included' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($hotels as $hotelData) {
            $hotel = Hotel::firstOrCreate(
                ['slug' => $hotelData['slug']],
                $hotelData
            );

            // Create rooms for each hotel
            $roomTypes = [
                ['name' => 'Superior Twin Room', 'type' => 'Twin', 'price' => $hotelData['price_per_night'] - 20],
                ['name' => 'Double Room', 'type' => 'Double', 'price' => $hotelData['price_per_night']],
                ['name' => 'Family Suite', 'type' => 'Suite', 'price' => $hotelData['price_per_night'] + 50],
            ];

            foreach ($roomTypes as $roomType) {
                $room = Room::firstOrCreate(
                    [
                        'hotel_id' => $hotel->id,
                        'name' => $roomType['name']
                    ],
                    [
                        'type' => $roomType['type'],
                        'description' => 'Comfortable ' . $roomType['type'] . ' room with modern amenities and stylish decor.',
                        'price_per_night' => $roomType['price'],
                        'max_adults' => 2,
                        'max_children' => 2,
                        'available_rooms' => 5,
                        'size_sqm' => 18,
                    ]
                );

                // Attach amenities
                if ($room->amenities()->count() === 0) {
                    $room->amenities()->attach(Amenity::where('category', 'room')->pluck('id'));
                }
            }
        }

        $this->info('Sample data generated successfully!');
        $this->info('Note: Make sure you have image files in storage/app/public/hotels/');
        return 0;
    }
}