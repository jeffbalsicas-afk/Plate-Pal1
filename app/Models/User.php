<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'phone', 'business_name', 'barangay', 'cuisine', 'price_range', 'profile_image', 'rating', 'reviews_count', 'auto_feature_reviews', 'is_verified', 'is_active', 'approval_status', 'rejection_reason', 'description', 'price_min', 'price_max', 'min_guest', 'max_guest', 'is_featured', 'our_story', 'what_makes_special', 'services_offered', 'gallery_images'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'auto_feature_reviews' => 'boolean',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'services_offered' => 'array',
            'gallery_images' => 'array',
        ];
    }

    public function getInitialsAttribute(): string
    {
        $name = $this->business_name ?? $this->name;
        $parts = explode(' ', trim($name));
        $initials = strtoupper($parts[0][0] ?? '');
        if (count($parts) > 1) {
            $initials .= strtoupper($parts[1][0] ?? '');
        }
        return $initials;
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function receivedReviews()
    {
        return $this->hasMany(Review::class, 'caterer_id');
    }

    public function writtenReviews()
    {
        return $this->hasMany(Review::class, 'client_id');
    }
}
