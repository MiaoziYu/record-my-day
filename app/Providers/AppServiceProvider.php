<?php

namespace App\Providers;

use App\Observers\RecordObserver;
use App\Observers\TodoObserver;
use App\Record;
use App\Todo;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Record::observe(RecordObserver::class);
        Todo::observe(TodoObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
