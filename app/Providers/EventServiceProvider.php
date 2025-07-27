<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Auth\Events\Login;
use App\Listeners\LogUserLogin;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */


    protected $listen = [
        Login::class => [LogUserLogin::class],
    ];

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
    }

    public function handle(Login $event): void
    {
        $user = $event->user;

        // Optional: Update last login timestamp on the user
        $user->update([
            'last_login_at' => now(),
        ]);

        // Create a login log record
        LoginLog::create([
            'user_id' => $user->id,
            'logged_in_at' => now(),
        ]);
    }
}
