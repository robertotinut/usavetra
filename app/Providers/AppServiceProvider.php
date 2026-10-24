<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\ActivityLog;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function ($event) {
            ActivityLog::record(
                'login',
                'Pengguna ' . $event->user->name . ' berhasil login ke sistem.',
                $event->user,
                ['email' => $event->user->email, 'role' => $event->user->role],
                $event->user
            );
        });

        Event::listen(Logout::class, function ($event) {
            if ($event->user) {
                ActivityLog::record(
                    'logout',
                    'Pengguna ' . $event->user->name . ' keluar (logout) dari sistem.',
                    $event->user,
                    ['email' => $event->user->email],
                    $event->user
                );
            }
        });
    }
}

