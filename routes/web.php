<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShortUrlController;
use App\Models\ShortUrl;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

/** Invitation accept  ROutes */
Route::get('/invitations/accept/{token}', [InvitationController::class, 'show'])->name('invitations.accept.show');
Route::post('/invitations/accept/{token}', [InvitationController::class, 'store'])->name('invitations.accept.store');


/** Short URL resolve & publicly access */
Route::get('/s/{code}', function (string $code) {
    $shortUrl = ShortUrl::where('short_code', $code)->first();
    if (!$shortUrl) {abort(404);}
    return redirect($shortUrl->original_url);
})->name('short-urls.resolve');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /* Invitation Routes */
    Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations.index');
    Route::get('/invitations/create-client', [InvitationController::class, 'createClient'])->name('invitations.create-client');
    Route::post('/invitations/client', [InvitationController::class, 'storeClient'])->name('invitations.store-client');
    Route::get('/invitations/create-member', [InvitationController::class, 'createMember'])->name('invitations.create-member');
    Route::post('/invitations/member', [InvitationController::class, 'storeMember'])->name('invitations.store-member');

    /* Short URL Routes */
    Route::get('/short-urls', [ShortUrlController::class, 'index'])->name('short-urls.index');
    Route::get('/short-urls/create', [ShortUrlController::class, 'create'])->name('short-urls.create');
    Route::post('/short-urls', [ShortUrlController::class, 'store'])->name('short-urls.store');

    /* Profile Routes */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';