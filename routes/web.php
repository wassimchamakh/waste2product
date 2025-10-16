<?php


use App\Http\Controllers\Backoffice\Event1controller;
use App\Http\Controllers\Backoffice\Project1controller;
use App\Http\Controllers\Frontoffice\DechetController;
use App\Http\Controllers\Frontoffice\DechetFavoriteController;
use App\Http\Controllers\Frontoffice\DechetReviewController;
use App\Http\Controllers\Backoffice\Dechet1Controller;
use App\Http\Controllers\Backoffice\CategoryController;
use App\Http\Controllers\Frontoffice\EventController;
use App\Http\Controllers\Frontoffice\ProjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';



Route::get('/admin', function () {
    return view('BackOffice.home');
})->name('admin');

Route::get('/', function () {
    return view('FrontOffice.home');
})->middleware('guest')->name('homee');

Route::get('/home', function () {
    return view('FrontOffice.pages.homestats');
})->middleware(['auth', 'verified'])->name('home');

/*
Route::get('/login', function () {
    return view('FrontOffice.auth.login');
})->name('login');

Route::get('/register', function () {
    return view('FrontOffice.auth.register');
})->name('register');*/


Route::middleware('auth')->group(function () {
Route::prefix('dechets')->name('dechets.')->group(function () {
    Route::get('/', [DechetController::class, 'index'])->name('index');
    Route::get('/mesdechets', [DechetController::class, 'myDechets'])->name('my');
    Route::get('/favoris', [DechetFavoriteController::class, 'index'])->name('favorites');
    Route::get('/create', [DechetController::class, 'create'])->name('create');
    Route::post('/', [DechetController::class, 'store'])->name('store');
    Route::get('/{id}', [DechetController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [DechetController::class, 'edit'])->name('edit');
    Route::put('/{id}', [DechetController::class, 'update'])->name('update');
    Route::delete('/{id}', [DechetController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/reserve', [DechetController::class, 'reserve'])->name('reserve');

    // Favorites
    Route::post('/{id}/favorite', [DechetFavoriteController::class, 'toggle'])->name('favorite.toggle');

    // Reviews
    Route::post('/{id}/reviews', [DechetReviewController::class, 'store'])->name('reviews.store');
    Route::put('/{dechetId}/reviews/{reviewId}', [DechetReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/{dechetId}/reviews/{reviewId}', [DechetReviewController::class, 'destroy'])->name('reviews.destroy');
});

Route::group(['prefix' => 'projects'], function() {
    Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/mesprojets', [ProjectController::class, 'myProjects'])->name('projects.my');
    Route::get('/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::post('/{project}/favorite', [ProjectController::class, 'toggleFavorite'])->name('projects.favorite');

    // Route pour enregistrer un commentaire
    Route::post('/{project}/comment', [\App\Http\Controllers\FrontOffice\CommentController::class, 'store'])->name('comments.store');

    // Route pour publier un projet
    Route::post('/{project}/publish', [ProjectController::class, 'publish'])->name('projects.publish');

});

Route::prefix('events')->name('Events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/myEvents', [EventController::class, 'myEvents'])->name('mes-Events');
    Route::get('/create', [EventController::class, 'create'])->name('create');
    Route::post('/', [EventController::class, 'store'])->name('store');
    Route::get('/{id}', [EventController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [EventController::class, 'edit'])->name('edit');
    Route::put('/{id}', [EventController::class, 'update'])->name('update');
    Route::delete('/{id}', [EventController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/register', [EventController::class, 'register'])->name('register');
    Route::delete('/{id}/unregister', [EventController::class, 'unregister'])->name('unregister');
    Route::get('/{id}/participants', [EventController::class, 'participants'])->name('participants');
});
                            // PARTIE BACK OFFICE ADMIIIINN //
Route::prefix('admin')->name('admin.')->group(function () {
    
    Route::prefix('/dechets')->name('dechets.')->group(function () {
        Route::get('/', [Dechet1Controller::class, 'index'])->name('index');
        Route::get('/create', [Dechet1Controller::class, 'create'])->name('create');
        Route::post('/', [Dechet1Controller::class, 'store'])->name('store');
        Route::get('/{dechet}/edit', [Dechet1Controller::class, 'edit'])->name('edit');
        Route::put('/{dechet}', [Dechet1Controller::class, 'update'])->name('update');
        Route::delete('/{dechet}', [Dechet1Controller::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/projects')->name('projects.')->group(function () {
        Route::get('/', [Project1controller::class, 'index'])->name('index');
        Route::get('/create', [Project1controller::class, 'create'])->name('create');
        Route::post('/', [Project1controller::class, 'store'])->name('store');
        Route::get('/{project}/edit', [Project1controller::class, 'edit'])->name('edit');
        Route::put('/{project}', [Project1controller::class, 'update'])->name('update');
        Route::delete('/{project}', [Project1controller::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/events')->name('events.')->group(function () {
        Route::get('/', [Event1controller::class, 'index'])->name('index');
        Route::get('/create', [Event1controller::class, 'create'])->name('create');
        Route::post('/', [Event1controller::class, 'store'])->name('store');
        Route::get('/{event}/edit', [Event1controller::class, 'edit'])->name('edit');
        Route::put('/{event}', [Event1controller::class, 'update'])->name('update');
        Route::delete('/{event}', [Event1controller::class, 'destroy'])->name('destroy');
        Route::get('/{event}/participants', [Event1controller::class, 'participants'])->name('participants');
        Route::delete('/{event}/participants/{participant}', [Event1controller::class, 'removeParticipant'])->name('removeParticipant');
    });
});     
}); 