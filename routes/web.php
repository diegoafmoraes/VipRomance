<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\PhotoController;

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

    // Pg inicial
    Route::get('/home', [FeedController::class, 'index'])->name('home');

    // Trata Rotas do MyProfile
    Route::get('/meu-perfil', [MyProfileController::class, 'edit'])->name('myprofile.edit');
    Route::put('/meu-perfil', [MyProfileController::class, 'update'])->name('myprofile.update');
    Route::get('/meu-perfil/config', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/meu-perfil/config', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/meu-perfil/config', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Rotas de localizacao das fotos (OFICIAL)
    Route::get('/meu-perfil/fotos', [PhotoController::class, 'index'])->name('myprofile.photos');
    Route::post('/meu-perfil/fotos', [PhotoController::class, 'store'])->name('myprofile.photos.store');
    Route::put('/meu-perfil/fotos/{photo}/primary', [PhotoController::class, 'setPrimary'])->name('myprofile.photos.primary');
    Route::delete('/meu-perfil/fotos/{photo}', [PhotoController::class, 'destroy'])->name('myprofile.photos.destroy');

    // perfil público
    Route::get('/u/{username}', [PublicProfileController::class, 'show'])->name('profile.public');

    // CTA do perfil: começar conversa (cria ou reaproveita)
    Route::post('/u/{username}/start-chat', [ConversationController::class, 'start'])->name('chat.start');

    // Rotas das páginas de conversa de dentro do perfil
    Route::get('/conversas', [ConversationController::class, 'index'])
        ->name('chat.index');

    // Rotas da págins geral de conversas
    // Route::get('/minhas-conversas', [ConversationController::class, 'index'])
    //     ->name('chat.index');
    // Route::get('/conversas/{conversation}', [ConversationController::class, 'show'])->name('chat.show');
    // Route::post('/conversas/{conversation}/mensagens', [ConversationController::class, 'send'])
    //     ->name('chat.send');
    // Route::get('/conversas/com/{username}', [ConversationController::class, 'withUser'])
    //     ->name('chat.with');
    Route::get('/conversas/com/{username}', [ConversationController::class, 'withUser'])
        ->name('chat.withUser');
    Route::post('/conversas/com/{username}/mensagens', [ConversationController::class, 'sendToUser'])
        ->name('chat.sendToUser');
});

require __DIR__ . '/auth.php';
