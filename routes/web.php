<?php


use App\Http\Controllers\Backoffice\Event1controller;
use App\Http\Controllers\Backoffice\Project1controller;
use App\Http\Controllers\Backoffice\UserController;
use App\Http\Controllers\Frontoffice\DechetController;
use App\Http\Controllers\Frontoffice\DechetFavoriteController;
use App\Http\Controllers\Frontoffice\DechetReviewController;
use App\Http\Controllers\Backoffice\Dechet1Controller;
use App\Http\Controllers\Backoffice\CategoryController;
use App\Http\Controllers\Backoffice\Tutorial1Controller;
use App\Http\Controllers\Frontoffice\EventController;
use App\Http\Controllers\FrontOffice\NotificationController;
use App\Http\Controllers\Frontoffice\PaymentController;
use App\Http\Controllers\Frontoffice\RefundController;
use App\Http\Controllers\Frontoffice\ProjectController;
use App\Http\Controllers\Frontoffice\TutorialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectLikeController;
use App\Http\Controllers\ForumPostController;
use App\Http\Controllers\ForumCommentController;
use App\Http\Controllers\Admin\ForumAdminController;

// Block malware/tracker requests
Route::any('hybridaction/{any}', function() {
    abort(403, 'Access Denied');
})->where('any', '.*');

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==========================================
// FORUM ROUTES (Public + Authenticated)
// ==========================================

// Forum - Public routes (browsing)
Route::prefix('forum')->name('forum.')->group(function () {
    // Public browsing
    Route::get('/', [ForumPostController::class, 'index'])->name('index');
    
    // Authenticated actions
    Route::middleware('auth')->group(function () {
        // Posts (create MUST come before {post} to avoid route conflicts)
        Route::get('/posts/create', [ForumPostController::class, 'create'])->name('posts.create');
        Route::post('/posts', [ForumPostController::class, 'store'])->name('posts.store');
        Route::get('/posts/{post}/edit', [ForumPostController::class, 'edit'])->name('posts.edit');
        Route::put('/posts/{post}', [ForumPostController::class, 'update'])->name('posts.update');
        Route::delete('/posts/{post}', [ForumPostController::class, 'destroy'])->name('posts.destroy');
        Route::post('/posts/{post}/vote', [ForumPostController::class, 'vote'])->name('posts.vote');
        Route::post('/posts/{post}/summarize', [ForumPostController::class, 'summarize'])->name('posts.summarize');
        
        // Comments
        Route::post('/comments', [ForumCommentController::class, 'store'])->name('comments.store');
        Route::put('/comments/{comment}', [ForumCommentController::class, 'update'])->name('comments.update');
        Route::delete('/comments/{comment}', [ForumCommentController::class, 'destroy'])->name('comments.destroy');
        Route::post('/comments/{comment}/vote', [ForumCommentController::class, 'vote'])->name('comments.vote');
        Route::post('/comments/{comment}/mark-best-answer', [ForumCommentController::class, 'markAsBestAnswer'])->name('comments.markBestAnswer');
    });
    
    // Public post viewing (after auth routes to avoid conflicts)
    Route::get('/posts/{post}', [ForumPostController::class, 'show'])->name('posts.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\Backoffice\DashboardController;

Route::get('/admin', [DashboardController::class, 'index'])->name('admin');

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
    Route::post('/predict-category', [DechetController::class, 'predictCategory'])->name('predict-category');
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
    // Route pour noter un projet
    Route::post('/{project}/rate', [ProjectController::class, 'rate'])->name('projects.rate');
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
    Route::post('/{project}/comment', [\App\Http\Controllers\Frontoffice\CommentController::class, 'store'])->name('comments.store');

    // Route pour supprimer un commentaire
    Route::delete('/comment/{comment}', [\App\Http\Controllers\Frontoffice\CommentController::class, 'destroy'])->name('comments.destroy');

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
    
    // Feedback route
    Route::post('/{event}/feedback', [EventController::class, 'submitFeedback'])
        ->name('feedback.submit');
    
    // Sentiment Analysis routes
    Route::post('/{event}/analyze-sentiment', [EventController::class, 'analyzeSentiment'])
        ->name('sentiment.analyze');
    Route::get('/{event}/sentiment-results', [EventController::class, 'getSentimentResults'])
        ->name('sentiment.results');
    
    // Payment routes
    Route::get('/{event}/payment/{participant}', [PaymentController::class, 'showPaymentPage'])
        ->name('payment');
    Route::get('/{event}/payment/{participant}/success', [PaymentController::class, 'success'])
        ->name('payment.success');
    Route::get('/{event}/payment/{participant}/failure', [PaymentController::class, 'failure'])
        ->name('payment.failure');
    Route::get('/{event}/payment/{participant}/cancel', [PaymentController::class, 'cancel'])
        ->name('payment.cancel');
    
    // Refund routes
    Route::get('/{event}/refund/{participant}', [RefundController::class, 'showRefundForm'])
        ->name('refund.form');
    Route::post('/{event}/refund/{participant}', [RefundController::class, 'requestRefund'])
        ->name('refund.request');
    Route::get('/{event}/refund-requests', [RefundController::class, 'listRefundRequests'])
        ->name('refund.list');
    Route::post('/{event}/refund/{refundRequest}/approve', [RefundController::class, 'approveRefund'])
        ->name('refund.approve');
    Route::post('/{event}/refund/{refundRequest}/reject', [RefundController::class, 'rejectRefund'])
        ->name('refund.reject');
    
    // Certificate routes
    Route::get('/{event}/certificate/{participant}', [EventController::class, 'viewCertificate'])
        ->name('certificate.view');
    Route::post('/{event}/certificates/send', [EventController::class, 'sendCertificates'])
        ->name('certificates.send');
    
    // Ticket routes
    Route::get('/{event}/ticket/{participant}', [EventController::class, 'viewTicket'])
        ->name('ticket.view');
    Route::get('/{event}/ticket/verify/{ticket}', [EventController::class, 'verifyTicket'])
        ->name('ticket.verify');
    Route::post('/{event}/confirm-participation', [EventController::class, 'confirmParticipation'])
        ->name('confirm.participation');
    Route::get('/{event}/qr-scanner', [EventController::class, 'qrScanner'])
        ->name('qr.scanner');
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

    Route::get('/profile', [ProfileController::class, 'editadmin'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'updateadmin'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
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
        Route::post('/ai-suggest', [CategoryController::class, 'aiSuggest'])->name('ai-suggest');
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

    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('toggle-admin');
        Route::patch('/{id}/toggle-verification', [UserController::class, 'toggleVerification'])->name('toggle-verification');
        Route::post('/bulk-action', [UserController::class, 'bulkAction'])->name('bulk-action');
    });

    // Notifications
    Route::prefix('/notifications')->name('notifications.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Backoffice\NotificationController::class, 'index'])->name('index');
        Route::post('/mark-all-read', [\App\Http\Controllers\Backoffice\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::patch('/{id}/mark-read', [\App\Http\Controllers\Backoffice\NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::delete('/{id}', [\App\Http\Controllers\Backoffice\NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/read/all', [\App\Http\Controllers\Backoffice\NotificationController::class, 'deleteAllRead'])->name('delete-all-read');
    });
     

    


    // Forum Admin Routes
    Route::prefix('/forum')->name('forum.')->group(function () {
        Route::get('/', [ForumAdminController::class, 'index'])->name('index');
        Route::get('/posts', [ForumAdminController::class, 'posts'])->name('posts');
        Route::get('/comments', [ForumAdminController::class, 'comments'])->name('comments');
        Route::get('/users', [ForumAdminController::class, 'users'])->name('users');
        
        // Post moderation
        Route::delete('/posts/{post}', [ForumAdminController::class, 'destroyPost'])->name('posts.destroy');
        Route::post('/posts/{post}/pin', [ForumAdminController::class, 'togglePin'])->name('posts.pin');
        Route::post('/posts/{post}/lock', [ForumAdminController::class, 'toggleLock'])->name('posts.lock');
        Route::post('/posts/{post}/status', [ForumAdminController::class, 'changeStatus'])->name('posts.status');
        
        // Comment moderation
        Route::delete('/comments/{comment}', [ForumAdminController::class, 'destroyComment'])->name('comments.destroy');
        
        // Spam management
        Route::post('/spam/toggle', [ForumAdminController::class, 'toggleSpam'])->name('spam.toggle');
    });
});     
// FrontOffice Notifications (for regular users)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::patch('/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/read/all', [NotificationController::class, 'deleteAllRead'])->name('delete-all-read');
    });
}); // Close auth middleware group

Route::post('/projects/{project}/toggle-like', [ProjectLikeController::class, 'toggle'])->middleware('auth')->name('projects.toggleLike');

// Route pour effacer toutes les notifications du user connecté
Route::post('/notifications/clear', function() {
    Auth::user()->notifications()->delete();
    return back();
})->name('notifications.clear')->middleware('auth');

// Route pour marquer toutes les notifications comme lues
Route::post('/notifications/markAsRead', function() {
    Auth::user()->unreadNotifications->markAsRead();
    return response()->json(['status' => 'ok']);
})->name('notifications.markAsRead')->middleware('auth');

// Route pour marquer une notification comme lue individuellement
Route::post('/notifications/read/{id}', function($id) {
    $notif = Auth::user()->notifications()->find($id);
    if($notif) $notif->markAsRead();
    return response()->json(['status' => 'ok']);
})->name('notifications.read')->middleware('auth');


// Stripe Webhook - No authentication required (Stripe sends requests directly)
Route::post('/webhooks/stripe', [PaymentController::class, 'handleWebhook'])->name('webhooks.stripe');