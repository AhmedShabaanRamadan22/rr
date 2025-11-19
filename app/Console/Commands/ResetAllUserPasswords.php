<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResetAllUserPasswords extends Command
{

    protected $signature = 'users:reset-passwords';
    protected $description = 'Reset all user passwords and upload result to S3 as CSV';

    public function handle()
    {
        $this->info('Resetting all user passwords...');

        $csvContent = "ID,Name,Email,New Password\n";

        $users = User::whereHas('roles',function($q){
            $q->whereIn('name',['superadmin','admin']);
        })->get();

        foreach ($users as $user) {
            $newPassword = Str::random(15);

            $user->update(['password' => Hash::make($newPassword)]);

            $csvContent .= '"' . $user->id . '","' . $user->name . '","' . $user->email . '","' . $newPassword . "\"\n";
        }

        $filename = 'password-resets-' . now()->format('Ymd_His') . '.csv';

        Mail::raw('Attached is the list of reset user passwords.', function ($message) use ($csvContent, $filename) {
            $message->to('o.khan@rakaya.co')
                    ->cc('o.jehni@rakaya.co')
                    ->subject('Reset User Passwords')
                    ->attachData($csvContent, $filename, [
                        'mime' => 'text/csv',
                    ]);
        });
        
        // $filename = 'public/password-resets/' . now()->format('Ymd_His') . '.csv';

        // Storage::disk()->put($filename, $csvContent);
        // Storage::disk('s3')->put($filename, $csvContent);

        // $url = Storage::disk('s3')->temporaryUrl($filename,now()->addMinutes(7*24*60));
        // $this->info("CSV uploaded to S3: $url");

        $this->info("Passwords reset & send email successfully.");

        return Command::SUCCESS;
    }
}
