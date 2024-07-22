<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        for ($i = 1; $i <= 20; $i++) {

            Product::create([
                'id_kategori' => rand(1, 3),
                'id_subkategori' => rand(1, 3),
                'nama_barang' => 'Lorem Ipsum Dolor Sit Amet',
                'gambar' => '17213077347.jpg',
                'deskripsi' => 'Lorem Ipsum Dolor Sit Amet',
                'harga' => rand(1000, 100000),
                'diskon' => 0,
                'bahan' => 'Lorem Ipsum Dolor Sit Amet',
                'tags' => Str::random(8),
                'sku' => 'Lorem Ipsum Dolor Sit Amet',
                'ukuran' => 'S,M,L,XL',
                'warna' => 'Hitam,Biru,Kuning,Merah,Hijau',
            ]);
        }
    }
}
