<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

echo "Seeding database...\n";

// Categories
$categories = [
    ['name' => 'Tractors', 'slug' => 'tractors'],
    ['name' => 'Harvesters', 'slug' => 'harvesters'],
    ['name' => 'Implements', 'slug' => 'implements']
];

$categoryIds = [];
foreach ($categories as $cat) {
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE slug = ?");
    $stmt->execute([$cat['slug']]);
    $exists = $stmt->fetch();
    
    if (!$exists) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
        $stmt->execute([$cat['name'], $cat['slug']]);
        $categoryIds[$cat['slug']] = $pdo->lastInsertId();
        echo "Inserted category: {$cat['name']}\n";
    } else {
        $categoryIds[$cat['slug']] = $exists['id'];
    }
}

// Products
$products = [
    [
        'category_slug' => 'tractors',
        'name' => 'Mahindra 575 DI',
        'slug' => 'mahindra-575-di',
        'short_description' => 'A powerful and fuel-efficient tractor for all farming needs.',
        'description' => 'The Mahindra 575 DI is designed for high performance, reliability, and fuel efficiency. Ideal for plowing, tilling, and heavy haulage.',
        'specifications' => "- Engine: 45 HP\n- Cylinders: 4\n- Lifting Capacity: 1600 kg\n- Fuel Tank: 47 Liters",
        'applications' => "Ploughing, Cultivation, Haulage, Rotavation",
        'price' => 650000.00,
        'price_visibility' => 'public',
        'featured' => 1
    ],
    [
        'category_slug' => 'tractors',
        'name' => 'Swaraj 744 FE',
        'slug' => 'swaraj-744-fe',
        'short_description' => 'Versatile tractor with powerful engine and high lift capacity.',
        'description' => 'Swaraj 744 FE offers excellent pulling power and long life. It is highly suitable for agricultural and commercial applications.',
        'specifications' => "- Engine: 48 HP\n- Cylinders: 3\n- Lifting Capacity: 1700 kg\n- Steering: Mechanical/Power",
        'applications' => "Heavy Implements, Transport, Cultivation",
        'price' => 690000.00,
        'price_visibility' => 'locked',
        'featured' => 1
    ],
    [
        'category_slug' => 'tractors',
        'name' => 'John Deere 5050 D',
        'slug' => 'john-deere-5050-d',
        'short_description' => 'Premium tractor with advanced technology for high yield.',
        'description' => 'Experience the power of John Deere with the 5050 D. High backup torque and low maintenance make it a top choice.',
        'specifications' => "- Engine: 50 HP\n- Cylinders: 3\n- Lifting Capacity: 1600 kg\n- Brakes: Oil Immersed",
        'applications' => "Harvesting, Puddling, Haulage",
        'price' => 740000.00,
        'price_visibility' => 'public',
        'featured' => 0
    ],
    [
        'category_slug' => 'tractors',
        'name' => 'Eicher 380',
        'slug' => 'eicher-380',
        'short_description' => 'Economical and robust tractor for small to medium farms.',
        'description' => 'Eicher 380 is a highly economical tractor that delivers great mileage and requires low maintenance.',
        'specifications' => "- Engine: 40 HP\n- Cylinders: 3\n- Lifting Capacity: 1300 kg",
        'applications' => "Sowing, Spraying, Threshing",
        'price' => 520000.00,
        'price_visibility' => 'hidden',
        'featured' => 0
    ],
    [
        'category_slug' => 'harvesters',
        'name' => 'Kartar 4000',
        'slug' => 'kartar-4000',
        'short_description' => 'Self-propelled combine harvester for multiple crops.',
        'description' => 'Kartar 4000 provides efficient harvesting with minimal grain loss. Best suited for wheat, paddy, and soybean.',
        'specifications' => "- Engine: 101 HP\n- Cutting Width: 14 Feet\n- Grain Tank: 2.5 Tons",
        'applications' => "Wheat, Paddy, Soybean Harvesting",
        'price' => 2200000.00,
        'price_visibility' => 'locked',
        'featured' => 1
    ],
    [
        'category_slug' => 'harvesters',
        'name' => 'Preet 987',
        'slug' => 'preet-987',
        'short_description' => 'High performance combine harvester with AC cabin option.',
        'description' => 'Preet 987 is known for its durability and high harvesting speed.',
        'specifications' => "- Engine: 105 HP\n- Cutting Width: 14 Feet\n- Drive: 2WD",
        'applications' => "Multicrop Harvesting",
        'price' => 2400000.00,
        'price_visibility' => 'locked',
        'featured' => 0
    ],
    [
        'category_slug' => 'implements',
        'name' => 'Shaktiman Rotavator',
        'slug' => 'shaktiman-rotavator',
        'short_description' => 'Heavy duty rotary tiller for seedbed preparation.',
        'description' => 'Prepares the soil perfectly for sowing in a single pass. Saves time and fuel.',
        'specifications' => "- Size: 6 Feet\n- Blades: 42\n- Tractor HP Req: 40-50 HP",
        'applications' => "Soil Preparation, Tilling",
        'price' => 110000.00,
        'price_visibility' => 'public',
        'featured' => 1
    ],
    [
        'category_slug' => 'implements',
        'name' => 'Fieldking Disc Harrow',
        'slug' => 'fieldking-disc-harrow',
        'short_description' => 'Trailing type disc harrow for deep tillage.',
        'description' => 'Breaks up clods and mixes crop residue effectively.',
        'specifications' => "- Discs: 16\n- Disc Diameter: 22 inches\n- Tractor HP Req: 50+ HP",
        'applications' => "Deep Tillage",
        'price' => 75000.00,
        'price_visibility' => 'public',
        'featured' => 0
    ],
    [
        'category_slug' => 'implements',
        'name' => 'Lemken Reversible Plough',
        'slug' => 'lemken-reversible-plough',
        'short_description' => 'Hydraulic reversible plough for smooth plowing.',
        'description' => 'High-quality German technology for smooth and efficient plowing with less fuel consumption.',
        'specifications' => "- Furrows: 2\n- Working Width: 60-70 cm\n- Tractor HP Req: 45-55 HP",
        'applications' => "Plowing",
        'price' => 150000.00,
        'price_visibility' => 'locked',
        'featured' => 0
    ],
    [
        'category_slug' => 'implements',
        'name' => 'Garud Cultivator',
        'slug' => 'garud-cultivator',
        'short_description' => '9 tyne spring loaded cultivator.',
        'description' => 'Ideal for loosening and aerating soil to a depth of 9 inches.',
        'specifications' => "- Tynes: 9\n- Frame: Heavy Duty\n- Tractor HP Req: 35-45 HP",
        'applications' => "Cultivation, Aeration",
        'price' => 25000.00,
        'price_visibility' => 'public',
        'featured' => 0
    ]
];

foreach ($products as $p) {
    $stmt = $pdo->prepare("SELECT id FROM products WHERE slug = ?");
    $stmt->execute([$p['slug']]);
    if (!$stmt->fetch()) {
        $catId = $categoryIds[$p['category_slug']];
        $stmt = $pdo->prepare("
            INSERT INTO products (category_id, name, slug, short_description, description, specifications, applications, price, price_visibility, featured, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')
        ");
        $stmt->execute([
            $catId, $p['name'], $p['slug'], $p['short_description'], $p['description'],
            $p['specifications'], $p['applications'], $p['price'], $p['price_visibility'], $p['featured']
        ]);
        echo "Inserted product: {$p['name']}\n";
    } else {
        echo "Product already exists: {$p['name']}\n";
    }
}

echo "Seeding completed!\n";
