<?php

namespace Boparaiamrit\Webserver\Commands;

use Boparaiamrit\Framework\Commands\AbstractCommand;
use Boparaiamrit\Tenancy\Contracts\HostRepositoryContract;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ToolboxCommand extends AbstractCommand
{
    use DispatchesJobs;

    protected $signature = 'webserver:toolbox
        {--update-configs : Update webserver configuration files}';

    protected $description = 'Allows mutation of webserver related to tenancy.';

    /**
     * @var HostRepositoryContract
     */
    protected $Host;

    /**
     * @param HostRepositoryContract $Host
     */
    public function __construct(HostRepositoryContract $Host)
    {
        parent::__construct();

        $this->Host = $Host;
    }

    /**
     * Handles command execution.
     */
    public function handle()
    {
        $this->Host->queryBuilder()->chunk(50, function ($Hosts) {
            foreach ($Hosts as $Host) {
                if ($this->option('update-configs')) {
                    $this->dispatch(new WebserverCommand($Host->id, 'update'));
                } else {
                    $this->error('Unknown option, please specify one.');

                    return;
                }
            }
        });
    }
}
