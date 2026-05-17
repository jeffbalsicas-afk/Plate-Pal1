<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class RecalculateBookingPrices extends Command
{
    protected $signature = 'bookings:recalculate-prices';
    protected $description = 'Recalculate final_price for all completed bookings to include add-ons and menu items';

    public function handle()
    {
        $this->info('Starting to recalculate booking prices...');

        $bookings = Booking::with(['bookingItems', 'caterer'])
            ->where('status', 'completed')
            ->get();

        $updated = 0;
        $skipped = 0;

        foreach ($bookings as $booking) {
            $calculatedPrice = $booking->calculated_total;
            
            // Only update if there's a calculated price and it's different from current
            if ($calculatedPrice > 0 && $calculatedPrice != $booking->final_price) {
                $oldPrice = $booking->final_price ?? 0;
                $booking->update(['final_price' => $calculatedPrice]);
                
                $this->line("Booking #{$booking->id}: ₱{$oldPrice} → ₱{$calculatedPrice}");
                $updated++;
            } else {
                $skipped++;
            }
        }

        $this->newLine();
        $this->info("✅ Recalculation complete!");
        $this->info("Updated: {$updated} bookings");
        $this->info("Skipped: {$skipped} bookings (no change needed)");

        return Command::SUCCESS;
    }
}
