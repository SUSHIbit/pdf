<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ToyibPayCallbackController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// CRITICAL FIX: Add endpoint to set pending upload session from welcome page
Route::post('/set-pending-upload', function (Request $request) {
    if ($request->has('pending') && $request->pending) {
        session(['pending_upload' => true]);
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false]);
});

// Landing page upload route for guests - Set session flag for pending upload
Route::post('/landing-upload', function (Request $request) {
    // Store upload intention in session
    $request->session()->put('pending_upload', true);
    
    return redirect()->route('login')->with('info', 'Please log in to upload documents');
})->name('landing.upload');

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Documents
    Route::get('/upload', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::post('/upload', [DocumentController::class, 'store'])->name('documents.store')->middleware('throttle:10,60');
    
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/format', [DocumentController::class, 'format'])->name('documents.format');
    Route::get('/documents/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');
    Route::post('/documents/{document}/process', [DocumentController::class, 'process'])->name('documents.process');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::patch('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    
    // Folders
    Route::get('/folders', [FolderController::class, 'index'])->name('folders.index');
    Route::post('/folders', [FolderController::class, 'store'])->name('folders.store');
    Route::get('/folders/{folder}', [FolderController::class, 'show'])->name('folders.show');
    Route::get('/folders/{folder}/edit', [FolderController::class, 'edit'])->name('folders.edit');
    Route::patch('/folders/{folder}', [FolderController::class, 'update'])->name('folders.update');
    Route::delete('/folders/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');
    Route::post('/move-document', [FolderController::class, 'moveDocument'])->name('folders.move-document');
    
    // ToyibPay Payments
    Route::get('/credits', [PaymentController::class, 'packages'])->name('payment.packages');
    Route::post('/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ToyibPay Callback (outside auth middleware)
Route::post('/toyyibpay/callback', [ToyibPayCallbackController::class, 'handle'])
    ->name('toyyibpay.callback');

require __DIR__.'/auth.php';