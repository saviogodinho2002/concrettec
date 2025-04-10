<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConstructionRequest;
use App\Http\Requests\UpdateConstructionRequest;
use App\Models\Construction;
use App\Models\Enterprise;
use App\Services\CityService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class ConstructionController extends Controller
{
    protected $cityService;
    
    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }
    
    public static function middleware()
    {
        return [
            'auth',
            new Middleware('permission:construction-list', only: ['index']),
            new Middleware('permission:construction-create', only: ['create', 'store']),
            new Middleware('permission:construction-view', only: ['show']),
            new Middleware('permission:construction-edit', only: ['edit', 'update']),
            new Middleware('permission:construction-delete', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        return Inertia::render('Constructions/Index', [
            'constructions' => Construction::with(['enterprise', 'address'])->latest()->paginate(10)
        ]);
    }

    public function create()
    {
        return Inertia::render('Constructions/Form', [
            'enterprises' => Enterprise::where('type', 'construcao')->get()
        ]);
    }

    public function store(StoreConstructionRequest $request)
    {
        // Obtém os dados validados do request
        $validatedData = $request->validated();
        $addressData = $validatedData['address'];
        
        // Buscar ou criar a cidade usando o serviço
        $city = null;
        if (!empty($addressData['city']) && !empty($addressData['uf'])) {
            $city = $this->cityService->findOrCreate($addressData['city'], $addressData['uf']);
        }
        
        // Remove city e uf dos dados de endereço e adiciona city_id se disponível
        unset($addressData['city'], $addressData['uf']);
        if ($city) {
            $addressData['city_id'] = $city->id;
        }
        
        // Cria o endereço
        $address = \App\Models\Address::create($addressData);
        
        // Cria a construção
        $construction = Construction::create([
            ...$request->safe()->except(['address']),
            'address_id' => $address->id
        ]);

        return redirect()->route('constructions.index')
            ->with('success', 'Obra criada com sucesso.');
    }

    public function show(Construction $construction)
    {
        $construction->load(['enterprise', 'address', 'address.city']);
        
        return Inertia::render('Constructions/Show', [
            'construction' => $construction
        ]);
    }

    public function edit(Construction $construction)
    {
        $construction->load(['enterprise', 'address', 'address.city']);
        
        // Adicionar cidade e UF diretamente ao endereço para facilitar o trabalho do frontend
        if ($construction->address && $construction->address->city) {
            $construction->address->city_name = $construction->address->city->name;
            $construction->address->uf = $construction->address->city->uf;
        }
        
        return Inertia::render('Constructions/Form', [
            'construction' => $construction,
            'enterprises' => Enterprise::where('type', 'construcao')->get()
        ]);
    }

    public function update(UpdateConstructionRequest $request, Construction $construction)
    {
        // Obtém os dados validados do request
        $validatedData = $request->validated();
        $addressData = $validatedData['address'];
        
        // Buscar ou criar a cidade usando o serviço
        $city = null;
        if (!empty($addressData['city']) && !empty($addressData['uf'])) {
            $city = $this->cityService->findOrCreate($addressData['city'], $addressData['uf']);
        }
        
        // Remove city e uf dos dados de endereço e adiciona city_id se disponível
        unset($addressData['city'], $addressData['uf']);
        if ($city) {
            $addressData['city_id'] = $city->id;
        }
        
        // Atualiza o endereço
        $construction->address->update($addressData);
        
        // Atualiza a construção
        $construction->update($request->safe()->except(['address']));

        return redirect()->route('constructions.index')
            ->with('success', 'Obra atualizada com sucesso.');
    }

    public function destroy(Construction $construction)
    {
        $construction->delete();

        return redirect()->route('constructions.index')
            ->with('success', 'Obra excluída com sucesso.');
    }
}
