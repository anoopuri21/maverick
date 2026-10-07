<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create
                            {--email= : Admin email address (prompted if omitted)}
                            {--password= : Admin password (prompted if omitted)}
                            {--name= : Display name, defaults to "Admin" for new users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Filament admin user, or promote an existing user to admin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = (string) ($this->option('email') ?: $this->ask('Admin email', 'admin@maverick.test'));

        $password = (string) ($this->option('password') ?: $this->secret('Admin password (hidden, leave blank to keep existing)'));

        $existing = User::where('email', $email)->first();

        if (! $existing && $password === '') {
            $this->error('A password is required when creating a new user.');

            return self::FAILURE;
        }

        $validator = Validator::make(
            ['email' => $email, 'password' => $password],
            [
                'email' => ['required', 'email:rfc'],
                'password' => [$existing ? 'nullable' : 'required', 'string', 'min:8'],
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        $attributes = [
            'name' => $existing?->name ?: ((string) $this->option('name') ?: 'Admin'),
            'is_admin' => true,
        ];

        if ($password !== '') {
            // Hashed automatically by the User model's 'password' => 'hashed' cast.
            $attributes['password'] = $password;
        }

        $user = User::updateOrCreate(['email' => $email], $attributes);

        $this->newLine();
        $this->info($existing ? "Promoted existing user to admin: {$user->email}" : "Created admin user: {$user->email}");
        $this->line('  Sign in at: '.rtrim((string) config('app.url'), '/').'/admin');
        $this->newLine();

        return self::SUCCESS;
    }
}
