<?php

namespace App\Services;

use App\Models\Enterprise;
use Illuminate\Support\Facades\DB;

class EnterpriseService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $enterprise = new Enterprise();
            $enterprise->name = $data['name'];
            $enterprise->type = $data['type'];
            $enterprise->cnpj = $data['cnpj'];
            $enterprise->save();

            return $enterprise;
        });
    }

    public function update(Enterprise $enterprise, array $data)
    {
        return DB::transaction(function () use ($enterprise, $data) {
            if (isset($data['name'])) {
                $enterprise->name = $data['name'];
            }
            if (isset($data['type'])) {
                $enterprise->type = $data['type'];
            }
            if (isset($data['cnpj'])) {
                $enterprise->cnpj = $data['cnpj'];
            }

            $enterprise->save();
            return $enterprise;
        });
    }

    public function delete(Enterprise $enterprise)
    {
        return DB::transaction(function () use ($enterprise) {
            return $enterprise->delete();
        });
    }

    public function getById($id)
    {
        return Enterprise::findOrFail($id);
    }

    public function getAll()
    {
        return Enterprise::all();
    }

    public function getByType($type)
    {
        return Enterprise::where('type', $type)->get();
    }
} 