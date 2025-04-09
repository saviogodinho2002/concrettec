<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConstructionRequest;
use App\Http\Requests\UpdateConstructionRequest;
use App\Models\Construction;
use App\Models\Enterprise;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class ConstructionController extends Controller
{
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
        $address = \App\Models\Address::create($request->validated()['address']);
        
        $construction = Construction::create([
            ...$request->safe()->except(['address']),
            'address_id' => $address->id
        ]);

        return redirect()->route('constructions.index')
            ->with('success', 'Obra criada com sucesso.');
    }

    public function show(Construction $construction)
    {
        return Inertia::render('Constructions/Show', [
            'construction' => $construction->load(['enterprise', 'address'])
        ]);
    }

    public function edit(Construction $construction)
    {
        return Inertia::render('Constructions/Form', [
            'construction' => $construction->load(['enterprise', 'address']),
            'enterprises' => Enterprise::where('type', 'construcao')->get()
        ]);
    }

    public function update(UpdateConstructionRequest $request, Construction $construction)
    {
        $construction->address->update($request->validated()['address']);
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
