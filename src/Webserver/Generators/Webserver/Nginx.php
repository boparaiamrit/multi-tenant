<?php

namespace Hyn\Webserver\Generators\Webserver;

use Config;
use Hyn\Webserver\Generators\AbstractFileGenerator;

class Nginx extends AbstractFileGenerator
{
    /**
     * Generates the view that is written.
     *
     * @return \Illuminate\View\View
     */
    public function generate()
    {
        return view('webserver::nginx.configuration', [
            'website'     => $this->website,
            'public_path' => public_path(),
            'log_path'    => config('webserver.log.path')."/nginx-{$this->website->identifier}",
            'config'      => config('webserver.nginx'),
            'fpm_port'    => config('webserver.fpm.port'),
        ]);
    }

    /**
     * Provides the complete path to publish the generated content to.
     *
     * @return string
     */
    protected function publishPath()
    {
        return sprintf('%s%s.conf', config('webserver.nginx.path'), $this->name());
    }
}
