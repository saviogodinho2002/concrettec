<?php

namespace App\Services;

use App\Models\City;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CityService
{
    /**
     * Buscar cidades por nome
     * 
     * @param string $search
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchByName(string $search, int $limit = 10)
    {
        return City::where('name', 'like', "%{$search}%")
            ->orderBy('name')
            ->limit($limit)
            ->get(['id', 'name', 'uf']);
    }

    /**
     * Buscar ou criar uma cidade
     * 
     * @param string $name
     * @param string $uf
     * @return City
     */
    public function findOrCreate(string $name, string $uf): City
    {
        return DB::transaction(function () use ($name, $uf) {
            // Normaliza o nome da cidade e UF para evitar duplicações
            $name = mb_convert_case(trim($name), MB_CASE_TITLE);
            $uf = mb_strtoupper(trim($uf));
            
            // Primeiro procura uma cidade com exatamente o mesmo nome e UF
            $city = City::where('name', $name)
                ->where('uf', $uf)
                ->first();
            
            // Se não encontrar, cria uma nova
            if (!$city) {
                $city = City::create([
                    'name' => $name,
                    'uf' => $uf,
                    'codigo' => '', // Código precisa ser preenchido, mas não é fornecido pelo usuário
                ]);
            }
            
            return $city;
        });
    }

    /**
     * Busca cidades com base no termo de pesquisa
     *
     * @param string $search
     * @param int $limit
     * @return array
     */
    public function searchCities(string $search, int $limit = 15): array
    {
        if (empty($search) || strlen($search) < 3) {
            return [];
        }

        $cacheKey = 'city_search_' . md5($search . $limit);
        
        return Cache::remember($cacheKey, now()->addHours(24), function () use ($search, $limit) {
            try {
                // Primeiro busca no banco de dados local
                $localCities = $this->searchByName($search, $limit);
                
                // Se encontrou cidades suficientes no banco, retorna elas
                if ($localCities->count() > 0) {
                    return $localCities->map(function ($city) {
                        return [
                            'id' => $city->id,
                            'name' => $city->name,
                            'uf' => $city->uf,
                            'state_name' => null // O banco local não tem o nome do estado
                        ];
                    })->toArray();
                }
                
                // Se não encontrou cidades suficientes, busca na API do IBGE
                $response = Http::get("https://servicodados.ibge.gov.br/api/v1/localidades/municipios", [
                    'orderBy' => 'nome',
                ]);

                if ($response->successful()) {
                    $allCities = $response->json();
                    
                    // Filtra as cidades que contêm o termo de busca
                    $apiCities = collect($allCities)->filter(function ($city) use ($search) {
                        return stripos($city['nome'], $search) !== false;
                    })->take($limit)->map(function ($city) {
                        return [
                            'id' => $city['id'],
                            'name' => $city['nome'],
                            'uf' => $city['microrregiao']['mesorregiao']['UF']['sigla'],
                            'state_name' => $city['microrregiao']['mesorregiao']['UF']['nome'],
                        ];
                    })->values()->all();
                    
                    // Se temos resultados do banco local, combina com os da API
                    if ($localCities->count() > 0) {
                        $localResults = $localCities->map(function ($city) {
                            return [
                                'id' => $city->id,
                                'name' => $city->name,
                                'uf' => $city->uf,
                                'state_name' => null
                            ];
                        })->toArray();
                        
                        // Combina os resultados, removendo duplicados por nome+uf
                        $combinedResults = collect($localResults);
                        $existingKeys = $combinedResults->map(function ($city) {
                            return strtolower($city['name'] . '-' . $city['uf']);
                        })->toArray();
                        
                        foreach ($apiCities as $apiCity) {
                            $key = strtolower($apiCity['name'] . '-' . $apiCity['uf']);
                            if (!in_array($key, $existingKeys)) {
                                $combinedResults->push($apiCity);
                                $existingKeys[] = $key;
                            }
                        }
                        
                        return $combinedResults->take($limit)->values()->all();
                    }
                    
                    return $apiCities;
                }
                
                // Se a API falhar, retorna os resultados locais (mesmo que sejam poucos)
                if ($localCities->count() > 0) {
                    return $localCities->map(function ($city) {
                        return [
                            'id' => $city->id,
                            'name' => $city->name,
                            'uf' => $city->uf,
                            'state_name' => null
                        ];
                    })->toArray();
                }
                
                return [];
            } catch (\Exception $e) {
                report($e);
                
              
                return [];
            }
        });
    }

    /**
     * Obtém os detalhes de uma cidade específica pelo ID
     *
     * @param int $id
     * @return array|null
     */
    public function getCity(int $id): ?array
    {
        $cacheKey = 'city_' . $id;
        
        return Cache::remember($cacheKey, now()->addDays(7), function () use ($id) {
            try {
                $response = Http::get("https://servicodados.ibge.gov.br/api/v1/localidades/municipios/{$id}");
                
                if ($response->successful()) {
                    $city = $response->json();
                    
                    return [
                        'id' => $city['id'],
                        'name' => $city['nome'],
                        'state' => $city['microrregiao']['mesorregiao']['UF']['sigla'],
                        'state_name' => $city['microrregiao']['mesorregiao']['UF']['nome'],
                    ];
                }
                
                return null;
            } catch (\Exception $e) {
                report($e);
                return null;
            }
        });
    }
} 