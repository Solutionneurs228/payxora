<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DisputeController as AdminDisputeController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WebhookController;
use App\Http\Middleware\RateLimitPayment;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Publiques
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/comment-ca-marche', [HomeController::class, 'howItWorks'])->name('how-it-works');
Route::get('/tarifs', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/a-propos', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

/*
|--------------------------------------------------------------------------
| Authentification (Guest)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/inscription', [RegisterController::class, 'show'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'store']);
    Route::get('/connexion', [LoginController::class, 'show'])->name('login');
    Route::post('/connexion', [LoginController::class, 'store']);
    Route::get('/mot-de-passe-oublie', [LoginController::class, 'showForgot'])->name('password.request');
    Route::post('/mot-de-passe-oublie', [LoginController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reinitialiser-mot-de-passe/{token}', [LoginController::class, 'showReset'])->name('password.reset');
    Route::post('/reinitialiser-mot-de-passe', [LoginController::class, 'reset'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authentification (Auth)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout');
    Route::get('/verification-email', [RegisterController::class, 'showVerify'])->name('verification.notice');
    Route::get('/verification-email/{id}/{hash}', [RegisterController::class, 'verify'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/verification-email/renvoyer', [RegisterController::class, 'resend'])->middleware('throttle:6,1')->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| KYC (Auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/kyc', [ProfileController::class, 'showKyc'])->name('kyc');
    Route::post('/kyc', [ProfileController::class, 'storeKyc'])->name('kyc.store');
    Route::get('/kyc/verification', [ProfileController::class, 'showKycVerification'])->name('kyc.verification');
});

/*
|--------------------------------------------------------------------------
| Espace Utilisateur (Auth + KYC)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'kyc'])->group(function () {
    // Dashboard
    Route::get('/tableau-de-bord', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications', [DashboardController::class, 'notifications'])->name('notifications');

    // Profil
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profil/mot-de-passe', [ProfileController::class, 'editPassword'])->name('profile.password');
    Route::patch('/profil/mot-de-passe', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/creer', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{transaction}/modifier', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::patch('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    // Workflow Escrow
    Route::post('/transactions/{transaction}/publier', [TransactionController::class, 'publish'])->name('transactions.publish');
    Route::post('/transactions/{transaction}/expedier', [TransactionController::class, 'ship'])->name('transactions.ship');
    Route::post('/transactions/{transaction}/livrer', [TransactionController::class, 'deliver'])->name('transactions.deliver');
    Route::post('/transactions/{transaction}/confirmer', [TransactionController::class, 'complete'])->name('transactions.complete');
    Route::post('/transactions/{transaction}/annuler', [TransactionController::class, 'cancel'])->name('transactions.cancel');

    // Litiges
    Route::post('/transactions/{transaction}/litige', [DisputeController::class, 'store'])->name('disputes.store');
    Route::get('/litiges', [DisputeController::class, 'index'])->name('disputes.index');
    Route::get('/litiges/{dispute}', [DisputeController::class, 'show'])->name('disputes.show');
    Route::post('/litiges/{dispute}/reponse', [DisputeController::class, 'reply'])->name('disputes.reply');

    // Paiements (avec rate limiting)
    Route::middleware([RateLimitPayment::class . ':ip'])->group(function () {
        Route::get('/transactions/{transaction}/paiement', [PaymentController::class, 'show'])->name('payment.show');
        Route::post('/transactions/{transaction}/paiement/mobile-money', [PaymentController::class, 'processMobileMoney'])
            ->middleware(RateLimitPayment::class . ':payment')
            ->name('payment.mobile-money');
        Route::post('/transactions/{transaction}/paiement/carte', [PaymentController::class, 'processCard'])
            ->middleware(RateLimitPayment::class . ':payment')
            ->name('payment.card');
    });

    Route::get('/paiement/succes/{transaction}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/paiement/echec/{transaction}', [PaymentController::class, 'failure'])->name('payment.failure');
});

/*
|--------------------------------------------------------------------------
| Webhooks (Public — rate limited)
|--------------------------------------------------------------------------
*/

Route::middleware([RateLimitPayment::class . ':webhook'])->group(function () {
    Route::post('/webhook/payment/{provider}', [WebhookController::class, 'handlePayment'])
        ->name('payment.callback');
    Route::post('/webhook/stripe', [WebhookController::class, 'handleStripe'])
        ->name('webhook.stripe');
    Route::post('/webhook/flutterwave', [WebhookController::class, 'handleFlutterwave'])
        ->name('webhook.flutterwave');
});

/*
|--------------------------------------------------------------------------
| Callbacks de redirection (Public)
|--------------------------------------------------------------------------
*/

Route::get('/paiement/callback/{provider}/{transaction}', [PaymentController::class, 'callback'])
    ->name('payment.redirect.callback');

/*
|--------------------------------------------------------------------------
| Administration (Auth + Admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Utilisateurs
    Route::get('/utilisateurs', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/utilisateurs/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/utilisateurs/{user}/valider-kyc', [AdminUserController::class, 'validateKyc'])->name('users.validate-kyc');
    Route::post('/utilisateurs/{user}/suspendre', [AdminUserController::class, 'suspend'])->name('users.suspend');

    // Transactions
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [AdminTransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/liberer', [AdminTransactionController::class, 'release'])->name('transactions.release');
    Route::post('/transactions/{transaction}/rembourser', [AdminTransactionController::class, 'refund'])->name('transactions.refund');

    // Litiges
    Route::get('/litiges', [AdminDisputeController::class, 'index'])->name('disputes.index');
    Route::get('/litiges/{dispute}', [AdminDisputeController::class, 'show'])->name('disputes.show');
    Route::post('/litiges/{dispute}/arbitrer', [AdminDisputeController::class, 'arbitrate'])->name('disputes.arbitrate');
    Route::post('/litiges/{dispute}/fermer', [AdminDisputeController::class, 'close'])->name('disputes.close');
});
