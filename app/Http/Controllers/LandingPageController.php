<?php

namespace App\Http\Controllers;

use App\Models\Caterer;
use App\Models\User;
use Throwable;

class LandingPageController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index()
    {
        $featuredCaterers = $this->featuredCaterers();

        return view('landingpage.home', compact('featuredCaterers'));
    }

    /**
     * Display the "How it works" page.
     */
    public function howItWorks()
    {
        return view('landingpage.how-it-works');
    }

    /**
     * Display the "For caterers" page.
     */
    public function forCaterers()
    {
        return view('landingpage.for-caterers');
    }

    private function featuredCaterers()
    {
        $featured = collect();

        try {
            $caterers = User::where('role', 'caterer')
                ->where('approval_status', 'approved')
                ->where('is_active', true)
                ->where('is_featured', true)
                ->orderByDesc('rating')
                ->limit(6)
                ->get();

            $featured = $caterers->map(fn ($caterer) => [
                'name' => $caterer->business_name ?? $caterer->name,
                'location' => $caterer->barangay ?? 'Tagum City',
                'cuisine' => $caterer->cuisine ?? 'Catering Services',
                'rating' => number_format($caterer->rating ?: 0, 1),
                'reviews' => $caterer->reviews_count ?? 0,
                'price' => $this->priceLabel($caterer->price_min, $caterer->price_max, $caterer->price_range),
                'image' => $caterer->profile_image ?: '/assets/PlatePal_logo.jpg',
                'guests' => $this->guestLabel($caterer->min_guest, $caterer->max_guest),
                'response' => 'Direct inquiry',
            ]);
        } catch (Throwable) {
            //
        }

        if ($featured->count() < 6) {
            try {
                $legacyCaterers = Caterer::where('is_featured', true)
                    ->where('is_active', true)
                    ->orderByDesc('rating')
                    ->limit(6 - $featured->count())
                    ->get();

                $legacyFeatured = $legacyCaterers->map(fn ($caterer) => [
                    'name' => $caterer->business_name ?? $caterer->name,
                    'location' => $caterer->location ?? 'Tagum City',
                    'cuisine' => $caterer->cuisine ?? 'Catering Services',
                    'rating' => number_format($caterer->rating ?: 0, 1),
                    'reviews' => $caterer->reviews_count ?? 0,
                    'price' => $this->priceLabel(null, null, $caterer->price_range),
                    'image' => $caterer->profile_image ?: '/assets/PlatePal_logo.jpg',
                    'guests' => 'Ask for capacity',
                    'response' => 'Direct inquiry',
                ]);

                $featured = $featured
                    ->merge($legacyFeatured)
                    ->unique(fn ($caterer) => strtolower($caterer['name']))
                    ->values()
                    ->take(6);
            } catch (Throwable) {
                //
            }
        }

        return $featured;
    }

    private function priceLabel($priceMin, $priceMax, ?string $priceRange): string
    {
        if ($priceMin && $priceMax) {
            return 'PHP '.number_format($priceMin, 0).'-'.number_format($priceMax, 0).'/head';
        }

        if ($priceRange) {
            return 'PHP '.$priceRange.'/head';
        }

        return 'Ask for pricing';
    }

    private function guestLabel($minGuests, $maxGuests): string
    {
        if ($minGuests && $maxGuests) {
            return $minGuests.'-'.$maxGuests.' guests';
        }

        return 'Ask for capacity';
    }
}
