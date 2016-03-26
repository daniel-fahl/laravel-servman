<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Hash;

class UserCreator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servman:createuser {username} {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user for the application.';

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
        $username = $this->argument('username');
        $name = $this->argument('name');
        $password = $this->ask('Enter password');

        $user = new User;
        if (!empty($name)) {
            $user->name = $name;
        }
        $user->email = $username;
        $user->password = Hash::make($password);
        $user->save();

        $this->info('User \'' . $username . '\' created successfully.');
    }
}
