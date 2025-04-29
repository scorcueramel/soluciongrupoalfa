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

  public function index(EvaluadosIndexRequest $request){

      $role = auth()->user()->roles->pluck('name')[0];

      $valuados = Personas::leftJoin('nacionalidades','nacionalidades.id','=','personas.nacionalidad_id')
        ->leftJoin('tipos_documentos','tipos_documentos.id','=','personas.tipo_documento_id')
        ->leftJoin('estados_civiles','estados_civiles.id','=','personas.estado_civil_id')
        ->leftJoin('generos','generos.id','=','personas.genero_id')
        ->leftJoin('distritos','distritos.id','=','personas.distrito_id')
        ->leftJoin('tipos_viviendas','tipos_viviendas.id','=','personas.tipo_vivienda_id')
        ->leftJoin('solicitudes_datos_personales','solicitudes_datos_personales.persona_id','=','personas.id')
        ->leftJoin('empresas','empresas.id','=','solicitudes_datos_personales.empresa_id')
        ->leftJoin('cargos','cargos.id','=','solicitudes_datos_personales.cargo_id')
        ->select('personas.id as personaId','personas.foto','personas.nombres','personas.apellido_paterno','personas.apellido_materno','personas.numero_documento','personas.fecha_nacimiento','personas.direccion','personas.otro_tipo_vivienda','personas.telefono','personas.email','personas.brevete','personas.lugar_nacimiento','nacionalidades.nacionalidad as nacionalidad','tipos_documentos.tipo_documento','estados_civiles.estado_civil','generos.genero','distritos.distrito','tipos_viviendas.tipo_vivienda','empresas.nombre_comercial','cargos.cargo');

      if($role != 'superadmin'){
        $solDatPer = SolicitudesDatosPersonales::where('usuario_id',\Auth::id())->get();
        $valuados->where('id',$solDatPer[0]->persona_id);
      }

      if ($request->has('search')) {
        $valuados->where('numero_documento', 'LIKE', '%' . $request->search . '%');
      }

      if ($request->has(['field', 'order'])) {
        $valuados->orderBy($request->field, $request->order);
      }

      return Inertia::render('Evaluado/Index',[
        'filters' => $request->all(['search', 'field', 'order']),
        'evaluados' => $valuados->paginate(10),
      ]);
    }

    public function getRelationships(string $personaid){
      $response = $this->evaluadosService->obtenerParentescos($personaid);
      $jsonDecode = json_decode($response->content());

      if ($jsonDecode->code === 200) {
        return back()->with('parentescos', $jsonDecode->parentescos);
      }

      if ($jsonDecode->code === 500) {
        return back()->with('error', $jsonDecode->message);
      }
    }
}
