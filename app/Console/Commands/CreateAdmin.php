<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Hash;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createAdmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating Admin';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Admin::create([
            "full_name" => "Bisnu kundu",
            "user_name" => 'bisnukundu',
            "password" => Hash::make('password')

        ]);
    }
}
