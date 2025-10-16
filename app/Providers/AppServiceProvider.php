<?php

namespace App\Providers;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
      // Set locale Carbon ke Indonesia
    Carbon::setLocale('id');
    Paginator::useTailwind();
    
    
    // Jika pakai locale Indonesia di PHP secara umum (optional)
    setlocale(LC_TIME, 'id_ID.utf8');
    }
}
