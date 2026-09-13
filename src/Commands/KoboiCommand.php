<?php

namespace Jegex\Koboi\Commands;

use Illuminate\Console\Command;

class KoboiCommand extends Command
{
    public $signature = 'koboi';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
