<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DigitalOcean;
use Exception;

class DestroyAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servman:destroyall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Destroys all servman servers currently running';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // get all droplets from account
        $allDroplets = array();
        try {
            $allDroplets = DigitalOcean::droplet()->getAll();
        } catch (Exception $e) {
            $this->error('Error fetching droplets: ' . $e->getMessage());
        }

        // delete droplets prefixed with "servman-"
        foreach ($allDroplets as $droplet) {
            $name = $droplet->name;
            $id = $droplet->id;
            if (strpos($name, 'servman-') === 0) {
                // Destroy droplet by $dropletId
                try {
                    DigitalOcean::droplet()->delete($id);
                    $this->info('Destroying droplet ' . $id);
                } catch (Exception $e) {
                    $this->error('Error destroying droplet ' . $id . ': ' . $e->getMessage());
                }

            }
        }

    }
}
