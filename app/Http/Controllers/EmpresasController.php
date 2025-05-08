<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpresaIndexRequest;
use App\Http\Requests\EmpresaStoreRequest;
use App\Http\Requests\EmpresaUpdateRequest;
use App\Models\Empresas;
use App\Services\EmpresaService;
use Inertia\Inertia;

class EmpresasController extends Controller
{
  public function __construct(
    private EmpresaService $empresaService
  )
  {
    $this->middleware('permission:create empresa', ['only' => ['create', 'store']]);
    $this->middleware('permission:read empresa', ['only' => ['index', 'show']]);
    $this->middleware('permission:update empresa', ['only' => ['edit', 'update']]);
    $this->middleware('permission:delete empresa', ['only' => ['destroy', 'destroyBulk']]);
  }

  public function index(EmpresaIndexRequest $request)
  {
    $empresas = Empresas::query();

    if ($request->has('search')) {
      $empresas->where('nombre_comercial', 'LIKE', "%" . $request->search . "%");
      $empresas->orWhere('razon_social', 'LIKE', "%" . $request->search . "%");
    }
    if ($request->has(['field', 'order'])) {
      $empresas->orderBy($request->field, $request->order);
    }

    return Inertia::render('Empresa/Index', [
      'filters' => $request->all(['search', 'field', 'order']),
      'empresas' => $empresas->paginate(10),
    ]);
  }

  public function store(EmpresaStoreRequest $request){
    $response = $this->empresaService->store($request->data);
    $jsonDecode = json_decode($response->content());

    if ($jsonDecode->code === 200) {
      return back()->with('success', $jsonDecode->message);
    }

    if ($jsonDecode->code === 500) {
      return back()->with('error', $jsonDecode->message);
    }
  }

  public function update(EmpresaUpdateRequest $request){
    $response = $this->empresaService->update($request->data);
    $jsonDecode = json_decode($response->content());

    if ($jsonDecode->code === 200) {
      return back()->with('success', $jsonDecode->message);
    }

    if ($jsonDecode->code === 500) {
      return back()->with('error', $jsonDecode->message);
    }
  }

  public function destroy(Empresas $empresa){
    $response = $this->empresaService->destroy($empresa);
    $jsonDecode = json_decode($response->content());

    if ($jsonDecode->code === 200) {
      return back()->with('success', $jsonDecode->message);
    }

    if ($jsonDecode->code === 500) {
      return back()->with('error', $jsonDecode->message);
    }
  }
}
