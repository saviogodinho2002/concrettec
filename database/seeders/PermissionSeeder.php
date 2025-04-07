<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    private array $models = [
        'user',
        'enterprise',
        'construction',
        'address',
        'phone_number'
    ];

    private array $actions = [
        'view',
        'list',
        'create',
        'edit',
        'delete'
    ];

    public function run(): void
    {
        // Criar role master
        $masterRole = Role::updateOrCreate(
            ['name' => 'master'],
            ['description' => 'Usuário com acesso total ao sistema']
        );

        // Criar permissões para cada modelo
        foreach ($this->models as $model) {
            foreach ($this->actions as $action) {
                $permission = Permission::updateOrCreate(
                    ['name' => "{$model}-{$action}"],
                );

                // Atribuir todas as permissões ao role master
                $masterRole->permissions()->syncWithoutDetaching($permission);
            }
        }

        // Criar roles básicos
        $roles = [
            'gestor' => 'Gestor da empresa',
            'colaborador' => 'Colaborador da empresa',
            'empresa' => 'Usuário da empresa',
            'api' => 'Usuário da API'
        ];

        foreach ($roles as $name => $description) {
            Role::updateOrCreate(
                ['name' => $name],
            );
        }
    }
}
