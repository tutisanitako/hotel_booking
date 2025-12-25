<?php

// This script downloads sample hotel images from Unsplash
// Run: php setup_images.php

$imageUrls = [
    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop', // Hotel lobby
    'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&h=600&fit=crop', // Hotel exterior
    'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&h=600&fit=crop', // Hotel room
    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&h=600&fit=crop', // Hotel suite
    'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&h=600&fit=crop', // Hotel pool
];

$destinationDir = __DIR__ . '/storage/app/public/hotels';

// Create directory if it doesn't exist
if (!is_dir($destinationDir)) {
    mkdir($destinationDir, 0755, true);
    echo "Created directory: $destinationDir\n";
}

foreach ($imageUrls as $index => $url) {
    $filename = "hotel" . ($index + 1) . ".jpg";
    $destination = $destinationDir . '/' . $filename;
    
    echo "Downloading $filename...\n";
    
    $imageData = file_get_contents($url);
    
    if ($imageData !== false) {
        file_put_contents($destination, $imageData);
        echo "✓ Saved: $filename\n";
    } else {
        echo "✗ Failed to download: $filename\n";
    }
}

echo "\nDone! Images saved to: $destinationDir\n";
echo "Run 'php artisan data:generate' to use these images.\n";