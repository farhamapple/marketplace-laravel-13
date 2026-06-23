<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $categories = [
            ['name' => 'Elektronik', 'slug' => 'elektronik'],
            ['name' => 'Fashion Pria', 'slug' => 'fashion-pria'],
            ['name' => 'Fashion Wanita', 'slug' => 'fashion-wanita'],
            ['name' => 'Kesehatan & Kecantikan', 'slug' => 'kesehatan-kecantikan'],
            ['name' => 'Olahraga', 'slug' => 'olahraga'],
            ['name' => 'Rumah & Dapur', 'slug' => 'rumah-dapur'],
            ['name' => 'Buku & Alat Tulis', 'slug' => 'buku-alat-tulis'],
            ['name' => 'Mainan & Hobi', 'slug' => 'mainan-hobi'],
            ['name' => 'Otomotif', 'slug' => 'otomotif'],
            ['name' => 'Makanan & Minuman', 'slug' => 'makanan-minuman'],
        ];

        foreach ($categories as $data) {
            Category::create($data);
        }

        $products = [
            ['category_id' => 1, 'name' => 'Smartphone Samsung Galaxy A55', 'price' => 4299000, 'stock' => 85],
            ['category_id' => 1, 'name' => 'Laptop ASUS VivoBook 15', 'price' => 8499000, 'stock' => 40],
            ['category_id' => 1, 'name' => 'TWS Earbuds Xiaomi Redmi Buds 5', 'price' => 349000, 'stock' => 120],
            ['category_id' => 1, 'name' => 'Smartwatch Amazfit GTS 4 Mini', 'price' => 999000, 'stock' => 60],
            ['category_id' => 1, 'name' => 'Tablet Lenovo Tab M10 Plus', 'price' => 3199000, 'stock' => 35],
            ['category_id' => 1, 'name' => 'Power Bank Anker 20000mAh', 'price' => 459000, 'stock' => 200],
            ['category_id' => 1, 'name' => 'Kamera Mirrorless Sony ZV-E10', 'price' => 7999000, 'stock' => 25],
            ['category_id' => 1, 'name' => 'Monitor LG 24 inch IPS FHD', 'price' => 2399000, 'stock' => 50],
            ['category_id' => 1, 'name' => 'Keyboard Mechanical Rexus Dacomaster', 'price' => 699000, 'stock' => 75],
            ['category_id' => 1, 'name' => 'Webcam Logitech C270 HD', 'price' => 389000, 'stock' => 90],
            ['category_id' => 2, 'name' => 'Kemeja Flannel Pria Slim Fit', 'price' => 189000, 'stock' => 150],
            ['category_id' => 2, 'name' => 'Celana Chino Pria Abu-abu', 'price' => 229000, 'stock' => 130],
            ['category_id' => 2, 'name' => 'Jaket Bomber Pria Hitam', 'price' => 349000, 'stock' => 80],
            ['category_id' => 2, 'name' => 'Sepatu Sneakers Pria Putih', 'price' => 459000, 'stock' => 95],
            ['category_id' => 2, 'name' => 'Kaos Polo Pria Lengan Pendek', 'price' => 129000, 'stock' => 200],
            ['category_id' => 2, 'name' => 'Celana Jeans Pria Slim Fit', 'price' => 279000, 'stock' => 110],
            ['category_id' => 2, 'name' => 'Topi Baseball Cap Polos', 'price' => 89000, 'stock' => 180],
            ['category_id' => 2, 'name' => 'Dompet Kulit Pria RFID', 'price' => 199000, 'stock' => 100],
            ['category_id' => 2, 'name' => 'Ikat Pinggang Kulit Pria', 'price' => 149000, 'stock' => 120],
            ['category_id' => 2, 'name' => 'Tas Ransel Pria 30L Canvas', 'price' => 319000, 'stock' => 65],
            ['category_id' => 3, 'name' => 'Dress Midi Floral Wanita', 'price' => 219000, 'stock' => 140],
            ['category_id' => 3, 'name' => 'Blouse Wanita Kain Rayon', 'price' => 159000, 'stock' => 160],
            ['category_id' => 3, 'name' => 'Celana Palazzo Wanita', 'price' => 189000, 'stock' => 120],
            ['category_id' => 3, 'name' => 'Sepatu Heels Wanita 5cm', 'price' => 379000, 'stock' => 75],
            ['category_id' => 3, 'name' => 'Tas Tote Bag Wanita Canvas', 'price' => 149000, 'stock' => 200],
            ['category_id' => 3, 'name' => 'Rok Mini Wanita Pleated', 'price' => 169000, 'stock' => 95],
            ['category_id' => 3, 'name' => 'Cardigan Rajut Wanita Oversize', 'price' => 249000, 'stock' => 85],
            ['category_id' => 3, 'name' => 'Sandal Flat Wanita Kulit Sintetis', 'price' => 129000, 'stock' => 150],
            ['category_id' => 3, 'name' => 'Scrunchie Set Rambut 10pcs', 'price' => 49000, 'stock' => 300],
            ['category_id' => 3, 'name' => 'Dompet Wristlet Wanita', 'price' => 179000, 'stock' => 110],
            ['category_id' => 4, 'name' => 'Serum Vitamin C Wardah 20ml', 'price' => 89000, 'stock' => 250],
            ['category_id' => 4, 'name' => 'Sunscreen SPF 50 Mineral 30ml', 'price' => 119000, 'stock' => 200],
            ['category_id' => 4, 'name' => 'Masker Sheet Korea 10pcs', 'price' => 79000, 'stock' => 300],
            ['category_id' => 4, 'name' => 'Lipstik Matte Implora 3.8gr', 'price' => 35000, 'stock' => 400],
            ['category_id' => 4, 'name' => 'Pelembab Wajah Cetaphil 250ml', 'price' => 169000, 'stock' => 150],
            ['category_id' => 4, 'name' => 'Sabun Muka Pond\'s Age Miracle', 'price' => 49000, 'stock' => 350],
            ['category_id' => 4, 'name' => 'Parfum Pria Axe Dark Temptation 150ml', 'price' => 89000, 'stock' => 180],
            ['category_id' => 4, 'name' => 'Vitamin C Suplemen 1000mg 30 Tablet', 'price' => 59000, 'stock' => 400],
            ['category_id' => 4, 'name' => 'Sikat Gigi Elektrik Oral-B', 'price' => 299000, 'stock' => 90],
            ['category_id' => 4, 'name' => 'Timbangan Badan Digital', 'price' => 179000, 'stock' => 80],
            ['category_id' => 5, 'name' => 'Sepatu Lari Nike Revolution 6', 'price' => 899000, 'stock' => 70],
            ['category_id' => 5, 'name' => 'Matras Yoga TPE 6mm', 'price' => 249000, 'stock' => 100],
            ['category_id' => 5, 'name' => 'Dumbbell Set 5kg Hex Rubber', 'price' => 189000, 'stock' => 60],
            ['category_id' => 5, 'name' => 'Jersey Futsal Printing Custom', 'price' => 159000, 'stock' => 200],
            ['category_id' => 5, 'name' => 'Raket Badminton Yonex Astrox 22', 'price' => 599000, 'stock' => 45],
            ['category_id' => 5, 'name' => 'Tas Gym Olahraga 30L', 'price' => 199000, 'stock' => 90],
            ['category_id' => 5, 'name' => 'Resistance Band Set 5 Level', 'price' => 99000, 'stock' => 150],
            ['category_id' => 5, 'name' => 'Bola Sepak Futsal Mikasa', 'price' => 329000, 'stock' => 55],
            ['category_id' => 5, 'name' => 'Topi Lari Anti UV Dry Fit', 'price' => 79000, 'stock' => 130],
            ['category_id' => 5, 'name' => 'Skipping Rope Adjustable Steel', 'price' => 59000, 'stock' => 200],
            ['category_id' => 6, 'name' => 'Rice Cooker Miyako 1.8L', 'price' => 299000, 'stock' => 75],
            ['category_id' => 6, 'name' => 'Blender Philips 2L 600W', 'price' => 449000, 'stock' => 55],
            ['category_id' => 6, 'name' => 'Set Panci Anti Lengket 3pcs', 'price' => 359000, 'stock' => 65],
            ['category_id' => 6, 'name' => 'Dispenser Air Galon Cosmos', 'price' => 549000, 'stock' => 40],
            ['category_id' => 6, 'name' => 'Bantal Tidur Memory Foam', 'price' => 219000, 'stock' => 90],
            ['category_id' => 6, 'name' => 'Tempat Sampah Injak 10L', 'price' => 89000, 'stock' => 120],
            ['category_id' => 6, 'name' => 'Vacuum Cleaner Portable 600W', 'price' => 399000, 'stock' => 50],
            ['category_id' => 6, 'name' => 'Rak Buku Kayu 5 Susun', 'price' => 679000, 'stock' => 30],
            ['category_id' => 6, 'name' => 'Lampu LED Philips 14W E27', 'price' => 49000, 'stock' => 500],
            ['category_id' => 6, 'name' => 'Kursi Lipat Camping Portable', 'price' => 149000, 'stock' => 85],
            ['category_id' => 7, 'name' => 'Novel Atomic Habits James Clear', 'price' => 109000, 'stock' => 200],
            ['category_id' => 7, 'name' => 'Buku Pemrograman Python untuk Pemula', 'price' => 129000, 'stock' => 150],
            ['category_id' => 7, 'name' => 'Planner Agenda Bulanan 2025', 'price' => 79000, 'stock' => 180],
            ['category_id' => 7, 'name' => 'Set Pulpen Pilot G2 12pcs', 'price' => 89000, 'stock' => 300],
            ['category_id' => 7, 'name' => 'Stabilo Boss Set Warna 6pcs', 'price' => 59000, 'stock' => 250],
            ['category_id' => 7, 'name' => 'Sticky Notes Neon 5x5 200 Lembar', 'price' => 29000, 'stock' => 400],
            ['category_id' => 7, 'name' => 'Buku Sketsa A4 200gsm 50 Lembar', 'price' => 69000, 'stock' => 180],
            ['category_id' => 7, 'name' => 'Kalkulator Casio FX-350ES Plus', 'price' => 149000, 'stock' => 120],
            ['category_id' => 7, 'name' => 'Tas Sekolah / Kampus 20L', 'price' => 199000, 'stock' => 100],
            ['category_id' => 7, 'name' => 'Cat Akrilik Set 12 Warna 12ml', 'price' => 119000, 'stock' => 140],
            ['category_id' => 8, 'name' => 'LEGO Classic Bricks 484pcs', 'price' => 599000, 'stock' => 45],
            ['category_id' => 8, 'name' => 'Action Figure Gundam RX-78 MG', 'price' => 799000, 'stock' => 30],
            ['category_id' => 8, 'name' => 'Board Game Monopoly Classic', 'price' => 249000, 'stock' => 60],
            ['category_id' => 8, 'name' => 'Mainan RC Car Off-Road 1:16', 'price' => 379000, 'stock' => 40],
            ['category_id' => 8, 'name' => 'Puzzle 1000 Pcs Landscape Jepang', 'price' => 149000, 'stock' => 80],
            ['category_id' => 8, 'name' => 'Drone Mini Quadcopter DJI Tello', 'price' => 1499000, 'stock' => 20],
            ['category_id' => 8, 'name' => 'Kartu UNO Original Mattel', 'price' => 79000, 'stock' => 200],
            ['category_id' => 8, 'name' => 'Boneka Barbie Fashionistas', 'price' => 199000, 'stock' => 90],
            ['category_id' => 8, 'name' => 'Slime Kit DIY 10 Warna', 'price' => 89000, 'stock' => 150],
            ['category_id' => 8, 'name' => 'Miniatur Die-Cast Hot Wheels 5-Pack', 'price' => 119000, 'stock' => 120],
            ['category_id' => 9, 'name' => 'Helm Full Face INK CL-MAX', 'price' => 649000, 'stock' => 55],
            ['category_id' => 9, 'name' => 'Sarung Tangan Motor Kulit Riding', 'price' => 179000, 'stock' => 90],
            ['category_id' => 9, 'name' => 'Jaket Motor Touring Anti Angin', 'price' => 499000, 'stock' => 60],
            ['category_id' => 9, 'name' => 'Kaca Spion Motor Universal Bulat', 'price' => 89000, 'stock' => 150],
            ['category_id' => 9, 'name' => 'Oli Mesin Motor Castrol Power 1L', 'price' => 79000, 'stock' => 300],
            ['category_id' => 9, 'name' => 'Cover Motor Anti Hujan Waterproof', 'price' => 129000, 'stock' => 120],
            ['category_id' => 9, 'name' => 'Kunci Stang Motor Anti Maling', 'price' => 99000, 'stock' => 180],
            ['category_id' => 9, 'name' => 'Air Freshener Mobil Gantung', 'price' => 29000, 'stock' => 500],
            ['category_id' => 9, 'name' => 'Dashboard Camera Mobil Full HD', 'price' => 499000, 'stock' => 45],
            ['category_id' => 9, 'name' => 'Pompa Angin Elektrik Portable 12V', 'price' => 199000, 'stock' => 80],
            ['category_id' => 10, 'name' => 'Kopi Arabika Gayo Aceh 250gr', 'price' => 79000, 'stock' => 300],
            ['category_id' => 10, 'name' => 'Teh Herbal Rosella Kering 100gr', 'price' => 49000, 'stock' => 250],
            ['category_id' => 10, 'name' => 'Coklat Batang Silverqueen 65gr', 'price' => 25000, 'stock' => 500],
            ['category_id' => 10, 'name' => 'Snack Kacang Atom Pedas 500gr', 'price' => 39000, 'stock' => 400],
            ['category_id' => 10, 'name' => 'Madu Murni Hutan Kalimantan 250ml', 'price' => 129000, 'stock' => 150],
            ['category_id' => 10, 'name' => 'Granola Oat Sehat Original 500gr', 'price' => 69000, 'stock' => 200],
            ['category_id' => 10, 'name' => 'Sambal Matah Bali Kemasan 150gr', 'price' => 35000, 'stock' => 350],
            ['category_id' => 10, 'name' => 'Kurma Medjool Premium 500gr', 'price' => 159000, 'stock' => 180],
            ['category_id' => 10, 'name' => 'Minuman Sari Jahe Merah Sachet 10pcs', 'price' => 45000, 'stock' => 400],
            ['category_id' => 10, 'name' => 'Keripik Tempe Krispy Original 200gr', 'price' => 29000, 'stock' => 450],
        ];

        foreach ($products as $data) {
            Product::create($data);
        }
    }
}
