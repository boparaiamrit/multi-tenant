<?php

namespace Boparaiamrit\Tenancy\Jobs;


use Boparaiamrit\Tenancy\Models\Host;
use Boparaiamrit\Webserver\Generators\Webserver\Env;
use Boparaiamrit\Webserver\Generators\Webserver\Fpm;
use Boparaiamrit\Webserver\Generators\Webserver\Nginx;
use Boparaiamrit\Webserver\Generators\Webserver\Supervisor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Jenssegers\Mongodb\Eloquent\Model;
use MongoDB\BSON\UTCDateTime;

class WebserverJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * @var Model|Host
     */
    protected $Host;

    /**
     * @var Model
     */
    protected $user = null;

    public function __construct($Host, $user = [])
    {
        $this->Host = $Host;
        $this->user = $user;
    }

    public function handle()
    {
        $this->job->delete();

        // Php FPM
        (new Fpm($this->Host))->onCreate();

        // Supervisor
        (new Supervisor($this->Host))->onCreate();

        // Webservers
        (new Nginx($this->Host))->onCreate();

        // Env
        (new Env($this->Host))->onCreate();

        app(Kernel::class)->call('db:seed', [
            '--force' => true, '--hostname' => $this->Host->identifier
        ]);

        if (!empty($this->user)) {
            $this->createAdmin();
        }

    }

    private function createAdmin()
    {
        $hostname = $this->Host->identifier;

        array_set($GLOBALS, 'hostname', $hostname);

        if ($hostname != config('env.default_host')) {
            /** @noinspection PhpUndefinedMethodInspection */
            $path = app()->bootstrapPath() . '/app.php';

            /** @noinspection PhpIncludeInspection */
            /** @var Application $app */
            $app = require $path;
            $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        }

        $nameParts = explode(' ', array_get($this->user, 'name'));
        if (count($nameParts) == 1) {
            $firstName = array_shift($nameParts);
            $lastName  = '';
        } else if (count($nameParts) == 2) {
            $firstName = array_shift($nameParts);
            $lastName  = array_shift($nameParts);
        } else {
            $firstName = array_shift($nameParts);
            $lastName  = implode(' ', $nameParts);
        }

        $data = [
            'name'     => array_get($this->user, 'name'),
            'email'    => array_get($this->user, 'email'),
            'hostname' => $this->Host->hostname,
            'bcc'      => ['puneet@promoto.co', 'nancy@promoto.co']
        ];

        $timestamp = new UTCDateTime(time() * 1000);

        app('db')->collection('admins')
                 ->insert([
                              'first_name' => $firstName,
                              'last_name'  => $lastName,
                              'email'      => $data['email'],
                              'name'       => $data['name'],
                              'password'   => bcrypt('welcome'),
                              'image'      => 'https://dummyimage.com/600x400/15a0eb/fff&text=Promoto',
                              'created_at' => $timestamp,
                              'updated_at' => $timestamp,
	                          'is_active'  => true
                          ]);

        app('mailer')->send('emails.new_domain_setup', ['data' => $data], function ($Message) use ($data) {
            /** @var Message $Message */
            $Message->from('support@promoto.co', 'Promoto');

            $Message->to($data['email'])->bcc($data['bcc'])->subject('Yuppie!! Your Promoto copy is ready.');
        });
    }
}