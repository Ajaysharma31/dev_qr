<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;


class QRCodeValidationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        Validator::extend('valid_prcode', function ($attribute, $value, $parameters, $validator) {
            // get the current authenticated user
            $authenticatedUserID = Auth::id();
            // check qrCode existed with authenticated user
            return User::where('qrcode', $value)->where('id', $authenticatedUserID)->exists();
        });
    }
}
