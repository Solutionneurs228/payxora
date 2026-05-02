<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\InitiatePaymentRequest;
use App\Models\Transaction;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Controller pour le flux de paiement.
 *
 * Ce controller utilise PaymentService qui delegue aux providers
 * via PaymentProviderFactory. Il supporte :
 * - Mobile Money (TMoney, Moov Money)
 * - Carte Bancaire (Stripe, Flutterwave, CinetPay... en preparation)
 * - Mode simulation (dev)
 */
class PaymentController extends Controller
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Affiche la page de paiement avec les methodes disponibles.
     */
    public function show(Transaction $transaction): View
    {
        $this->authorize('view', $transaction);

        if ($transaction->buyer_id !== Auth::id()) {
            abort(403, 'Seul l'acheteur designe peut effectuer le paiement.');
        }

        // Recuperer les methodes de paiement disponibles
        $paymentMethods = $this->paymentService->getAvailableMethods();

        // Cartes de test pour le mode dev
        $testCards = [];
        if (app()->environment('local', 'testing')) {
            $testCards = \App\Services\CardPaymentService::getTestCards();
        }

        return view('transactions.payment', compact('transaction', 'paymentMethods', 'testCards'));
    }

    /**
     * Traite un paiement Mobile Money.
     *
     * @deprecated Utiliser process() pour supporter toutes les methodes
     */
    public function processMobileMoney(InitiatePaymentRequest $request, Transaction $transaction): RedirectResponse
    {
        return $this->process($request, $transaction, 'mobile_money');
    }

    /**
     * Traite un paiement par carte bancaire.
     */
    public function processCard(Request $request, Transaction $transaction): RedirectResponse
    {
        $request->validate([
            'card_number' => ['required', 'string', 'min:13', 'max:19'],
            'card_expiry' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'card_cvc'    => ['required', 'string', 'min:3', 'max:4'],
        ]);

        return $this->process($request, $transaction, 'card');
    }

    /**
     * Traite un paiement (methode generique).
     *
     * Cette methode remplace processMobileMoney() et supporte
     * toutes les methodes de paiement via PaymentService.
     */
    private function process(Request $request, Transaction $transaction, string $method): RedirectResponse
    {
        $this->authorize('view', $transaction);

        if ($transaction->buyer_id !== Auth::id()) {
            abort(403);
        }

        // Determiner le sous-provider (tmoney, moov, stripe...)
        $subProvider = $request->input('provider');

        // Pour Mobile Money, le numero est obligatoire
        $phoneNumber = $request->input('phone_number', '');
        if ($method === 'mobile_money' && empty($phoneNumber)) {
            return back()->with('error', 'Veuillez saisir un numero de telephone.');
        }

        // Pour carte, on utilise un identifiant fictif (le token de carte sera gere par le provider)
        if ($method === 'card') {
            $phoneNumber = 'CARD-' . Auth::id();
        }

        $result = $this->paymentService->initiate(
            $transaction,
            $method,
            $phoneNumber,
            $subProvider,
            [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]
        );

        if ($result['success']) {
            // Si c'est une carte avec URL de paiement externe (Flutterwave/CinetPay)
            if (isset($result['payment_url']) && !empty($result['payment_url'])) {
                return redirect()->away($result['payment_url']);
            }

            return redirect()->route('payment.success', $transaction)
                ->with('success', $result['message']);
        }

        return redirect()->route('payment.failure', $transaction)
            ->with('error', $result['message']);
    }

    /**
     * Recoit le callback du provider (redirect apres paiement).
     *
     * Certains providers (Flutterwave, CinetPay) redirigent l'utilisateur
     * vers cette URL apres le paiement, en plus du webhook.
     */
    public function callback(Request $request, Transaction $transaction): RedirectResponse
    {
        $provider = $request->route('provider') ?? $request->input('provider', 'fake');
        $providerReference = $request->input('provider_reference');

        if ($providerReference) {
            $this->paymentService->handleCallback($provider, $request->all());
        }

        // Rediriger vers la page de succes ou echec selon le statut
        $payment = $transaction->payment;

        if ($payment && $payment->isCompleted()) {
            return redirect()->route('payment.success', $transaction);
        }

        return redirect()->route('payment.failure', $transaction)
            ->with('error', 'Le paiement n'a pas pu etre confirme.');
    }

    /**
     * Page de succes apres paiement.
     */
    public function success(Transaction $transaction): View
    {
        $this->authorize('view', $transaction);

        return view('transactions.payment-success', compact('transaction'));
    }

    /**
     * Page d'echec apres paiement.
     */
    public function failure(Transaction $transaction): View
    {
        $this->authorize('view', $transaction);

        return view('transactions.payment-failure', compact('transaction'));
    }
}
