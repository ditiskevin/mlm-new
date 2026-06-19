<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AudienceController;
use App\Http\Controllers\BabysitterController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FatherController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RevealController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/zwangerschap', [CalendarController::class, 'index'])->name('calendar');
Route::get('/community', [CommunityController::class, 'index'])->name('community');
Route::get('/checklists', [ChecklistController::class, 'index'])->name('checklists');
Route::get('/hoera-zwanger', [RevealController::class, 'index'])->name('reveal');
Route::get('/aanstaande-vader', [FatherController::class, 'index'])->name('father');
Route::get('/voor', [AudienceController::class, 'index'])->name('audiences.index');
Route::get('/voor/{audience}', [AudienceController::class, 'show'])->name('audiences.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::middleware('auth')->group(function () {
    Route::get('/blog/schrijven', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
});
Route::get('/blog/{article}', [BlogController::class, 'show'])->name('blog.show');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:6,1')->name('contact.store');

// Marktplaats — create routes registered before {listing} so they win the match
Route::get('/marktplaats', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::middleware('auth')->group(function () {
    Route::get('/marktplaats/plaatsen', [MarketplaceController::class, 'create'])->name('marketplace.create');
    Route::post('/marktplaats', [MarketplaceController::class, 'store'])->name('marketplace.store');
    Route::delete('/marktplaats/{listing}', [MarketplaceController::class, 'destroy'])->name('marketplace.destroy');
});
Route::get('/marktplaats/{listing}', [MarketplaceController::class, 'show'])->name('marketplace.show');

// Oppas
Route::get('/oppas', [BabysitterController::class, 'index'])->name('babysitters.index');
Route::middleware('auth')->group(function () {
    Route::get('/oppas/aanmelden', [BabysitterController::class, 'create'])->name('babysitters.create');
    Route::post('/oppas', [BabysitterController::class, 'store'])->name('babysitters.store');
    Route::delete('/oppas/{babysitter}', [BabysitterController::class, 'destroy'])->name('babysitters.destroy');
});
Route::get('/oppas/{babysitter}', [BabysitterController::class, 'show'])->name('babysitters.show');

// Forum
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::middleware('auth')->group(function () {
    Route::get('/forum/nieuw', [ForumController::class, 'create'])->name('forum.create');
    Route::post('/forum/onderwerpen', [ForumController::class, 'storeTopic'])->name('forum.topics.store');
    Route::post('/forum/onderwerp/{topic}/reacties', [ForumController::class, 'storeReply'])->name('forum.replies.store');
    Route::delete('/forum/onderwerp/{topic}', [ForumController::class, 'destroyTopic'])->name('forum.topics.destroy');
});
Route::get('/forum/categorie/{category}', [ForumController::class, 'category'])->name('forum.category');
Route::get('/forum/onderwerp/{topic}', [ForumController::class, 'topic'])->name('forum.topic');

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Interactive actions (members only)
    Route::post('/checklist-items/{item}/toggle', [ChecklistController::class, 'toggle'])->name('checklist.toggle');
    Route::post('/baby-names/{name}/favourite', [RevealController::class, 'toggleFavourite'])->name('names.favourite');
    Route::post('/community/posts', [CommunityController::class, 'storePost'])->name('community.posts.store');
    Route::post('/community/posts/{post}/comments', [CommunityController::class, 'storeComment'])->name('community.posts.comments.store');
    Route::post('/community/posts/{post}/like', [CommunityController::class, 'toggleLike'])->name('community.posts.like');
    Route::post('/community/groups/{group}/follow', [CommunityController::class, 'toggleFollow'])->name('community.groups.follow');

    // Eigen community-groep aanmaken / beheren
    Route::get('/community/groepen/aanmaken', [CommunityController::class, 'createGroup'])->name('community.groups.create');
    Route::post('/community/groepen', [CommunityController::class, 'storeGroup'])->name('community.groups.store');
    Route::delete('/community/groepen/{group}', [CommunityController::class, 'destroyGroup'])->name('community.groups.destroy');

    // Melden (rapporteren) van inhoud of profielen
    Route::post('/meldingen', [\App\Http\Controllers\ReportController::class, 'store'])
        ->middleware('throttle:20,1')->name('reports.store');

    // Privéberichten (chat)
    Route::get('/berichten', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::post('/berichten/start/{user}', [\App\Http\Controllers\MessageController::class, 'startWith'])->name('messages.start');
    Route::get('/berichten/{conversation}', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.show');
    Route::post('/berichten/{conversation}', [\App\Http\Controllers\MessageController::class, 'store'])
        ->middleware('throttle:60,1')->name('messages.store');
});

// Admin moderation area
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::delete('/posts/{post}', [AdminController::class, 'destroyPost'])->name('posts.destroy');
    Route::delete('/comments/{comment}', [AdminController::class, 'destroyComment'])->name('comments.destroy');
    Route::delete('/forum-topics/{topic}', [AdminController::class, 'destroyTopic'])->name('topics.destroy');
    Route::delete('/forum-replies/{reply}', [AdminController::class, 'destroyReply'])->name('replies.destroy');
    Route::delete('/listings/{listing}', [AdminController::class, 'destroyListing'])->name('listings.destroy');
    Route::delete('/babysitters/{babysitter}', [AdminController::class, 'destroyBabysitter'])->name('babysitters.destroy');
    Route::delete('/articles/{article}', [AdminController::class, 'destroyArticle'])->name('articles.destroy');
    Route::get('/blog-inzendingen', [AdminController::class, 'pendingArticles'])->name('articles.pending');
    Route::patch('/articles/{article}/approve', [AdminController::class, 'approveArticle'])->name('articles.approve');

    // Volledig blogbeheer (artikelen aanmaken / bewerken)
    Route::get('/blog', [AdminController::class, 'articlesIndex'])->name('articles.index');
    Route::get('/blog/nieuw', [AdminController::class, 'createArticle'])->name('articles.create');
    Route::post('/blog', [AdminController::class, 'storeArticle'])->name('articles.store');
    Route::get('/blog/{article}/bewerken', [AdminController::class, 'editArticle'])->name('articles.edit');
    Route::patch('/blog/{article}', [AdminController::class, 'updateArticle'])->name('articles.update');

    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::patch('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggle-admin');

    // Meldingen (reports)
    Route::get('/meldingen', [AdminController::class, 'reports'])->name('reports.index');
    Route::patch('/meldingen/{report}/status', [AdminController::class, 'updateReportStatus'])->name('reports.status');
    Route::delete('/meldingen/{report}', [AdminController::class, 'destroyReport'])->name('reports.destroy');
    Route::delete('/meldingen/{report}/inhoud', [AdminController::class, 'destroyReportedContent'])->name('reports.content.destroy');

    // Contactberichten
    Route::get('/contact', [AdminController::class, 'contact'])->name('contact.index');
    Route::patch('/contact/{message}/handled', [AdminController::class, 'toggleContactHandled'])->name('contact.toggle');
    Route::delete('/contact/{message}', [AdminController::class, 'destroyContact'])->name('contact.destroy');

    // Doelgroepen (audiences) beheer
    Route::get('/doelgroepen', [AdminController::class, 'audiences'])->name('audiences.index');
    Route::get('/doelgroepen/nieuw', [AdminController::class, 'createAudience'])->name('audiences.create');
    Route::post('/doelgroepen', [AdminController::class, 'storeAudience'])->name('audiences.store');
    Route::get('/doelgroepen/{audience}/bewerken', [AdminController::class, 'editAudience'])->name('audiences.edit');
    Route::patch('/doelgroepen/{audience}', [AdminController::class, 'updateAudience'])->name('audiences.update');
    Route::delete('/doelgroepen/{audience}', [AdminController::class, 'destroyAudience'])->name('audiences.destroy');
});

require __DIR__.'/auth.php';
