<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->enterprise_id = $data['enterprise_id'] ?? null;
            $user->save();

            if (isset($data['roles'])) {
                $user->roles()->sync($data['roles']);
            }

            return $user;
        });
    }

    public function update(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            if (isset($data['name'])) {
                $user->name = $data['name'];
            }
            if (isset($data['email'])) {
                $user->email = $data['email'];
            }
            if (isset($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            if (isset($data['enterprise_id'])) {
                $user->enterprise_id = $data['enterprise_id'];
            }

            $user->save();

            if (isset($data['roles'])) {
                $user->roles()->sync($data['roles']);
            }

            return $user;
        });
    }

    public function delete(User $user)
    {
        return DB::transaction(function () use ($user) {
            $user->roles()->detach();
            return $user->delete();
        });
    }

    public function getByEnterprise($enterpriseId)
    {
        return User::where('enterprise_id', $enterpriseId)->get();
    }

    public function getById($id)
    {
        return User::findOrFail($id);
    }
} 