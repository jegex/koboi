<?php

namespace Jegex\Koboi\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Jegex\Koboi\Nova;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'nova:check-license')]
class CheckLicenseCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nova:check-license';

    /** * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify your Nova license key';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Cache::forget('nova_valid_license_key');

        $response = Nova::checkLicense();

        if ($response->status() == 204) {
            $this->components->info('Your license key is valid and correctly configured! Thank you for being a Nova customer. 🚀');

            return self::SUCCESS;
        }

        $this->components->error($response->json('message'));

        return self::FAILURE;
    }
}
