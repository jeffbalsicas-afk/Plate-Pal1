<?php

namespace Database\Seeders;

use App\Models\Caterer;
use Illuminate\Database\Seeder;

class CatererSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $caterers = [
            [
                'business_name' => "Lola Maria's Kitchen",
                'name' => "Lola Maria",
                'email' => 'lola.maria@example.com',
                'password' => bcrypt('password'),
                'phone' => '09123456789',
                'location' => 'Magugpo Poblacion',
                'cuisine' => 'Authentic Tagum Native Chicken',
                'rating' => 4.8,
                'reviews_count' => 127,
                'price_range' => '300-500',
                'profile_image' => '/assets/Pusit.png',
                'is_featured' => true,
            ],
            [
                'business_name' => 'Kusina ni Aling Nena',
                'name' => 'Aling Nena',
                'email' => 'nena@example.com',
                'password' => bcrypt('password'),
                'phone' => '09234567890',
                'location' => 'Apokon',
                'cuisine' => 'Mindanao Fusion Cuisine',
                'rating' => 4.9,
                'reviews_count' => 98,
                'price_range' => '400-600',
                'profile_image' => '/assets/nena.png',
                'is_featured' => true,
            ],
            [
                'business_name' => 'TasteBuds Catering',
                'name' => 'TasteBuds Team',
                'email' => 'tastebuds@example.com',
                'password' => bcrypt('password'),
                'phone' => '09345678901',
                'location' => 'Visayan Village',
                'cuisine' => 'Party Packages & Event Buffets',
                'rating' => 4.7,
                'reviews_count' => 156,
                'price_range' => '350-550',
                'profile_image' => '/assets/buds.png',
                'is_featured' => true,
            ],
            [
                'business_name' => 'Bahay Kubo Kitchen',
                'name' => 'Bahay Kubo',
                'email' => 'bahaykubo@example.com',
                'password' => bcrypt('password'),
                'phone' => '09456789012',
                'location' => 'Mankilam',
                'cuisine' => 'Organic Farm-to-Table Dishes',
                'rating' => 4.6,
                'reviews_count' => 73,
                'price_range' => '380-520',
                'profile_image' => '/assets/kubo.png',
                'is_featured' => true,
            ],
            [
                'business_name' => 'Sarap Pinoy Express',
                'name' => 'Sarap Pinoy',
                'email' => 'sarap@example.com',
                'password' => bcrypt('password'),
                'phone' => '09567890123',
                'location' => 'New Balamban',
                'cuisine' => 'Quick Service Party Trays',
                'rating' => 4.5,
                'reviews_count' => 89,
                'price_range' => '250-450',
                'profile_image' => '/assets/Pinoy_Exp.png',
                'is_featured' => true,
            ],
            [
                'business_name' => 'DeliciaHaus Catering',
                'name' => 'DeliciaHaus',
                'email' => 'delicia@example.com',
                'password' => bcrypt('password'),
                'phone' => '09678901234',
                'location' => 'Pagsabangan',
                'cuisine' => 'Premium Seafood Feasts',
                'rating' => 4.9,
                'reviews_count' => 112,
                'price_range' => '500-800',
                'profile_image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=600&q=80',
                'is_featured' => true,
            ],
        ];

        foreach ($caterers as $caterer) {
            Caterer::create($caterer);
        }
    }
}
