<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConstructionRequest;
use App\Models\Construction;
use App\Services\ConstructionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class ConstructionController extends Controller
{
    protected $constructionService;

    public function __construct(ConstructionService $constructionService)
    {
        $this->constructionService = $constructionService;
    }

    public static function middleware(): array
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
        $user = $request->user();
        
        if ($user->hasRole('master')) {
            $constructions = Construction::all();
        } else {
            $constructions = $this->constructionService->getByEnterprise($user->enterprise_id);
        }

        return view('constructions.index', compact('constructions'));
    }

    public function create()
    {
        return view('constructions.create');
    }

    public function store(ConstructionRequest $request)
    {
        $this->constructionService->create($request->validated());
        return redirect()->route('constructions.index')->with('success', 'Obra criada com sucesso');
    }

    public function show(Construction $construction)
    {
        return view('constructions.show', compact('construction'));
    }

    public function edit(Construction $construction)
    {
        return view('constructions.edit', compact('construction'));
    }

    public function update(ConstructionRequest $request, Construction $construction)
    {
        $this->constructionService->update($construction, $request->validated());
        return redirect()->route('constructions.index')->with('success', 'Obra atualizada com sucesso');
    }

    public function destroy(Construction $construction)
    {
        $this->constructionService->delete($construction);
        return redirect()->route('constructions.index')->with('success', 'Obra excluída com sucesso');
    }
}
