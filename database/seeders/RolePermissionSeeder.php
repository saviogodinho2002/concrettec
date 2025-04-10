<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Limpar cache de permissões
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Atribuir permissões às roles
        $rolePermissions = [
            // Gestor - pode gerenciar obras e agendamentos da sua empresa (RN 1.6)
            'gestor' => [
                'enterprise-view', // Pode ver sua própria empresa
                'enterprise-list', // Pode ver sua própria empresa
                'construction-list',
                'construction-view',
             /*   'schedule-list',
                'schedule-view',
                'schedule-create',
                'schedule-edit',
                'schedule-cancel',*/
                'user-list', // Pode ver usuários da sua empresa
                'user-view',
            ],

            // Colaborador - pode visualizar obras e agendamentos (RN 1.6)
            'colaborador' => [
                'construction-list',
                'construction-view',
         /*       'schedule-list',
                'schedule-view',*/
            ],

            // Empresa - usuário do tipo construtora (RN 2.2)
            'empresa' => [
                'construction-list', // Pode ver suas obras
                'construction-view',

               /* 'schedule-list', // Pode gerenciar agendamentos
                'schedule-view',
                'schedule-create',
                'schedule-cancel',*/
            ],

            // API - sem permissões por enquanto (RN 1.5 - Autenticação obrigatória)
            'api' => [
                'construction-list', // Pode ver suas obras
                'construction-view',

            ],
        ];

        foreach ($rolePermissions as $roleName => $permissionNames) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->syncPermissions($permissionNames);
            }
        }

        $this->command->info('Permissões atribuídas com sucesso!');
    }
}
