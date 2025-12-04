<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Address;
use App\Models\Transaction;
use App\Models\TransactionItem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // If not production, truncate related tables for a clean slate.
        if (!app()->environment('production')) {
            // Temporarily disable foreign key checks (MySQL) to allow truncation order
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Truncate child tables first to avoid FK constraint issues
            DB::table('transaction_items')->truncate();
            DB::table('transactions')->truncate();
            DB::table('product_images')->truncate();
            DB::table('products')->truncate();
            DB::table('addresses')->truncate();
            DB::table('categories')->truncate();
            DB::table('users')->truncate();

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $usersData = [
            ['name' => 'Administrator', 'email' => 'admin@gmail.com', 'phone' => '081100000000', 'is_admin' => true],
            ['name' => 'Budi Santoso', 'email' => 'budi.santoso@gmail.com', 'phone' => '081200000001', 'is_admin' => false],
            ['name' => 'Siti Aminah', 'email' => 'siti.aminah@gmail.com', 'phone' => '081200000002', 'is_admin' => false],
            ['name' => 'Andi Wijaya', 'email' => 'andi.wijaya@gmail.com', 'phone' => '081200000003', 'is_admin' => false],
            ['name' => 'Rini Putri', 'email' => 'rini.putri@gmail.com', 'phone' => '081200000004', 'is_admin' => false],
            ['name' => 'Dewi Lestari', 'email' => 'dewi.lestari@gmail.com', 'phone' => '081200000005', 'is_admin' => false],
            ['name' => 'Tono Prasetyo', 'email' => 'tono.prasetyo@gmail.com', 'phone' => '081200000006', 'is_admin' => false],
            ['name' => 'Rudi Hartono', 'email' => 'rudi.hartono@gmail.com', 'phone' => '081200000007', 'is_admin' => false],
            ['name' => 'Maya Kurnia', 'email' => 'maya.kurnia@gmail.com', 'phone' => '081200000008', 'is_admin' => false],
            ['name' => 'Arif Nugroho', 'email' => 'arif.nugroho@gmail.com', 'phone' => '081200000009', 'is_admin' => false],
        ];

        $users = [];
        foreach ($usersData as $idx => $u) {
            // Use updateOrCreate so seeder is idempotent
            $user = User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'phone' => $u['phone'],
                    'is_admin' => $u['is_admin'],
                    'password' => Hash::make('password'),
                ]
            );
            $users[] = $user;
        }


        $categories = [];
        $kategoriNames = ['Elektronik', 'Pakaian', 'Rumah Tangga', 'Kecantikan', 'Olahraga', 'Mainan', 'Buku', 'Makanan & Minuman', 'Gadget', 'Aksesoris'];
        foreach ($kategoriNames as $name) {
            $slug = strtolower(str_replace(' ', '-', $name));
            $categories[] = Category::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'description' => "Kategori produk $name"]
            );
        }


        $products = [];
        $productNames = [
            'Televisi LED 43"', 'Kaos Polos Pria', 'Set Panci Stainless', 'Pelembap Wajah', 'Sepatu Lari',
            'Boneka Teddy Bear', 'Novel Indonesia', 'Kopi Luwak Sachet', 'Smartphone X Pro', 'Dompet Kulit'
        ];

        foreach ($productNames as $i => $pname) {
            $slug = 'produk-' . ($i + 1);
            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $pname,
                    'description' => "Deskripsi singkat untuk $pname.",
                    'price' => rand(50000, 500000),
                    'stock' => rand(10, 200),
                    'category_id' => $categories[$i % count($categories)]->id,
                    'active' => true,
                ]
            );

            // Ensure at least one product image exists
            if (! ProductImage::where('product_id', $product->id)->exists()) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'url' => 'products/sample-' . ($i + 1) . '.jpg',
                    'position' => 0,
                    'alt_text' => $pname,
                ]);
            }

            $products[] = $product;
        }


        foreach ($users as $i => $user) {
            // Adapt to addresses table schema: use address_text and no phone/recipient_name
            Address::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'label' => $i === 0 ? 'Kantor' : 'Rumah',
                    'address_text' => 'Jalan Contoh No. ' . ($i + 1),
                    'city' => 'Bandung',
                    'province' => 'Jawa Barat',
                    'postal_code' => '40135',
                    'is_primary' => $i === 0,
                ]
            );
        }


        $statuses = ['pending', 'paid', 'shipped', 'completed', 'cancelled'];
        foreach (range(1, 10) as $n) {
            $buyer = $users[$n - 1];
            $items = [$products[array_rand($products)]];
            $subtotal = 0;

            $transaction = Transaction::create([
                'user_id' => $buyer->id,
                'status' => $statuses[array_rand($statuses)],
                'total_amount' => 0,
                'shipping_fee' => 10000,
            ]);

            foreach ($items as $item) {
                $qty = rand(1, 3);
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item->id,
                    'product_name' => $item->name,
                    'quantity' => $qty,
                    'price' => $item->price,
                ]);
                $subtotal += $item->price * $qty;
            }

            $transaction->total_amount = $subtotal + $transaction->shipping_fee;
            $transaction->save();
        }
    }
}
