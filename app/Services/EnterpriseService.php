<?php

namespace App\Services;

use App\Models\Enterprise;
use App\Enums\EnterpriseTypes;
use Illuminate\Support\Facades\DB;

class EnterpriseService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $enterprise = new Enterprise();
            $enterprise->name = $data['name'];
            $enterprise->fantasy_name = $data['fantasy_name'];
            $enterprise->cnpj = $data['cnpj'];
            $enterprise->email = $data['email'];
            $enterprise->price_cp = $data['price_cp'];
            $enterprise->type = EnterpriseTypes::fromLabel($data['type']);
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
            if (isset($data['fantasy_name'])) {
                $enterprise->fantasy_name = $data['fantasy_name'];
            }
            if (isset($data['cnpj'])) {
                $enterprise->cnpj = $data['cnpj'];
            }
            if (isset($data['email'])) {
                $enterprise->email = $data['email'];
            }
            if (isset($data['price_cp'])) {
                $enterprise->price_cp = $data['price_cp'];
            }
            if (isset($data['type'])) {
                $enterprise->type = EnterpriseTypes::fromLabel($data['type']);
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

    public function getByType(EnterpriseTypes $type)
    {
        return Enterprise::where('type', $type)->get();
    }
} 