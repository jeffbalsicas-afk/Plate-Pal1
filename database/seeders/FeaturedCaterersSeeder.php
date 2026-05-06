<?php

namespace Database\Seeders;

use App\Models\Caterer;
use Illuminate\Database\Seeder;

class FeaturedCaterersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mark specific caterers as featured based on their names
        Caterer::whereIn('business_name', [
            "Lola Maria's Kitchen",
            'Kusina ni Aling Nena',
            'TasteBuds Catering',
            'Bahay Kubo Kitchen',
            'Sarap Pinoy Express',
            'DeliciaHaus Catering',
        ])->update(['is_featured' => true]);
    }
}
