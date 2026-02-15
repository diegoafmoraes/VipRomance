<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MyPhotosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
}); */

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('home')   // ou dashboard
        : redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [FeedController::class, 'index'])->name('home');

    // perfil público
    Route::get('/u/{username}', [PublicProfileController::class, 'show'])->name('profile.public');

    // CTA do perfil: começar conversa (cria ou reaproveita)
    Route::post('/u/{username}/start-chat', [ConversationController::class, 'start'])->name('chat.start');

    // Trata Rotas do MyProfile
    Route::get('/meu-perfil', [MyProfileController::class, 'edit'])->name('myprofile.edit');
    Route::put('/meu-perfil', [MyProfileController::class, 'update'])->name('myprofile.update');

    // Rotas de tratamento do upload e manuseio de fotos
    Route::get('/minhas-fotos', [MyPhotosController::class, 'index'])->name('myphotos.index');
    Route::post('/minhas-fotos', [MyPhotosController::class, 'store'])->name('myphotos.store');
    Route::delete('/minhas-fotos/{photo}', [MyPhotosController::class, 'destroy'])->name('myphotos.destroy');
    Route::post('/minhas-fotos/{photo}/primary', [MyPhotosController::class, 'makePrimary'])->name('myphotos.primary');
});
