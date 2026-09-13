<?php

namespace Jegex\Koboi;

use Closure;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Jegex\Koboi\Auth\Actions\ConfirmPasswordViewResponse;
use Jegex\Koboi\Auth\Actions\FailedPasswordConfirmationResponse;
use Jegex\Koboi\Auth\Actions\FailedPasswordResetLinkRequestResponse;
use Jegex\Koboi\Auth\Actions\LoginResponse;
use Jegex\Koboi\Auth\Actions\LoginViewResponse;
use Jegex\Koboi\Auth\Actions\PasskeyLoginResponse;
use Jegex\Koboi\Auth\Actions\PasswordConfirmedResponse;
use Jegex\Koboi\Auth\Actions\PasswordUpdateResponse;
use Jegex\Koboi\Auth\Actions\RedirectAsIntendedForNova;
use Jegex\Koboi\Auth\Actions\RedirectIfTwoFactorAuthenticatable;
use Jegex\Koboi\Auth\Actions\RequestPasswordResetLinkViewResponse;
use Jegex\Koboi\Auth\Actions\ResetPasswordViewResponse;
use Jegex\Koboi\Auth\Actions\ResetUserPassword;
use Jegex\Koboi\Auth\Actions\SuccessfulPasswordResetLinkRequestResponse;
use Jegex\Koboi\Auth\Actions\TwoFactorChallengeViewResponse;
use Jegex\Koboi\Auth\Actions\TwoFactorLoginResponse;
use Jegex\Koboi\Auth\Actions\UpdateUserPassword;
use Jegex\Koboi\Auth\Actions\VerifyEmailViewResponse;
use Jegex\Koboi\Events\ServingNova;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable as FortifyRedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\ConfirmPasswordViewResponse as ConfirmPasswordViewResponseContract;
use Laravel\Fortify\Contracts\FailedPasswordConfirmationResponse as FailedPasswordConfirmationResponseContract;
use Laravel\Fortify\Contracts\FailedPasswordResetLinkRequestResponse as FailedPasswordResetLinkRequestResponseContract;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\LoginViewResponse as LoginViewResponseContract;
use Laravel\Fortify\Contracts\PasswordConfirmedResponse as PasswordConfirmedResponseContract;
use Laravel\Fortify\Contracts\PasswordUpdateResponse as PasswordUpdateResponseContract;
use Laravel\Fortify\Contracts\RequestPasswordResetLinkViewResponse as RequestPasswordResetLinkViewResponseContract;
use Laravel\Fortify\Contracts\ResetPasswordViewResponse as ResetPasswordViewResponseContract;
use Laravel\Fortify\Contracts\ResetsUserPasswords as ResetsUserPasswordsContract;
use Laravel\Fortify\Contracts\SuccessfulPasswordResetLinkRequestResponse as SuccessfulPasswordResetLinkRequestResponseContract;
use Laravel\Fortify\Contracts\TwoFactorChallengeViewResponse as TwoFactorChallengeViewResponseContract;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Laravel\Fortify\Contracts\UpdatesUserPasswords as UpdatesUserPasswordsContract;
use Laravel\Fortify\Contracts\VerifyEmailViewResponse as VerifyEmailViewResponseContract;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Responses\RedirectAsIntended;
use Laravel\Passkeys\Contracts\PasskeyLoginResponse as PasskeyLoginResponseContract;

class PendingFortifyConfiguration
{
    /**
     * List of Laravel Fortify features.
     *
     * @var array<int, string>|null
     */
    public ?array $features = null;

    /**
     * List of Laravel Fortify options.
     *
     * @var array<string, mixed>|null
     */
    public ?array $options = null;

    /**
     * The username used for authentication.
     */
    public string $username;

    /**
     * The name of the email address request variable / field.
     */
    public string $email;

    /**
     * List of Laravel Fortify features.
     */
    protected ?array $cachedConfig = null;

    /**
     * List of Laravel Fortify features.
     */
    protected ?array $cachedOptionsConfig = null;

    /**
     * The callback that is responsible for building the authentication pipeline array, if applicable.
     *
     * @var (callable(Request):(array<int, string|class-string>))|null
     */
    protected static $authenticateThroughCallback = null;

    /**
     * The original callback that is responsible for building the authentication pipeline array, if applicable.
     *
     * @var (callable(Request):(array<int, string|class-string>))|null
     */
    protected static $originalAuthenticateThroughCallback = null;

    /**
     * The callback that is responsible for validating authentication credentials, if applicable.
     *
     * @var (callable(Request):(mixed|null))|null
     */
    protected static $authenticateUsingCallback = null;

    /**
     * The original callback that is responsible for validating authentication credentials, if applicable.
     *
     * @var (callable(Request):(mixed|null))|null
     */
    protected static $originalAuthenticateUsingCallback = null;

    /**
     * The callback that is responsible for confirming user passwords.
     *
     * @var (callable(mixed, ?string):(bool))|null
     */
    protected static $confirmPasswordsUsingCallback = null;

    /**
     * The original callback that is responsible for confirming user passwords.
     *
     * @var (callable(mixed, ?string):(bool))|null
     */
    protected static $originalConfirmPasswordsUsingCallback = null;

    /**
     * Determined whether Fortify configurations and static props has been cached.
     */
    protected bool $cached = false;

    /**
     * Determine whether Foritfy is configured to load authentication routes for frontend.
     */
    protected ?bool $withFrontendRoutes = null;

    /**
     * Construct a new instance.
     */
    public function __construct()
    {
        $this->username = Fortify::username();
        $this->email = Fortify::email();

        $this->cachedConfig = config('fortify', []);
        $this->cachedOptionsConfig = config('fortify-options', []);
    }

    /**
     * Set Laravel Fortify enabled features.
     *
     * @param  (Closure():(array<int, string>|null))|array<int, string>|null  $features
     * @return $this
     */
    public function features(Closure|array|null $features = null)
    {
        if (! \is_null($features)) {
            $this->features = Arr::wrap(value($features));

            $this->options = config('fortify-options');
        }

        return $this;
    }

    /**
     * Determine if the given feature is enabled.
     */
    public function enabled(string $feature): bool
    {
        return \in_array($feature, $this->features ?? config('fortify.features', []));
    }

    /**
     * Determine if the application is using any security profile features.
     */
    public function hasSecurityFeatures(): bool
    {
        return $this->enabled(Features::updatePasswords()) ||
               $this->canManageTwoFactorAuthentication();
    }

    /**
     * Determine if the application can manage two factor authentication.
     */
    public function canManageTwoFactorAuthentication(): bool
    {
        return $this->enabled(Features::twoFactorAuthentication());
    }

    /**
     * Set the username used for authentication.
     *
     * @return $this
     */
    public function usernameUsing(string $attribute)
    {
        $this->username = $attribute;

        return $this;
    }

    /**
     * Set the email used for authentication.
     *
     * @return $this
     */
    public function emailUsing(string $attribute)
    {
        $this->email = $attribute;

        return $this;
    }

    /**
     * Register a callback that is responsible for building the authentication pipeline array.
     *
     * @param  callable(Request):(array<int, string|class-string>)  $callback
     * @return $this
     */
    public function authenticateThrough(callable $callback)
    {
        static::$authenticateThroughCallback = $callback;

        return $this;
    }

    /**
     * Register a callback that is responsible for validating incoming authentication credentials.
     *
     * @param  callable(Request):(mixed|null)  $callback
     * @return $this
     */
    public function authenticateUsing(callable $callback)
    {
        static::$authenticateUsingCallback = $callback;

        return $this;
    }

    /**
     * Register a callback responsible for confirming existing user passwords as valid.
     *
     * @param  callable(mixed, ?string):bool  $callback
     * @return $this
     */
    public function confirmPasswordsUsing(callable $callback)
    {
        static::$confirmPasswordsUsingCallback = $callback;

        return $this;
    }

    /**
     * Determine if Laravel Fortify uses the same auth guard as Nova.
     */
    public function usingIdenticalGuardOrModel(): bool
    {
        $fortifyGuard = config('fortify.guard') ?? config('auth.defaults.guard');
        $novaGuard = Util::userGuard();

        if ($fortifyGuard === $novaGuard) {
            return true;
        }

        return Util::userModelFromGuard($fortifyGuard) === Util::userModelFromGuard($novaGuard);
    }

    /**
     * Synchronize Laravel Forify's configurations.
     */
    public function sync(): void
    {
        if ($this->cached === true) {
            return;
        }

        if (Nova::routes()->defaultAuthentication()) {
            config([
                'fortify.features' => $this->features ?? [],
                'fortify-options' => $this->options ?? [],
                'fortify.username' => $this->username,
                'fortify.email' => $this->email,
            ]);
        } else {
            config([
                'fortify.features' => $this->features ?? Arr::get($this->cachedConfig, 'features', []),
                'fortify-options' => $this->options ?? $this->cachedOptionsConfig,
                'fortify.username' => $this->username,
                'fortify.email' => $this->email,
            ]);
        }

        Fortify::$authenticateThroughCallback = static::$authenticateThroughCallback ?? static::$originalAuthenticateThroughCallback;
        Fortify::$authenticateUsingCallback = static::$authenticateUsingCallback ?? static::$originalAuthenticateUsingCallback;
        Fortify::$confirmPasswordsUsingCallback = static::$confirmPasswordsUsingCallback ?? static::$originalConfirmPasswordsUsingCallback;

        $this->cached = true;
    }

    /**
     * Flush Laravel Forify's configurations.
     */
    public function flush(): void
    {
        if (! Nova::routes()->defaultAuthentication()) {
            config([
                'fortify' => $this->cachedConfig,
                'fortify-options' => $this->cachedOptionsConfig,
            ]);
        }

        if (\is_callable(static::$originalAuthenticateThroughCallback)) {
            Fortify::$authenticateThroughCallback = static::$originalAuthenticateThroughCallback;
        }

        if (\is_callable(static::$originalAuthenticateUsingCallback)) {
            Fortify::$authenticateUsingCallback = static::$originalAuthenticateUsingCallback;
        }

        if (\is_callable(static::$originalConfirmPasswordsUsingCallback)) {
            Fortify::$confirmPasswordsUsingCallback = static::$originalConfirmPasswordsUsingCallback;
        }

        $this->cached = false;
    }

    /**
     * Register Fortify.
     */
    public function register(?bool $routes = null): void
    {
        if (\is_null($routes)) {
            $routes = Fortify::$registersRoutes === true
                ? Util::isFortifyRoutesRegisteredForFrontend()
                : false;
        }

        if ($routes === false && Fortify::$registersRoutes === true) {
            Fortify::ignoreRoutes();
        }

        $this->withFrontendRoutes = $routes;
    }

    /**
     * Bootstrap the registered Nova routes.
     */
    public function bootstrap(): void
    {
        /** @var PendingRouteRegistration $routes */
        $routes = Nova::routes();

        $this->features = collect($this->features ?? [])->merge(array_filter([
            $routes->withPasswordReset ? Features::resetPasswords() : null,
            $routes->withEmailVerification ? Features::emailVerification() : null,
        ]))->unique()->all();

        static::$originalAuthenticateThroughCallback ??= Fortify::$authenticateThroughCallback;
        static::$originalAuthenticateUsingCallback ??= Fortify::$authenticateUsingCallback;
        static::$originalConfirmPasswordsUsingCallback ??= Fortify::$confirmPasswordsUsingCallback;

        Nova::serving(function (ServingNova $event) use ($routes) {
            $this->sync();

            /** @var Application $app */
            $app = $event->app;

            $app->scoped(StatefulGuard::class, static fn () => Auth::guard(Util::userGuard()));
            $app->scoped(RedirectAsIntended::class, RedirectAsIntendedForNova::class);

            if ($routes->withAuthentication === true) {
                $app->scoped(LoginViewResponseContract::class, LoginViewResponse::class);
                $app->scoped(LoginResponseContract::class, LoginResponse::class);
                $app->scoped(TwoFactorChallengeViewResponseContract::class, TwoFactorChallengeViewResponse::class);
                $app->scoped(TwoFactorLoginResponseContract::class, TwoFactorLoginResponse::class);
                $app->scoped(FortifyRedirectIfTwoFactorAuthenticatable::class, RedirectIfTwoFactorAuthenticatable::class);
                $app->scoped(PasskeyLoginResponseContract::class, PasskeyLoginResponse::class);
            }

            if ($routes->withPasswordReset === true) {
                if ($routes->withPasswordResetPreventsEmailEnumeration === true) {
                    $app->scoped(SuccessfulPasswordResetLinkRequestResponseContract::class, SuccessfulPasswordResetLinkRequestResponse::class);
                    $app->scoped(FailedPasswordResetLinkRequestResponseContract::class, FailedPasswordResetLinkRequestResponse::class);
                }

                $app->scoped(ResetPasswordViewResponseContract::class, ResetPasswordViewResponse::class);
                $app->scoped(RequestPasswordResetLinkViewResponseContract::class, RequestPasswordResetLinkViewResponse::class);
                $app->scoped(ResetsUserPasswordsContract::class, ResetUserPassword::class);

                ResetPassword::toMailUsing(static function ($notifiable, $token) {
                    return (new MailMessage)
                        ->subject(Nova::__('Reset Password Notification'))
                        ->line(Nova::__('You are receiving this email because we received a password reset request for your account.'))
                        ->action(Nova::__('Reset Password'), route('nova.pages.password.reset', ['token' => $token]))
                        ->line(Nova::__('If you did not request a password reset, no further action is required.'));
                });
            }

            $app->scoped(VerifyEmailViewResponseContract::class, VerifyEmailViewResponse::class);

            $app->scoped(PasswordUpdateResponseContract::class, PasswordUpdateResponse::class);
            $app->scoped(UpdatesUserPasswordsContract::class, UpdateUserPassword::class);

            $app->scoped(ConfirmPasswordViewResponseContract::class, ConfirmPasswordViewResponse::class);
            $app->scoped(PasswordConfirmedResponseContract::class, PasswordConfirmedResponse::class);
            $app->scoped(FailedPasswordConfirmationResponseContract::class, FailedPasswordConfirmationResponse::class);
        });
    }
}
