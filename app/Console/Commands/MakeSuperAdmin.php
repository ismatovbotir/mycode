<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('make:super-admin {email}')]
#[Description('Make a user a super admin')]
class MakeSuperAdmin extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found");
            return 1;
        }

        $user->update(['role' => 'super_admin']);

        $this->info("User {$user->name} ({$email}) is now a super admin! 🎉");
        return 0;
    }
}
