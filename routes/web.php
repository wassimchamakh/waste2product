<?php


use App\Http\Controllers\Backoffice\Event1controller;
use App\Http\Controllers\Backoffice\Project1controller;
use App\Http\Controllers\Frontoffice\DechetController;
use App\Http\Controllers\Frontoffice\DechetFavoriteController;
use App\Http\Controllers\Frontoffice\DechetReviewController;
use App\Http\Controllers\Backoffice\Dechet1Controller;
use App\Http\Controllers\Backoffice\CategoryController;
use App\Http\Controllers\Backoffice\Tutorial1Controller;
use App\Http\Controllers\Frontoffice\EventController;
use App\Http\Controllers\Frontoffice\ProjectController;
use App\Http\Controllers\Frontoffice\TutorialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
    
    // Get participants modal content for AJAX
    Route::get('/{id}/participants-modal-content', [EventController::class, 'getParticipantsModalContent'])
        ->name('participants.modalContent');

     Route::patch('/{event}/participants/{participant}/status', [EventController::class, 'updateParticipantStatus'])
        ->name('participants.updateStatus');
    Route::delete('/{event}/participants/{participant}', [EventController::class, 'deleteParticipant'])
        ->name('participants.delete');
    
    // Bulk operations - using POST to avoid DELETE with body issues
    Route::post('/{event}/participants/bulk-status', [EventController::class, 'bulkUpdateParticipantStatus'])
        ->name('participants.bulkUpdateStatus');
    Route::post('/{event}/participants/bulk-delete', [EventController::class, 'bulkDeleteParticipants'])
        ->name('participants.bulkDelete');
    Route::post('/{event}/participants/send-email', [EventController::class, 'sendBulkEmail'])
        ->name('participants.sendEmail');
});


Route::prefix('tutorials')->name('tutorials.')->group(function () {
    // Public routes
    Route::get('/', [TutorialController::class, 'index'])->name('index');
    Route::get('/create', [TutorialController::class, 'create'])->name('create');
    Route::post('/', [TutorialController::class, 'store'])->name('store');
    Route::get('/{slug}', [TutorialController::class, 'show'])->name('show');
    Route::get('/{slug}/edit', [TutorialController::class, 'edit'])->name('edit');
    Route::put('/{slug}', [TutorialController::class, 'update'])->name('update');
    Route::delete('/{slug}', [TutorialController::class, 'destroy'])->name('destroy');
    
    // Step routes
    Route::get('/{slug}/step/{stepNumber}', [TutorialController::class, 'step'])->name('step');
    Route::post('/steps/complete', [TutorialController::class, 'markStepComplete'])->name('steps.complete');
    
    // Comments routes
    Route::post('/comments', [TutorialController::class, 'storeComment'])->name('comments.store');
    Route::post('/comments/helpful', [TutorialController::class, 'markCommentHelpful'])->name('comments.helpful');
    
    // User interactions
    Route::post('/bookmark', [TutorialController::class, 'toggleBookmark'])->name('bookmark');
    Route::post('/{id}/complete', [TutorialController::class, 'markComplete'])->name('complete');
    
    // Notes routes
    Route::post('/notes/save', [TutorialController::class, 'saveNotes'])->name('notes.save');
    Route::get('/notes/get', [TutorialController::class, 'getNotes'])->name('notes.get');
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

    Route::prefix('/tutorials')->name('tutorials.')->group(function () {
        Route::get('/', [Tutorial1Controller::class, 'index'])->name('index');
        Route::get('/create', [Tutorial1Controller::class, 'create'])->name('create');
        Route::post('/', [Tutorial1Controller::class, 'store'])->name('store');
        Route::get('/{id}', [Tutorial1Controller::class, 'show'])->name('show');
        Route::get('/{id}/edit', [Tutorial1Controller::class, 'edit'])->name('edit');
        Route::put('/{id}', [Tutorial1Controller::class, 'update'])->name('update');
        Route::get('/{id}/steps', [Tutorial1Controller::class, 'steps'])->name('steps');
        Route::get('/{id}/steps/create', [Tutorial1Controller::class, 'createStep'])->name('steps.create');
        Route::post('/{id}/steps', [Tutorial1Controller::class, 'storeStep'])->name('steps.store');
        Route::get('/{tutorialId}/steps/{stepId}/edit', [Tutorial1Controller::class, 'editStep'])->name('steps.edit');
        Route::put('/{tutorialId}/steps/{stepId}', [Tutorial1Controller::class, 'updateStep'])->name('steps.update');
        Route::delete('/{tutorialId}/steps/{stepId}', [Tutorial1Controller::class, 'destroyStep'])->name('steps.destroy');
        Route::get('/{id}/comments', [Tutorial1Controller::class, 'comments'])->name('comments');
        Route::get('/{id}/progress', [Tutorial1Controller::class, 'progress'])->name('progress');
        Route::post('/{id}/status', [Tutorial1Controller::class, 'updateStatus'])->name('status');
        Route::post('/{id}/featured', [Tutorial1Controller::class, 'toggleFeatured'])->name('featured');
        Route::post('/comments/{id}/moderate', [Tutorial1Controller::class, 'moderateComment'])->name('comments.moderate');
        Route::delete('/{id}', [Tutorial1Controller::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [Tutorial1Controller::class, 'bulkAction'])->name('bulk');
    });
});     
}); 