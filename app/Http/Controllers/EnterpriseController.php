<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnterpriseRequest;
use App\Http\Requests\StoreEnterpriseRequest;
use App\Models\Enterprise;
use App\Services\EnterpriseService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class EnterpriseController extends Controller implements HasMiddleware
{
    protected $enterpriseService;

    public function __construct(EnterpriseService $enterpriseService)
    {
        $this->enterpriseService = $enterpriseService;
    }

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:enterprise-list', only: ['index']),
            new Middleware('permission:enterprise-create', only: ['create', 'store']),
            new Middleware('permission:enterprise-view', only: ['show']),
            new Middleware('permission:enterprise-edit', only: ['edit', 'update']),
            new Middleware('permission:enterprise-delete', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('master')) {
            $enterprises = $this->enterpriseService->getAll();
        } else {
            $enterprises = collect([$this->enterpriseService->getById($user->enterprise_id)]);
        }

        return Inertia::render('Enterprises/Index', [
            'enterprises' => $enterprises
        ]);
    }

    public function create()
    {
        return Inertia::render('Enterprises/Form');
    }

    public function store(StoreEnterpriseRequest $request)
    {
        $this->enterpriseService->create($request->validated());

        return redirect()
            ->route('enterprises.index')
            ->with('success', 'Empresa criada com sucesso');
    }

    public function show(Enterprise $enterprise)
    {
        return Inertia::render('Enterprises/Show', [
            'enterprise' => $enterprise
        ]);
    }

    public function edit(Enterprise $enterprise)
    {
        return Inertia::render('Enterprises/Form', [
            'enterprise' => $enterprise
        ]);
    }

    public function update(EnterpriseRequest $request, Enterprise $enterprise)
    {
        $this->enterpriseService->update($enterprise, $request->validated());

        return redirect()
            ->route('enterprises.index')
            ->with('success', 'Empresa atualizada com sucesso');
    }

    public function destroy(Enterprise $enterprise)
    {
        $this->enterpriseService->delete($enterprise);

        return redirect()
            ->route('enterprises.index')
            ->with('success', 'Empresa excluída com sucesso');
    }
}
