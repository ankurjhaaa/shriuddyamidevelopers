<?php
$dbFile = __DIR__ . '/database.sqlite';
$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$schema = "
CREATE TABLE IF NOT EXISTS admins (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    image TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id INTEGER,
    name TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    short_description TEXT,
    description TEXT,
    specifications TEXT,
    applications TEXT,
    price REAL,
    price_visibility TEXT DEFAULT 'public', -- 'public', 'locked', 'hidden'
    featured INTEGER DEFAULT 0,
    status TEXT DEFAULT 'active', -- 'active', 'inactive'
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS product_images (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER,
    image_path TEXT NOT NULL,
    is_primary INTEGER DEFAULT 0,
    FOREIGN KEY(product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS leads (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER,
    customer_name TEXT NOT NULL,
    phone TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(product_id) REFERENCES products(id)
);

CREATE TABLE IF NOT EXISTS settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    key TEXT NOT NULL UNIQUE,
    value TEXT
);
";

$pdo->exec($schema);

// Insert default admin if not exists
$stmt = $pdo->prepare("SELECT COUNT(*) FROM admins");
$stmt->execute();
if ($stmt->fetchColumn() == 0) {
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->prepare("INSERT INTO admins (username, password_hash) VALUES ('admin', ?)")->execute([$hash]);
    echo "Default admin created: admin / admin123\n";
}

// Insert default settings
$defaultSettings = [
    'store_name' => 'Agri & Industrial Store',
    'logo' => '',
    'banner' => '',
    'phone' => '+919876543210',
    'whatsapp' => '+919876543210',
    'address' => '123 Main Street, City',
    'gst' => '22AAAAA0000A1Z5',
    'social_links' => '{}'
];

foreach ($defaultSettings as $key => $value) {
    $stmt = $pdo->prepare("INSERT OR IGNORE INTO settings (key, value) VALUES (?, ?)");
    $stmt->execute([$key, $value]);
}

// Seed Categories and Products if empty
if ($pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn() == 0) {
    echo "Seeding categories and products...\n";

    // Insert Categories
    $categoriesData = [
        ['name' => 'Commercial Atta Chakki', 'slug' => 'commercial-atta-chakki', 'image' => 'assets/images/categories/dummy_atta_chakki.png'],
        ['name' => 'Domestic Flour Mill', 'slug' => 'domestic-flour-mill', 'image' => 'assets/images/categories/dummy_domestic_mill.png'],
        ['name' => 'Rice Mill Machine', 'slug' => 'rice-mill-machine', 'image' => 'assets/images/categories/dummy_rice_mill.png'],
        ['name' => 'Destoner Machine', 'slug' => 'destoner-machine', 'image' => 'assets/images/categories/dummy_destoner.png'],
    ];

    $catIdMap = [];
    foreach ($categoriesData as $cat) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug, image) VALUES (?, ?, ?)");
        $stmt->execute([$cat['name'], $cat['slug'], $cat['image']]);
        $catIdMap[$cat['name']] = $pdo->lastInsertId();
    }

    // Insert Products
    $productsData = [
        // Commercial Atta Chakki
        [
            'cat' => 'Commercial Atta Chakki',
            'name' => 'Semi-Automatic 5 HP STONE ATTA CHAKKI HEAVY DUTY, 80 Kg/hr',
            'price' => 65000,
            'price_visibility' => 'public',
            'short' => 'Heavy duty 5 HP stone atta chakki suitable for commercial use.',
            'desc' => 'High quality semi-automatic stone atta chakki for commercial milling. Built with durable materials for long-lasting performance and continuous operation.',
            'specs' => '{"Motor Power":"5 HP", "Capacity":"80 Kg/hr", "Operation Mode":"Semi-Automatic", "Phase":"Three Phase"}'
        ],
        [
            'cat' => 'Commercial Atta Chakki',
            'name' => 'Fully Automatic Commercial Atta Chakki Machine, 16 inch',
            'price' => 30000,
            'price_visibility' => 'public',
            'short' => '16-inch fully automatic commercial atta chakki machine.',
            'desc' => 'Fully automatic 16-inch commercial flour mill designed for high efficiency and consistent grinding quality.',
            'specs' => '{"Size":"16 inch", "Operation Mode":"Fully Automatic", "Material":"Cast Iron/Mild Steel"}'
        ],
        [
            'cat' => 'Commercial Atta Chakki',
            'name' => '2 HP Automatic Atta Chakki Machine, 30 kg/hr',
            'price' => 16000,
            'price_visibility' => 'public',
            'short' => 'Compact 2 HP automatic atta chakki with 30 kg/hr capacity.',
            'desc' => 'Ideal for small commercial setups or large families, offering 30 kg per hour grinding capacity with a reliable 2 HP motor.',
            'specs' => '{"Motor Power":"2 HP", "Capacity":"30 kg/hr", "Operation Mode":"Automatic"}'
        ],
        [
            'cat' => 'Commercial Atta Chakki',
            'name' => 'Automatic 3 HP Commercial Atta Chakki Machine, 50 kg/hr',
            'price' => 35500,
            'price_visibility' => 'public',
            'short' => '3 HP automatic commercial atta chakki with 50 kg/hr capacity.',
            'desc' => 'Efficient and robust 3 HP flour mill delivering up to 50 kg per hour. Perfect for medium-scale milling businesses.',
            'specs' => '{"Motor Power":"3 HP", "Capacity":"50 kg/hr", "Operation Mode":"Automatic"}'
        ],
        [
            'cat' => 'Commercial Atta Chakki',
            'name' => 'For Commercial Motor Power: 15HP Double Stage Heavy Duty Aata Chakki',
            'price' => 95000,
            'price_visibility' => 'public',
            'short' => '15HP double stage heavy duty commercial flour mill.',
            'desc' => 'Industrial-grade 15HP double stage atta chakki designed for massive production and heavy-duty continuous operations.',
            'specs' => '{"Motor Power":"15 HP", "Stage":"Double Stage", "Type":"Heavy Duty"}'
        ],
        [
            'cat' => 'Commercial Atta Chakki',
            'name' => 'Wheat Flour Mill Machine',
            'price' => 1000000,
            'price_visibility' => 'public',
            'short' => 'Large scale complete wheat flour mill machine setup.',
            'desc' => 'A complete industrial setup for large scale wheat flour milling, offering complete automation and extremely high capacity.',
            'specs' => '{"Type":"Plant Setup", "Application":"Industrial Wheat Milling", "Automation Grade":"Fully Automatic"}'
        ],
        [
            'cat' => 'Commercial Atta Chakki',
            'name' => 'Single Round Flour Milling Machine, 300 kg/hr',
            'price' => 550000,
            'price_visibility' => 'public',
            'short' => 'High capacity 300 kg/hr single round flour milling machine.',
            'desc' => 'Industrial standard single round flour milling machine capable of processing 300 kg per hour with high precision.',
            'specs' => '{"Capacity":"300 kg/hr", "Type":"Single Round", "Application":"Commercial/Industrial"}'
        ],
        
        // Domestic Flour Mill
        [
            'cat' => 'Domestic Flour Mill',
            'name' => 'Natraj Zest Atta Chakki with Vacuum Cleaner',
            'price' => 20990,
            'price_visibility' => 'public',
            'short' => 'Premium domestic atta chakki with built-in vacuum cleaner.',
            'desc' => 'Natraj Zest offers clean and hygienic grinding at home with its integrated vacuum cleaner system and elegant design.',
            'specs' => '{"Brand":"Natraj", "Model":"Zest", "Special Feature":"Vacuum Cleaner Included", "Capacity":"8-10 Kg/hr"}'
        ],
        [
            'cat' => 'Domestic Flour Mill',
            'name' => '2 HP Natraj Atta Chakki, Capacity: 50 kg/hr',
            'price' => 6000,
            'price_visibility' => 'public',
            'short' => '2 HP Natraj atta chakki for heavy domestic or light commercial use.',
            'desc' => 'Powerful 2 HP domestic mill offering up to 50 kg/hr capacity. Durable construction and efficient grinding.',
            'specs' => '{"Brand":"Natraj", "Motor Power":"2 HP", "Capacity":"50 kg/hr"}'
        ],
        [
            'cat' => 'Domestic Flour Mill',
            'name' => '1 HP Domestic Flour Mill, Capacity: 5-10 Kg/hr',
            'price' => 8500,
            'price_visibility' => 'public',
            'short' => 'Compact 1 HP domestic flour mill for everyday home use.',
            'desc' => 'Perfectly sized for household kitchens, this 1 HP flour mill delivers fresh flour with a capacity of 5-10 kg per hour.',
            'specs' => '{"Motor Power":"1 HP", "Capacity":"5-10 Kg/hr", "Material":"Stainless Steel"}'
        ],

        // Rice Mill Machine
        [
            'cat' => 'Rice Mill Machine',
            'name' => 'Semi-Automatic 3 HP MS Mini Rice Mill, Single Phase',
            'price' => 30000,
            'price_visibility' => 'public',
            'short' => '3 HP mini rice mill for small-scale rice processing.',
            'desc' => 'Reliable MS mini rice mill with a 3 HP single-phase motor. Ideal for small farmers and local processing units.',
            'specs' => '{"Motor Power":"3 HP", "Phase":"Single Phase", "Material":"Mild Steel (MS)"}'
        ],
        [
            'cat' => 'Rice Mill Machine',
            'name' => 'Combined Mini Rice Mill 6w200, 200-250 KG 1Hrs',
            'price' => 35999,
            'price_visibility' => 'public',
            'short' => 'Combined mini rice mill with 200-250 kg/hr capacity.',
            'desc' => 'Efficient combined mini rice mill model 6w200. Can process 200 to 250 kg of rice per hour.',
            'specs' => '{"Model":"6w200", "Capacity":"200-250 KG/Hr", "Type":"Combined"}'
        ],
        [
            'cat' => 'Rice Mill Machine',
            'name' => 'Semi Automatic 6N50 Dsv Mini Rice Mill With 7.5 HP Engine',
            'price' => 45500,
            'price_visibility' => 'public',
            'short' => 'Engine-driven 6N50 mini rice mill with 7.5 HP engine.',
            'desc' => 'Perfect for areas with unstable electricity, this mini rice mill comes powered by a robust 7.5 HP engine.',
            'specs' => '{"Model":"6N50 Dsv", "Engine Power":"7.5 HP", "Operation Mode":"Semi Automatic"}'
        ],
        [
            'cat' => 'Rice Mill Machine',
            'name' => 'Combined Mini Rice Mill',
            'price' => null,
            'price_visibility' => 'hidden',
            'short' => 'Standard combined mini rice mill machine.',
            'desc' => 'High quality combined mini rice mill. Contact us for pricing and further technical details.',
            'specs' => '{"Type":"Combined Mini Rice Mill"}'
        ],

        // Destoner Machine
        [
            'cat' => 'Destoner Machine',
            'name' => '500 Kg/Hr Destoner Machine',
            'price' => 150000,
            'price_visibility' => 'public',
            'short' => '500 kg/hr capacity destoner for grain cleaning.',
            'desc' => 'Effectively removes stones, clods, and heavy impurities from grains with a processing capacity of 500 kg per hour.',
            'specs' => '{"Capacity":"500 Kg/Hr", "Application":"Grain Cleaning", "Automation Grade":"Automatic"}'
        ],
        [
            'cat' => 'Destoner Machine',
            'name' => 'Double Destoner Machine',
            'price' => 99000,
            'price_visibility' => 'public',
            'short' => 'Double deck destoner machine for enhanced cleaning.',
            'desc' => 'Features a double deck system for superior separation of stones and impurities from agricultural produce.',
            'specs' => '{"Type":"Double Destoner", "Deck":"Double Deck"}'
        ],
        [
            'cat' => 'Destoner Machine',
            'name' => '4 TPH De Stoning Machine',
            'price' => null,
            'price_visibility' => 'hidden',
            'short' => 'Industrial 4 Tons Per Hour (TPH) de-stoning machine.',
            'desc' => 'High-capacity industrial destoner capable of handling 4 tons per hour. Ideal for large-scale processing plants.',
            'specs' => '{"Capacity":"4 TPH (Tons Per Hour)", "Application":"Industrial Processing"}'
        ],
        [
            'cat' => 'Destoner Machine',
            'name' => 'Groundnut Destoner Machine',
            'price' => 155000,
            'price_visibility' => 'public',
            'short' => 'Specialized destoner machine for groundnuts.',
            'desc' => 'Specifically designed and calibrated to gently and effectively remove stones from groundnuts without causing damage.',
            'specs' => '{"Application":"Groundnut Cleaning", "Operation Mode":"Automatic"}'
        ]
    ];

    foreach ($productsData as $prod) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $prod['name'])));
        
        $stmt = $pdo->prepare("INSERT INTO products (category_id, name, slug, short_description, description, specifications, price, price_visibility, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')");
        $stmt->execute([
            $catIdMap[$prod['cat']],
            $prod['name'],
            $slug,
            $prod['short'],
            $prod['desc'],
            $prod['specs'],
            $prod['price'],
            $prod['price_visibility']
        ]);
        $productId = $pdo->lastInsertId();

        // Insert 1 primary and 1 secondary dummy image for each product
        $dummyImage1 = 'assets/images/products/dummy_' . rand(1, 5) . '.png';
        $dummyImage2 = 'assets/images/products/dummy_' . rand(1, 5) . '.png';
        
        $pdo->prepare("INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, 1)")->execute([$productId, $dummyImage1]);
        $pdo->prepare("INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, 0)")->execute([$productId, $dummyImage2]);
    }

    echo "Categories and Products seeded.\n";
}

echo "Database initialized successfully.\n";
