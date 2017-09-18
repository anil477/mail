<?php

namespace App\Providers;

use App\Models\V1\Mail\MailLogging;
use App\Models\V1\Mail\SendGridHelper;
use Illuminate\Support\ServiceProvider;
use App\Repositories\V1\Mail\MailingInterface;
use App\Repositories\V1\Mail\MailLoggingInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Easily  switch  to different kind of data source for logging and not just RDBMS
        $this->app->bind(MailLoggingInterface::class, function () {
            return new MailLogging(
                app()->make(\Psr\Log\LoggerInterface::class)
            );
        });

        // Configure the sendgrid helper
        $this->app->bind(MailingInterface::class, function () {
            return new SendGridHelper($this->app['config']->get('mail.sendgrid_key'));
        });
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
