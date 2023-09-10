<?php

namespace Rabibgalib\ApiAction;

use Illuminate\Support\ServiceProvider;
use Rabibgalib\ApiAction\Console\Commands\MakeActionCommand;

class ApiActionServiceProvider extends ServiceProvider
{

    protected array $commands = [
        MakeActionCommand::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->commands($this->commands);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
