<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnterpriseRequest;
use App\Models\Enterprise;
use App\Services\EnterpriseService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class EnterpriseController extends Controller
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

        if (is_null($user->enterprise_id)) {
            $enterprises = $this->enterpriseService->getAll();
        } else {
            $enterprises = collect([$this->enterpriseService->getById($user->enterprise_id)]);
        }

        return view('enterprises.index', compact('enterprises'));
    }

    public function create()
    {
        return view('enterprises.create');
    }

    public function store(EnterpriseRequest $request)
    {
        $this->enterpriseService->create($request->validated());
        return redirect()->route('enterprises.index')->with('success', 'Empresa criada com sucesso');
    }

    public function show(Enterprise $enterprise)
    {
        return view('enterprises.show', compact('enterprise'));
    }

    public function edit(Enterprise $enterprise)
    {
        return view('enterprises.edit', compact('enterprise'));
    }

    public function update(EnterpriseRequest $request, Enterprise $enterprise)
    {
        $this->enterpriseService->update($enterprise, $request->validated());
        return redirect()->route('enterprises.index')->with('success', 'Empresa atualizada com sucesso');
    }

    public function destroy(Enterprise $enterprise)
    {
        $this->enterpriseService->delete($enterprise);
        return redirect()->route('enterprises.index')->with('success', 'Empresa excluída com sucesso');
    }
}
