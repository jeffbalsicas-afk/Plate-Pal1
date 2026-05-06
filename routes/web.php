<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\CatererController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MessageController;

Route::get('/', [LandingPageController::class, 'index'])->name('home');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::get('/caterer/login', [AuthController::class, 'showCatererLogin'])->name('caterer.login');
    Route::get('/caterer/register', [AuthController::class, 'showCatererRegister'])->name('caterer.register');
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/caterer/login', [AuthController::class, 'Catererlogin']);
Route::post('/caterer/register', [AuthController::class, 'Catererregister']);

// Public routes (accessible to guests)
Route::get('/browse-caterers', [ClientDashboardController::class, 'browsePubic'])->name('browse.caterers');
Route::get('/how-it-works', [LandingPageController::class, 'howItWorks'])->name('how.it.works');
Route::get('/for-caterers', [LandingPageController::class, 'forCaterers'])->name('for.caterers');

// Protected routes (require auth)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
    Route::get('/client/browse-caterers', [ClientDashboardController::class, 'browse'])->name('client.browse');
    Route::get('/client/bookings', [ClientDashboardController::class, 'bookings'])->name('client.bookings');
    Route::post('/client/bookings/{caterer}', [ClientDashboardController::class, 'storeBooking'])->name('client.bookings.store');
    Route::get('/client/bookings/{booking}', [ClientDashboardController::class, 'bookingDetails'])->name('client.bookings.show');
    Route::get('/client/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('client.bookings.edit');
    Route::put('/client/bookings/{booking}', [BookingController::class, 'update'])->name('client.bookings.update');
    Route::post('/client/bookings/{booking}/review', [ClientDashboardController::class, 'storeReview'])->name('client.bookings.review');
    Route::get('/client/saved-caterers', [ClientDashboardController::class, 'savedCaterers'])->name('client.saved-caterers');
    Route::post('/client/saved-caterers/{caterer}', [ClientDashboardController::class, 'toggleSavedCaterer'])->name('client.saved-caterers.toggle');
    Route::get('/client/reviews', [ClientDashboardController::class, 'myReviews'])->name('client.reviews');

    // Caterer routes
    Route::get('/caterer/dashboard', [CatererController::class, 'dashboard'])->name('caterer.dashboard');
    Route::get('/caterer/bookings', [CatererController::class, 'bookings'])->name('caterer.bookings');
    Route::get('/caterer/menu-pricing', [CatererController::class, 'menuAndPricing'])->name('caterer.menu-pricing');
    Route::get('/caterer/messages', [MessageController::class, 'index'])->name('caterer.messages');
    Route::get('/caterer/earnings', [CatererController::class, 'earnings'])->name('caterer.earnings');
    Route::get('/caterer/reviews', [ReviewController::class, 'index'])->name('caterer.reviews');
    Route::post('/caterer/reviews/request-feedback', [ReviewController::class, 'requestFeedback'])->name('caterer.reviews.request-feedback');
    Route::patch('/caterer/reviews/auto-feature', [ReviewController::class, 'updateAutoFeature'])->name('caterer.reviews.auto-feature');
    Route::patch('/caterer/reviews/{review}/visibility', [ReviewController::class, 'updateVisibility'])->name('caterer.reviews.visibility');
    Route::patch('/caterer/reviews/{review}/featured', [ReviewController::class, 'updateFeatured'])->name('caterer.reviews.featured');
    Route::post('/caterer/reviews/{review}/reply', [ReviewController::class, 'reply'])->name('caterer.reviews.reply');
    Route::post('/caterer/reviews/{review}/report', [ReviewController::class, 'report'])->name('caterer.reviews.report');
    Route::get('/caterer/profile', [CatererController::class, 'editProfile'])->name('caterer.profile');
    Route::post('/caterer/profile', [CatererController::class, 'updateProfile'])->name('caterer.profile.update');
    Route::get('/caterer/{id}', [CatererController::class, 'show'])->name('caterer.detail');

    // Menu management routes
    Route::post('/menu/items', [MenuController::class, 'storeMenuItem'])->name('menu.items.store');
    Route::get('/menu/items/{menuItem}/edit', [MenuController::class, 'editMenuItem'])->name('menu.items.edit');
    Route::put('/menu/items/{menuItem}', [MenuController::class, 'updateMenuItem'])->name('menu.items.update');
    Route::delete('/menu/items/{menuItem}', [MenuController::class, 'destroyMenuItem'])->name('menu.items.destroy');

    Route::post('/menu/addons', [MenuController::class, 'storeAddOn'])->name('menu.addons.store');
    Route::get('/menu/addons/{menuItem}/edit', [MenuController::class, 'editAddOn'])->name('menu.addons.edit');
    Route::put('/menu/addons/{menuItem}', [MenuController::class, 'updateAddOn'])->name('menu.addons.update');
    Route::delete('/menu/addons/{menuItem}', [MenuController::class, 'destroyAddOn'])->name('menu.addons.destroy');

    Route::post('/menu/packages', [MenuController::class, 'storePackage'])->name('menu.packages.store');
    Route::get('/menu/packages/{package}/edit', [MenuController::class, 'editPackage'])->name('menu.packages.edit');
    Route::put('/menu/packages/{package}', [MenuController::class, 'updatePackage'])->name('menu.packages.update');
    Route::delete('/menu/packages/{package}', [MenuController::class, 'destroyPackage'])->name('menu.packages.destroy');

    // Booking actions
    Route::post('/bookings/{booking}/accept', [BookingController::class, 'accept'])->name('bookings.accept');
    Route::post('/bookings/{booking}/decline', [BookingController::class, 'decline'])->name('bookings.decline');
    Route::post('/bookings/{booking}/complete', [BookingController::class, 'complete'])->name('bookings.complete');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{recipient}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{recipient}', [MessageController::class, 'store'])->name('messages.store');
});

// Admin routes (require auth + admin role)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminDashboardController::class, 'users'])->name('admin.users');
    Route::get('/admin/bookings', [AdminDashboardController::class, 'bookings'])->name('admin.bookings');
    Route::post('/admin/caterers/{user}/approve', [AdminDashboardController::class, 'approve'])->name('admin.caterer.approve');
    Route::post('/admin/caterers/{user}/reject', [AdminDashboardController::class, 'reject'])->name('admin.caterer.reject');
    Route::get('/admin/featured-caterers', [AdminDashboardController::class, 'featuredCaterers'])->name('admin.featured-caterers.index');
    Route::post('/admin/featured-caterers/{caterer}/toggle', [AdminDashboardController::class, 'toggleFeatured'])->name('admin.featured-caterers.toggle');
    Route::get('/admin/reports', [AdminDashboardController::class, 'reports'])->name('admin.reports');
});
