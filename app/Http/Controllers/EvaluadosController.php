<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluadosIndexRequest;
use App\Models\Personas;
use App\Models\SolicitudesDatosPersonales;
use App\Services\EvaluadosService;
use Inertia\Inertia;

class EvaluadosController extends Controller
{
  public function __construct(
    private EvaluadosService $evaluadosService,
  )
  {
  }

  public function index(EvaluadosIndexRequest $request)
  {
    $role = auth()->user()->roles->pluck('name')[0];

    $valuados = Personas::leftJoin('nacionalidades', 'nacionalidades.id', '=', 'personas.nacionalidad_id')
                ->leftJoin('tipos_documentos', 'tipos_documentos.id', '=', 'personas.tipo_documento_id')
                ->select('personas.id as personaId','personas.nombres','personas.apellido_paterno','personas.apellido_materno','personas.numero_documento', 'personas.informe_final','tipos_documentos.tipo_documento');

    if ($role != 'superadmin') {
      $solDatPer = SolicitudesDatosPersonales::where('usuario_id', \Auth::id())->get();
      $valuados->where('id', $solDatPer[0]->persona_id);
    }

    if ($request->has('search')) {
      $valuados->where('numero_documento', 'LIKE', '%' . $request->search . '%');
    }

    if ($request->has(['field', 'order'])) {
      $valuados->orderBy($request->field, $request->order);
    }

    return Inertia::render('Evaluado/Index', [
      'filters' => $request->all(['search', 'field', 'order']),
      'evaluados' => $valuados->paginate(10),
    ]);
  }

  public function getDetailsPerson(string $personaid)
  {
    $response = $this->evaluadosService->obtenerDetallePersona($personaid);
    $jsonDecode = json_decode($response->content());

    if ($jsonDecode->code === 200) {
      return response()->json($jsonDecode->data);
    }

    if ($jsonDecode->code === 500) {
      return response()->json(['error' => $jsonDecode->message]);
    }
  }
}
