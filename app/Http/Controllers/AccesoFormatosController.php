<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccesoFormatosIndexRequest;
use App\Http\Requests\AccesoFormatosStoreRequest;
use App\Http\Requests\AccesoFormatosUpdateRequest;
use App\Models\AccesoFormatos;

use App\Services\AccesoFormatosService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AccesoFormatosController extends Controller
{
  public function __construct(
    private AccesoFormatosService $accesoFormatosService
  )
  {
    $this->middleware('permission:create formato', ['only' => ['create', 'createAllowAccessToFormat']]);
    $this->middleware('permission:read formato', ['only' => ['index', 'allowAccessToFormat']]);
    $this->middleware('permission:update formato', ['only' => ['update', 'restrictAccessToFormat']]);
    $this->middleware('permission:delete formato', ['only' => ['destroy', 'deletedEvaluatedFormat']]);
  }

  public function index()
  {
    return Inertia::render('Formato/AccessFormat');
  }

  public function allowAccessToFormat(AccesoFormatosIndexRequest $request)
  {
    $role = auth()->user()->roles->pluck('name')[0];

    $evaluados = AccesoFormatos::query();
    $codigoUsuario = \Auth::user()->codigo_poligrafista;

    if($role != 'superadmin'){
      $evaluados->where('codigo_poligrafista','=',$codigoUsuario);
    }

    if ($request->has('search')) {
      $evaluados->where('documento_persona', 'LIKE', '%' . $request->search . '%');
      $evaluados->orWhere('codigo_poligrafista', 'LIKE', '%' . $request->search . '%');
      $evaluados->orWhere('fecha_examen', 'LIKE', '%' . $request->search . '%');
    }

    if ($request->has(['field', 'order'])) {
      $evaluados->orderBy($request->field, $request->order);
    }

    return Inertia::render('Formato/AccesoEvaluados',[
      'filters' => $request->all(['search', 'field', 'order']),
      'evaluados' => $evaluados->paginate(10),
    ]);
  }

  public function createAllowAccessToFormat(AccesoFormatosStoreRequest $request)
  {
    $accesoFormato = $this->accesoFormatosService->createAccessFormat($request->data);
    $jsonDecode = json_decode($accesoFormato->content());

    if ($jsonDecode->code === 200) {
      return back()->with('success', $jsonDecode->message);
    }

    if ($jsonDecode->code === 500) {
      return back()->with('error', $jsonDecode->message);
    }
  }

  public function restrictAccessToFormat(string $id){
    $response = $this->accesoFormatosService->allowAndDisallowFormat($id);
    $jsonDecode = json_decode($response->content());

    if ($jsonDecode->code === 200) {
      return back()->with('success', $jsonDecode->message);
    }

    if ($jsonDecode->code === 500) {
      return back()->with('error', $jsonDecode->message);
    }
  }

  public function update(AccesoFormatosUpdateRequest $request){
    $response = $this->accesoFormatosService->allowAccessUpdate($request->data);
    $jsonDecode = json_decode($response->content());

    if ($jsonDecode->code === 200) {
      return back()->with('success', $jsonDecode->message);
    }

    if ($jsonDecode->code === 500) {
      return back()->with('error', $jsonDecode->message);
    }
  }

  public function deletedEvaluatedFormat(string $id){
    $response = $this->accesoFormatosService->deleteAccessFormatEvaluated($id);
    $jsonDecode = json_decode($response->content());

    if ($jsonDecode->code === 200) {
      return back()->with('success', $jsonDecode->message);
    }

    if ($jsonDecode->code === 500) {
      return back()->with('error', $jsonDecode->message);
    }
  }
}
