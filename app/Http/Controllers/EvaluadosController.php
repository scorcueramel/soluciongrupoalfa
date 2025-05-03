<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluadosIndexRequest;
use App\Models\AccesoFormatos;
use App\Models\AcercaPoligrafos;
use App\Models\Distritos;
use App\Models\EstadosCiviles;
use App\Models\Generos;
use App\Models\Nacionalidades;
use App\Models\Personas;
use App\Models\SolicitudesDatosPersonales;
use App\Models\TiposViviendas;
use App\Services\EvaluadosService;
use Carbon\Carbon;
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
      ->select('personas.id as personaId', 'personas.nombres', 'personas.apellido_paterno', 'personas.apellido_materno', 'personas.numero_documento', 'personas.informe_final', 'tipos_documentos.tipo_documento');

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

  public function exportFormatOne(string $id)
  {
    try {
      $persona = Personas::find($id)->first();

      $codigoYConteo = SolicitudesDatosPersonales::leftJoin('users', 'users.id', '=', 'solicitudes_datos_personales.usuario_id')->select('users.id as userid','users.codigo_poligrafista','solicitudes_datos_personales.fecha_solicitud','solicitudes_datos_personales.cantidad_evaluaciones')->get();

      $codigoPoli = $codigoYConteo[0]->codigo_poligrafista."".$codigoYConteo[0]->cantidad_evaluaciones;

      $solDatPers = SolicitudesDatosPersonales::leftJoin('empresas','empresas.id','=','solicitudes_datos_personales.empresa_id')->leftJoin('cargos','cargos.id','=','solicitudes_datos_personales.cargo_id')->where('persona_id',$id)->get();
      $nacionalidad = Nacionalidades::find($persona->nacionalidad_id)->first();
      $genero = Generos::find($persona->genero_id)->first();
      $distrito = Distritos::find($persona->distrito_id)->first();

      $template = new \PhpOffice\PhpWord\TemplateProcessor('storage/formatos/formatouno.docx');
      $template->setValue('codpoli',$codigoPoli);
      $template->setValue('fechaeval', Carbon::parse($codigoYConteo[0]->fecha_solicitud)->format('d/m/Y'));
      $template->setValue('empresa', $solDatPers[0]->razon_social);
      $template->setValue('cargo', $solDatPers[0]->cargo);
      $template->setValue('nombres', "$persona->nombres $persona->apellido_paterno $persona->apellido_materno");
      $template->setValue('nacionalidad', $nacionalidad->nacionalidad);
      if($persona->tipo_documento_id === 1){
        $template->setValue('d', '[X]');
        $template->setValue('p', '[ ]');
        $template->setValue('c', '[ ]');
        $template->setValue('pt', '[ ]');
        $template->setValue('ce', '[ ]');
        $template->setValue('cr', '[ ]');
      }
      if($persona->tipo_documento_id === 2){
        $template->setValue('d', '[ ]');
        $template->setValue('p', '[X]');
        $template->setValue('c', '[ ]');
        $template->setValue('pt', '[ ]');
        $template->setValue('ce', '[ ]');
        $template->setValue('cr', '[ ]');
      }
      if($persona->tipo_documento_id === 3){
        $template->setValue('d', '[ ]');
        $template->setValue('p', '[ ]');
        $template->setValue('c', '[X]');
        $template->setValue('pt', '[ ]');
        $template->setValue('ce', '[ ]');
        $template->setValue('cr', '[ ]');
      }
      if($persona->tipo_documento_id === 4){
        $template->setValue('d', '[ ]');
        $template->setValue('p', '[ ]');
        $template->setValue('c', '[ ]');
        $template->setValue('pt', '[X]');
        $template->setValue('ce', '[ ]');
        $template->setValue('cr', '[ ]');
      }
      if($persona->tipo_documento_id === 5){
        $template->setValue('d', '[ ]');
        $template->setValue('p', '[ ]');
        $template->setValue('c', '[ ]');
        $template->setValue('pt', '[ ]');
        $template->setValue('ce', '[X]');
        $template->setValue('cr', '[ ]');
      }
      if($persona->tipo_documento_id === 6){
        $template->setValue('d', '[ ]');
        $template->setValue('p', '[ ]');
        $template->setValue('c', '[ ]');
        $template->setValue('pt', '[ ]');
        $template->setValue('ce', '[ ]');
        $template->setValue('cr', '[X]');
      }
      $template->setValue('documento', $persona->numero_documento);
      $template->setValue('fecha_nacimiento', Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y'));
      $template->setValue('lugar_nacimiento', $persona->lugar_nacimiento);
      if($persona->estado_civil_id === 1){
        $template->setValue('so', '[X]');
        $template->setValue('ca', '[ ]');
        $template->setValue('vi', '[ ]');
        $template->setValue('di', '[ ]');
        $template->setValue('co', '[ ]');
      }
      if($persona->estado_civil_id === 2){
        $template->setValue('so', '[ ]');
        $template->setValue('ca', '[X]');
        $template->setValue('vi', '[ ]');
        $template->setValue('di', '[ ]');
        $template->setValue('co', '[ ]');
      }
      if($persona->estado_civil_id === 3){
        $template->setValue('so', '[ ]');
        $template->setValue('ca', '[ ]');
        $template->setValue('vi', '[X]');
        $template->setValue('di', '[ ]');
        $template->setValue('co', '[ ]');
      }
      if($persona->estado_civil_id === 4){
        $template->setValue('so', '[ ]');
        $template->setValue('ca', '[ ]');
        $template->setValue('vi', '[ ]');
        $template->setValue('di', '[X]');
        $template->setValue('co', '[ ]');
      }
      if($persona->estado_civil_id === 5){
        $template->setValue('so', '[ ]');
        $template->setValue('ca', '[ ]');
        $template->setValue('vi', '[ ]');
        $template->setValue('di', '[ ]');
        $template->setValue('co', '[X]');
      }
      $template->setValue('genero', $genero->genero);
      $template->setValue('direccion', $persona->direccion);
      $template->setValue('distrito', $distrito->distrito);
      if($persona->tipo_vivienda_id === 1){
        $template->setValue('pr', '[X]');
        $template->setValue('al', '[ ]');
        $template->setValue('pa', '[ ]');
        $template->setValue('fa', '[ ]');
        $template->setValue('ot', 'No registra');
      }
      if($persona->tipo_vivienda_id === 2){
        $template->setValue('pr', '[ ]');
        $template->setValue('al', '[X]');
        $template->setValue('pa', '[ ]');
        $template->setValue('fa', '[ ]');
        $template->setValue('ot', 'No registra');
      }
      if($persona->tipo_vivienda_id === 3){
        $template->setValue('pr', '[ ]');
        $template->setValue('al', '[ ]');
        $template->setValue('pa', '[X]');
        $template->setValue('fa', '[ ]');
        $template->setValue('ot', 'No registra');
      }
      if($persona->tipo_vivienda_id === 4){
        $template->setValue('pr', '[ ]');
        $template->setValue('al', '[ ]');
        $template->setValue('pa', '[ ]');
        $template->setValue('fa', '[X]');
        $template->setValue('ot', 'No registra');
      }
      if($persona->tipo_vivienda_id === 5){
        $template->setValue('pr', '[ ]');
        $template->setValue('al', '[ ]');
        $template->setValue('pa', '[ ]');
        $template->setValue('fa', '[ ]');
        $template->setValue('ot', $persona->otro_tipo_vivienda);
      }
      $template->setValue('telefono', $persona->telefono);
      $template->setValue('email', $persona->email);
      $template->setValue('brevete', $persona->brevete ?? 'No registra');

      $fileName = "Formato Uno - $persona->nombres.docx";

      $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
      $template->saveAs($tempFile);

      $headers = [
        'Content-Type' => 'application/octet-stream',
      ];

      return response()->download($tempFile, $fileName, $headers)->deleteFileAfterSend(true);

    } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
      return back()->with('error', $e->getCode());
    }
  }
}
