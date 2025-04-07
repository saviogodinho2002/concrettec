<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $masterRole = Role::where('name', 'master')->first();

        if (!$masterRole) {
            $this->command->error('Role master não encontrada. Execute o PermissionSeeder primeiro.');
            return;
        }

        $user = User::updateOrCreate(
            ['email' => 'master@concrettec.com'],
            [
                'name' => 'Master User',
                'password' => Hash::make('Str0ngerMidi@s'),
                'enterprise_id' => null,
            ]
        );

        $user->roles()->syncWithoutDetaching([$masterRole->id]);


    }
}
