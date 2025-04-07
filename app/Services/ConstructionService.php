<?php

namespace App\Services;

use App\Models\Construction;
use Illuminate\Support\Facades\DB;

class ConstructionService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $construction = new Construction();
            $construction->name = $data['name'];
            $construction->enterprise_id = $data['enterprise_id'];
            $construction->address_id = $data['address_id'];
            $construction->save();

            return $construction;
        });
    }

    public function update(Construction $construction, array $data)
    {
        return DB::transaction(function () use ($construction, $data) {
            if (isset($data['name'])) {
                $construction->name = $data['name'];
            }
            if (isset($data['enterprise_id'])) {
                $construction->enterprise_id = $data['enterprise_id'];
            }
            if (isset($data['address_id'])) {
                $construction->address_id = $data['address_id'];
            }

            $construction->save();
            return $construction;
        });
    }

    public function delete(Construction $construction)
    {
        return DB::transaction(function () use ($construction) {
            return $construction->delete();
        });
    }

    public function getById($id)
    {
        return Construction::findOrFail($id);
    }

    public function getByEnterprise($enterpriseId)
    {
        return Construction::where('enterprise_id', $enterpriseId)->get();
    }
} 