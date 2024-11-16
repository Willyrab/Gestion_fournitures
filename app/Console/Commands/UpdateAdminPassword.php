<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class UpdateAdminPassword extends Command
{

    protected $signature = 'admin:update-password {nom} {password}';

    
    protected $description = 'Update the admin password to use bcrypt hashing';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $login = $this->argument('nom');
        $password = $this->argument('password');

        $admin = Admin::where('nom', $login)->first();

        if ($admin) {
            $admin->password = Hash::make($password);
            $admin->save();
            $this->info('Password updated successfully.');
        } else {
            $this->error('Admin not found.');
        }
    }
}

