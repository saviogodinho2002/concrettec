<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    private array $models = [
        'user',
        'enterprise',
        'construction',
        'address',
        'phone_number',
        'roles_permissions'
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
            ['guard_name' => 'web'],
        );

        // Criar permissões para cada modelo
        foreach ($this->models as $model) {
            foreach ($this->actions as $action) {
                $permission = Permission::updateOrCreate(
                    ['name' => "{$model}-{$action}"],
                    ['guard_name' => 'web'],

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
                ['guard_name' => 'web'],

            );
        }
    }
}
