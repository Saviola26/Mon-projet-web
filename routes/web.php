<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SeminaireController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FichierController; 
use App\Http\Controllers\NotificationController; 

Route::get('/seminaires', [SeminaireController::class, 'index'])->name('seminaires.index');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/seminaires', [SeminaireController::class, 'index'])->name('seminaires.index');
Route::get('/seminaires/create', [SeminaireController::class, 'create'])->name('seminaires.create')->middleware('auth');
Route::post('/seminaires', [SeminaireController::class, 'store'])->name('seminaires.store')->middleware('auth');
Route::get('/seminaires/{seminaire}', [SeminaireController::class, 'show'])->name('seminaires.show');
Route::get('/seminaires/{seminaire}/edit', [SeminaireController::class, 'edit'])->name('seminaires.edit')->middleware('auth');
Route::put('/seminaires/{seminaire}', [SeminaireController::class, 'update'])->name('seminaires.update')->middleware('auth');
Route::delete('/seminaires/{seminaire}', [SeminaireController::class, 'destroy'])->name('seminaires.destroy')->middleware('auth');

Route::post('/seminaires/{id}/valider', [SeminaireController::class, 'valider'])->name('seminaires.valider')->middleware('auth'); 
Route::post('/seminaires/{id}/resume', [SeminaireController::class, 'envoyerResume'])->name('seminaires.resume')->middleware('auth');
Route::post('/seminaires/{id}/publier', [SeminaireController::class, 'publier'])->name('seminaires.publier')->middleware('auth'); 
Route::post('/seminaires/{seminaire}/terminer', [SeminaireController::class, 'marquerCommeTermine'])->name('seminaires.terminer');

Route::post('/seminaires/{seminaire}/participer', [SeminaireController::class, 'participer'])->name('seminaires.participer')->middleware('auth');
Route::post('/seminaires/{seminaire}/annuler-participation', [SeminaireController::class, 'annulerParticipation'])->name('seminaires.annuler_participation')->middleware('auth');
Route::get('/mes-inscriptions', [SeminaireController::class, 'mesInscriptions'])->name('mes.inscriptions')->middleware('auth'); // <-- POINTANT VERS SEMINAIRECONTROLLER
Route::get('/historique', [SeminaireController::class, 'historique'])->name('seminaires.historique')->middleware('auth');
Route::get('/mes-demandes', [SeminaireController::class, 'mesDemandes'])->name('seminaires.mesDemandes')->middleware('auth');
Route::get('/seminaires/{seminaire}/upload-presentation', [SeminaireController::class, 'showUploadPresentationForm'])->name('seminaires.show_upload_presentation_form');
Route::post('/seminaires/{seminaire}/upload-presentation', [SeminaireController::class, 'handleUploadPresentation'])->name('seminaires.handle_upload_presentation');


Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('auth');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create')->middleware('auth');
Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('auth');
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('auth');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('auth');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('auth');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('auth');

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index')->middleware('auth');
Route::get('/notifications/send-to-all', [NotificationController::class, 'showSendToAllForm'])->name('notifications.showSendToAllForm')->middleware('auth');
Route::post('/notifications/send-to-all', [NotificationController::class, 'sendToAll'])->name('notifications.sendToAll')->middleware('auth');
Route::get('/notifications/send/{user}', [NotificationController::class, 'showSendToUserForm'])->name('notifications.showSendToUserForm')->middleware('auth');
Route::post('/notifications/send/{user}', [NotificationController::class, 'send'])->name('notifications.send')->middleware('auth');
Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead')->middleware('auth');
Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy')->middleware('auth');
Route::get('/notifications/{notification}/confirm-delete', [NotificationController::class, 'confirmDelete'])->name('notifications.confirmDelete')->middleware('auth');
Route::get('/notifications/unread', [NotificationController::class, 'unreadNotifications'])->name('notifications.unread')->middleware('auth');

require __DIR__.'/auth.php';
