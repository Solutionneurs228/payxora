<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\KycController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TransactionAdminController;
use App\Http\Controllers\Admin\DisputeAdminController;

/*
|--------------------------------------------------------------------------
| PayXora Togo — Routes Principales
|--------------------------------------------------------------------------
*/

// === PAGE D'ACCUEIL ===
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/comment-ca-marche', [HomeController::class, 'howItWorks'])->name('how-it-works');
Route::get('/tarifs', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/a-propos', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// === AUTHENTIFICATION ===
Route::middleware('guest')->group(function () {
    Route::get('/inscription', [RegisterController::class, 'show'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/connexion', [LoginController::class, 'show'])->name('login');
    Route::post('/connexion', [LoginController::class, 'store'])->name('login.store');
    Route::get('/mot-de-passe-oublie', [LoginController::class, 'forgotPassword'])->name('password.request');
    Route::post('/mot-de-passe-oublie', [LoginController::class, 'sendResetLink'])->name('password.email');
});

Route::post('/deconnexion', [LogoutController::class, 'store'])->name('logout')->middleware('auth');

// === KYC (Know Your Customer) ===
Route::middleware('auth')->group(function () {
    Route::get('/kyc', [KycController::class, 'show'])->name('kyc.show');
    Route::post('/kyc', [KycController::class, 'store'])->name('kyc.store');
    Route::get('/kyc/verification', [KycController::class, 'verification'])->name('kyc.verification');
});

// === TABLEAU DE BORD ===
Route::middleware(['auth', 'kyc'])->group(function () {
    Route::get('/tableau-de-bord', [DashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/mot-de-passe', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/nouvelle', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/payer', [TransactionController::class, 'pay'])->name('transactions.pay');
    Route::post('/transactions/{transaction}/confirmer-reception', [TransactionController::class, 'confirmDelivery'])->name('transactions.confirm');
    Route::post('/transactions/{transaction}/annuler', [TransactionController::class, 'cancel'])->name('transactions.cancel');

    // Paiements
    Route::get('/paiement/{transaction}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/paiement/{transaction}/mobile-money', [PaymentController::class, 'processMobileMoney'])->name('payment.mobile-money');
    Route::post('/paiement/{transaction}/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('/paiement/{transaction}/succes', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/paiement/{transaction}/echec', [PaymentController::class, 'failure'])->name('payment.failure');

    // Litiges
    Route::get('/litiges', [DisputeController::class, 'index'])->name('disputes.index');
    Route::get('/litiges/nouveau/{transaction}', [DisputeController::class, 'create'])->name('disputes.create');
    Route::post('/litiges', [DisputeController::class, 'store'])->name('disputes.store');
    Route::get('/litiges/{dispute}', [DisputeController::class, 'show'])->name('disputes.show');
    Route::post('/litiges/{dispute}/repondre', [DisputeController::class, 'reply'])->name('disputes.reply');
});

// === ADMINISTRATION ===
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Gestion utilisateurs
    Route::get('/utilisateurs', [UserController::class, 'index'])->name('users.index');
    Route::get('/utilisateurs/{user}', [UserController::class, 'show'])->name('users.show');
    Route::post('/utilisateurs/{user}/valider-kyc', [UserController::class, 'validateKyc'])->name('users.validate-kyc');
    Route::post('/utilisateurs/{user}/suspendre', [UserController::class, 'suspend'])->name('users.suspend');

    // Gestion transactions
    Route::get('/transactions', [TransactionAdminController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionAdminController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/liberer-fonds', [TransactionAdminController::class, 'releaseFunds'])->name('transactions.release');
    Route::post('/transactions/{transaction}/rembourser', [TransactionAdminController::class, 'refund'])->name('transactions.refund');

    // Gestion litiges
    Route::get('/litiges', [DisputeAdminController::class, 'index'])->name('disputes.index');
    Route::get('/litiges/{dispute}', [DisputeAdminController::class, 'show'])->name('disputes.show');
    Route::post('/litiges/{dispute}/arbitrer', [DisputeAdminController::class, 'arbitrate'])->name('disputes.arbitrate');
    Route::post('/litiges/{dispute}/fermer', [DisputeAdminController::class, 'close'])->name('disputes.close');
});

// === API (pour AJAX) ===
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/notifications', [DashboardController::class, 'notifications'])->name('api.notifications');
    Route::post('/notifications/{id}/lue', [DashboardController::class, 'markNotificationRead'])->name('api.notifications.read');
});
