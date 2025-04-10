<?php

namespace App\Services;

use App\Models\User;
use App\Models\PhoneNumber;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserService
{
    /**
     * Listar todos os usuários
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listUsers()
    {
        return User::with(['roles', 'phoneNumbers', 'enterprise'])->get();
    }

    /**
     * Buscar um usuário pelo ID
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return User::with(['roles', 'phoneNumbers', 'enterprise'])->find($id);
    }

    /**
     * Criar um novo usuário
     *
     * @param array $userData
     * @param array $phoneNumbers
     * @param array $roleNames
     * @return User
     */
    public function createUser(array $userData, array $phoneNumbers = [], array $roleNames = []): User
    {
        return DB::transaction(function () use ($userData, $phoneNumbers, $roleNames) {
            // Verificar se as roles existem
            $validRoles = [];
            if (!empty($roleNames)) {
                Log::info('Verificando roles para criar:', ['roles' => $roleNames]);
                $validRoles = $this->getValidRoleNames($roleNames);
                Log::info('Roles válidas para criar:', ['valid_roles' => $validRoles]);
            }

            // Criar usuário
            $userData['password'] = Hash::make($userData['password']);
            $user = User::create($userData);

            // Adicionar números de telefone
            $this->syncPhoneNumbers($user, $phoneNumbers);

            // Adicionar roles
            if (!empty($validRoles)) {
                $user->syncRoles($validRoles);
            }

            return $user->fresh(['roles', 'phoneNumbers', 'enterprise']);
        });
    }

    /**
     * Atualizar um usuário existente
     *
     * @param User $user
     * @param array $userData
     * @param array $phoneNumbers
     * @param array $roleNames
     * @return User
     */
    public function updateUser(User $user, array $userData, array $phoneNumbers = [], array $roleNames = []): User
    {
        return DB::transaction(function () use ($user, $userData, $phoneNumbers, $roleNames) {
            // Verificar se as roles existem
            $validRoles = [];
            if (!empty($roleNames)) {
                Log::info('Verificando roles para atualizar:', ['roles' => $roleNames, 'user_id' => $user->id]);
                $validRoles = $this->getValidRoleNames($roleNames);
                Log::info('Roles válidas para atualizar:', ['valid_roles' => $validRoles, 'user_id' => $user->id]);
            }

            // Se a senha estiver no array mas vazia, remova-a
            if (isset($userData['password']) || empty($userData['password'])) {
                unset($userData['password']);

            } elseif (isset($userData['password'])) {
                $userData['password'] = Hash::make($userData['password']);
            }
            // Atualizar usuário
            $user->update($userData);

            // Sincronizar números de telefone
            $this->syncPhoneNumbers($user, $phoneNumbers);

            // Sincronizar roles
            if (!empty($validRoles)) {
                $user->syncRoles($validRoles);
            } else {
                $user->roles()->detach();
            }

            return $user->fresh(['roles', 'phoneNumbers', 'enterprise']);
        });
    }

    /**
     * Excluir um usuário (soft delete)
     *
     * @param User $user
     * @return bool
     */
    public function deleteUser(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            // Marcar email como excluído para permitir reutilização futura
            $timestamp = now()->timestamp;
            $user->email = $user->email . '_excluido_' . $timestamp;
            $user->save();

            return $user->delete();
        });
    }

    /**
     * Sincronizar números de telefone para um usuário
     *
     * @param User $user
     * @param array $phoneNumbers
     * @return void
     */
    private function syncPhoneNumbers(User $user, array $phoneNumbers): void
    {
        $user->phoneNumbers()->delete();

        foreach ($phoneNumbers as $phoneNumber) {
            if (!empty($phoneNumber['number'])) {
                $user->phoneNumbers()->create([
                    'number' => $phoneNumber['number'],
                    'type' => $phoneNumber['type'] ?? 'celular',
                ]);
            }
        }
    }

    public function getByEnterprise($enterpriseId)
    {
        return User::where('enterprise_id', $enterpriseId)->get();
    }

    public function getById($id)
    {
        return User::findOrFail($id);
    }

    /**
     * Obter nomes de roles válidos a partir de um array de nomes
     *
     * @param array $roleNames
     * @return array
     */
    private function getValidRoleNames(array $roleNames): array
    {
        // Buscar as roles existentes no banco
        $existingRoles = Role::whereIn('name', $roleNames)->pluck('name')->toArray();

        Log::info('Roles encontradas no banco:', ['roles' => $existingRoles]);

        return $existingRoles;
    }
}
