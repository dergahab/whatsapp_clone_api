<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Policies\CredentialsPolicy;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => CredentialsPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            $spaUrl = 'http://spa.test?email_verify_url='.$url;

            return (new MailMessage)
                ->subject('E-poçt ünvanını yoxlayın')
                ->line('E-poçt adresinizi yoxlamaq üçün aşağıdakı düyməni vurun.')
                ->action('E-poçt ünvanını yoxlayın', $spaUrl);
        });

    }
}
