<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class VerifyUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:verify {email : The email of the user to verify}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually verify a user\'s email address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }
        
        if ($user->hasVerifiedEmail()) {
            $this->info("User {$email} is already verified.");
            return 0;
        }
        
        $user->markEmailAsVerified();
        
        $this->info("User {$email} has been verified successfully!");
        return 0;
    }
}
