<?php

namespace App\Http\Controllers;

use App\Http\Requests\CargoIndexRequest;
use App\Http\Requests\CargoStoreRequest;
use App\Http\Requests\CargoUpdateRequest;
use App\Models\Cargos;
use App\Services\CargoService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CargosController extends Controller
{
  public function __construct(
    private CargoService $cargoService,
  )
  {
    $this->middleware('permission:create cargo', ['only' => ['create', 'store']]);
    $this->middleware('permission:read cargo', ['only' => ['index', 'show']]);
    $this->middleware('permission:update cargo', ['only' => ['edit', 'update']]);
    $this->middleware('permission:delete cargo', ['only' => ['destroy', 'destroyBulk']]);
  }

  public function index(CargoIndexRequest $request)
  {
    $cargos = Cargos::query();

    if ($request->has('search')) {
      $cargos->where('cargo', 'LIKE', "%" . $request->search . "%");
    }
    if ($request->has(['field', 'order'])) {
      $cargos->orderBy($request->field, $request->order);
    }

    return Inertia::render('Cargo/Index', [
      'filters' => $request->all(['search', 'field', 'order']),
      'cargos' => $cargos->paginate(10),
    ]);
  }

  public function store(CargoStoreRequest $request){
    $response = $this->cargoService->store($request->data);
    $jsonDecode = json_decode($response->content());

    if ($jsonDecode->code === 200) {
      return back()->with('success', $jsonDecode->message);
    }

    if ($jsonDecode->code === 500) {
      return back()->with('error', $jsonDecode->message);
    }
  }

  public function update(CargoUpdateRequest $request){
    $response = $this->cargoService->update($request->data);
    $jsonDecode = json_decode($response->content());

    if ($jsonDecode->code === 200) {
      return back()->with('success', $jsonDecode->message);
    }

    if ($jsonDecode->code === 500) {
      return back()->with('error', $jsonDecode->message);
    }
  }

  public function destroy(Cargos $cargo){
    $response = $this->cargoService->destroy($cargo);
    $jsonDecode = json_decode($response->content());

    if ($jsonDecode->code === 200) {
      return back()->with('success', $jsonDecode->message);
    }

    if ($jsonDecode->code === 500) {
      return back()->with('error', $jsonDecode->message);
    }
  }
}
