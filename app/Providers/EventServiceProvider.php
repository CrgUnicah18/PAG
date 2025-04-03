<?php


namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\NotificacionCreada; // Asegúrate de importar tu evento
use App\Listeners\NotificacionCreadaListener; // Asegúrate de importar tu listener

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
            // Otros eventos...
        NotificacionCreada::class => [
            NotificacionCreadaListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}