<?php

namespace Boparaiamrit\Tenancy\Commands;


use Boparaiamrit\Tenancy\Jobs\WebserverJob;
use Boparaiamrit\Tenancy\Models\Host;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SetupCommand extends Command
{
    use DispatchesJobs;
    /**
     * @var string
     */
    protected $signature = 'multitenant:setup
		{--domain= : Domain or domain for the the Customer website}
		{--email= : Customer Email}
		{--name= : Customer Name}
        {--identifier= : Website identifier}';

    /**
     * @var string
     */
    protected $description = 'Final configuration step for boparaiamrit multitenancy packages.';

    /**
     * Handles the set up.
     */
    public function handle()
    {
        $domain     = $this->option('domain');
        $email      = $this->option('email');
        $name       = $this->option('name');
        $identifier = $this->option('identifier');

        if (empty($domain)) {
            $domain = $this->ask('Please provide a customer domain or reload command with --domain');
        }

        if (!empty($identifier) && strlen($identifier) > 100) {
            $identifier = $this->ask('Please provide an identifier with a max length of 10 or reload command with --identifier');
        }

        // Seed DB with Local Data
        $this->info('Multitenancy Setup');

        // Create Host
        $Host = $this->createHost($identifier, $domain);

        $this->dispatch((new WebserverJob($Host, compact('name', 'email')))->onQueue('system'));

        $this->info('Host has been created. Other Processes going on. Once completed, you will be notify. ');
    }

    /**
     * @param $identifier
     * @param $domain
     *
     * @return Host
     */
    private function createHost($identifier, $domain)
    {
        if (empty($identifier)) {
            $identifier = hostname_cleaner($domain);
        }

        /** @noinspection PhpUndefinedFieldInspection */
        /** @var Host $Host */
        $Host = Host::firstOrNew([
                                     Host::HOSTNAME   => $domain,
                                     Host::IDENTIFIER => $identifier
                                 ]);

        $Host->save();

        return $Host;
    }
}
