<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PaymentProviderInterface;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

/**
 * Factory pour instancier dynamiquement le bon provider de paiement.
 *
 * Cette factory permet de changer de provider sans modifier le code metier.
 * Elle lit la configuration 'payxora.default_payment_provider' pour determiner
 * quel service utiliser par defaut.
 *
 * Providers supportes :
 * - 'tmoney'    → TMoneyService
 * - 'moov'      → MoovMoneyService
 * - 'card'      → CardPaymentService
 * - 'fake'      → FakeMobileMoneyService (dev uniquement)
 *
 * Usage :
 *     $provider = PaymentProviderFactory::make('tmoney');
 *     $result = $provider->initiatePayment(5000, 'XOF', '22890123456', 'Achat');
 *
 *     // Provider par defaut
 *     $provider = PaymentProviderFactory::default();
 */
class PaymentProviderFactory
{
    /**
     * Mapping des noms de providers vers les classes de service.
     */
    private const PROVIDERS = [
        'tmoney' => TMoneyService::class,
        'moov'   => MoovMoneyService::class,
        'card'   => CardPaymentService::class,
        'fake'   => FakeMobileMoneyService::class,
    ];

    /**
     * Instancie un provider par son nom.
     *
     * @param string $name Nom du provider (tmoney, moov, card, fake)
     *
     * @throws InvalidArgumentException Si le provider n'existe pas
     */
    public static function make(string $name): PaymentProviderInterface
    {
        $name = strtolower($name);

        if (!isset(self::PROVIDERS[$name])) {
            throw new InvalidArgumentException(
                "Provider de paiement '{$name}' non supporte. " .
                "Providers disponibles : " . implode(', ', array_keys(self::PROVIDERS))
            );
        }

        $class = self::PROVIDERS[$name];
        $instance = app($class);

        Log::debug('PaymentProviderFactory : provider instancie', [
            'provider' => $name,
            'class'    => $class,
        ]);

        return $instance;
    }

    /**
     * Retourne le provider par defaut configure dans l'application.
     *
     * En environnement local/testing, retourne 'fake' si non configure.
     */
    public static function default(): PaymentProviderInterface
    {
        $default = config('payxora.default_payment_provider');

        // En dev, fallback sur fake si non configure
        if (empty($default) && app()->environment('local', 'testing')) {
            $default = 'fake';
            Log::info('PaymentProviderFactory : fallback sur fake_mobile_money en environnement dev');
        }

        if (empty($default)) {
            throw new InvalidArgumentException(
                "Aucun provider de paiement par defaut configure. " .
                "Veuillez definir 'payxora.default_payment_provider' dans votre configuration."
            );
        }

        return self::make($default);
    }

    /**
     * Retourne le provider selon la methode de paiement choisie.
     *
     * @param string $method 'mobile_money' ou 'card'
     * @param string|null $subProvider Sous-provider specifique (tmoney, moov, stripe...)
     */
    public static function forMethod(string $method, ?string $subProvider = null): PaymentProviderInterface
    {
        $method = strtolower($method);

        if ($method === 'mobile_money') {
            // Si un sous-provider est specifie, on l'utilise
            if ($subProvider !== null) {
                return self::make($subProvider);
            }

            // Sinon, on prend le premier MM configure
            if (app(TMoneyService::class)->isConfigured()) {
                return app(TMoneyService::class);
            }

            if (app(MoovMoneyService::class)->isConfigured()) {
                return app(MoovMoneyService::class);
            }

            // Fallback en dev
            if (app()->environment('local', 'testing')) {
                return app(FakeMobileMoneyService::class);
            }

            throw new InvalidArgumentException('Aucun provider Mobile Money configure.');
        }

        if ($method === 'card') {
            return app(CardPaymentService::class);
        }

        throw new InvalidArgumentException(
            "Methode de paiement '{$method}' non supportee. " .
            "Methodes disponibles : mobile_money, card"
        );
    }

    /**
     * Retourne la liste de tous les providers disponibles.
     *
     * @return array<string, string> [nom => nom_classe]
     */
    public static function all(): array
    {
        return self::PROVIDERS;
    }

    /**
     * Retourne la liste des providers configures et prets a l'emploi.
     *
     * @return array<string, array> [nom => ['configured' => bool, 'sandbox' => bool]]
     */
    public static function available(): array
    {
        $available = [];

        foreach (self::PROVIDERS as $name => $class) {
            try {
                $provider = app($class);
                $available[$name] = [
                    'configured' => $provider->isConfigured(),
                    'sandbox'    => $provider->isSandbox(),
                    'class'      => $class,
                ];
            } catch (\Exception $e) {
                $available[$name] = [
                    'configured' => false,
                    'sandbox'    => false,
                    'error'      => $e->getMessage(),
                ];
            }
        }

        return $available;
    }

    /**
     * Verifie si un provider existe.
     */
    public static function exists(string $name): bool
    {
        return isset(self::PROVIDERS[strtolower($name)]);
    }
}
