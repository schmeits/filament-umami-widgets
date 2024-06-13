<?php

namespace Schmeits\FilamentUmamiWidgets\Commands;

use Illuminate\Console\Command;

class FilamentUmamiWidgetsCommand extends Command
{
    public $signature = 'filament-umami-widgets';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
