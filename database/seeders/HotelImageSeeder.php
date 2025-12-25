<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelImageSeeder extends Seeder
{
    public function run(): void
    {
        // Array of image filenames you've placed in storage/app/public/hotels/
        $images = [
            'hotel1.jpg',
            'hotel2.jpg',
            'hotel3.jpg',
            'hotel4.jpg',
            'hotel5.jpg',
        ];

        $hotels = Hotel::all();

        foreach ($hotels as $index => $hotel) {
            // Cycle through images if there are more hotels than images
            $imageIndex = $index % count($images);
            
            $hotel->update([
                'main_image' => 'hotels/' . $images[$imageIndex]
            ]);
        }

        $this->command->info('Hotel images updated successfully!');
    }
}