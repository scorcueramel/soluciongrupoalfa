<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluadosIndexRequest;
use App\Models\DetalleExperenciaLaboral;
use App\Models\Distritos;
use App\Models\ExperienciasLaborales;
use App\Models\FormacionesAcademicasPersonas;
use App\Models\Generos;
use App\Models\Nacionalidades;
use App\Models\ParentescosPersonas;
use App\Models\Personas;;
use App\Models\SolicitudesDatosPersonales;
use App\Services\EvaluadosService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
      $persona = Personas::where('id', $id)->get()[0];
      $codigoYConteo = SolicitudesDatosPersonales::leftJoin('users', 'users.id', '=', 'solicitudes_datos_personales.usuario_id')->select('users.id as userid', 'users.codigo_poligrafista', 'solicitudes_datos_personales.fecha_solicitud', 'solicitudes_datos_personales.cantidad_evaluaciones')->where('persona_id', $id)->get();
      $codigoPoli = $codigoYConteo[0]->codigo_poligrafista . "" . $codigoYConteo[0]->cantidad_evaluaciones;
      $solDatPers = SolicitudesDatosPersonales::leftJoin('empresas', 'empresas.id', '=', 'solicitudes_datos_personales.empresa_id')->leftJoin('cargos', 'cargos.id', '=', 'solicitudes_datos_personales.cargo_id')->where('persona_id', $id)->get();
      $nacionalidad = Nacionalidades::find($persona->nacionalidad_id)->first();
      $genero = Generos::find($persona->genero_id)->first();
      $distrito = Distritos::find($persona->distrito_id)->first();
      $parentescos = ParentescosPersonas::leftJoin('tipos_parentescos', 'tipos_parentescos.id', '=', 'parentescos_personas.tipo_parentesco_id')->leftJoin('personas', 'personas.id', '=', 'parentescos_personas.persona_id')->where('persona_id', $persona->id)->select('tipos_parentescos.tipo_parentesco', 'parentescos_personas.nombres_apellidos', 'parentescos_personas.edad', 'parentescos_personas.ocupacion', 'parentescos_personas.mismo_inmueble')->get();
      $formAcademicas = FormacionesAcademicasPersonas::leftJoin('grados_instrucciones','grados_instrucciones.id','formaciones_academicas_personas.grado_instruccion_id')->where('persona_id', $persona->id)->select('grados_instrucciones.grado_instruccion','formaciones_academicas_personas.centro_estudio','formaciones_academicas_personas.especialidad_facultad','formaciones_academicas_personas.fecha_inicio','formaciones_academicas_personas.fecha_termino','formaciones_academicas_personas.situacion')->get();
      $expLaborales = ExperienciasLaborales::where('persona_id',$id)->get();
      $detaExpLaborales = DetalleExperenciaLaboral::where('persona_id',$id)->get()[0];

      $infoFinancieras = DB::select("select pif.tiene_prestamo, pif.monto_prestamo, eb.nombre_entidad as entidad_prestamo, pif.cuota_mensual_prestamo, pif.otro_ingreso, pif.monto_ingreso, pif.origen_ingreso, pif.tiene_propiedades, pif.detalle_propiedades, pif.reportado_centrar_riesgos, eb2.nombre_entidad as entidad_reporto, pif.motivo_reportado, pif.tiempo_mora, pif.monto_deuda from personas_informaciones_financieras pif left join entidades_bancarias eb on eb.id = pif.entidad_bancaria_prestamo_id left join entidades_bancarias eb2 on eb2.id = pif.entidad_bancaria_reporto_id where persona_id = ?", [$persona->id]);

      dd($infoFinancieras);

      $template = new \PhpOffice\PhpWord\TemplateProcessor('storage/formatos/formatouno.docx');
      $template->setValue('codpoli', $codigoPoli);
      $template->setValue('fechaeval', Carbon::parse($codigoYConteo[0]->fecha_solicitud)->format('d/m/Y'));
      $template->setValue('empresa', $solDatPers[0]->razon_social);
      $template->setValue('cargo', $solDatPers[0]->cargo);
      $template->setValue('nombres', "$persona->nombres $persona->apellido_paterno $persona->apellido_materno");
      $template->setValue('nacionalidad', $nacionalidad->nacionalidad);
      if ($persona->tipo_documento_id === 1) {
        $template->setValue('d', '[X]');
        $template->setValue('p', '◻');
        $template->setValue('c', '◻');
        $template->setValue('pt', '◻');
        $template->setValue('ce', '◻');
        $template->setValue('cr', '◻');
      }
      if ($persona->tipo_documento_id === 2) {
        $template->setValue('d', '◻');
        $template->setValue('p', '[X]');
        $template->setValue('c', '◻');
        $template->setValue('pt', '◻');
        $template->setValue('ce', '◻');
        $template->setValue('cr', '◻');
      }
      if ($persona->tipo_documento_id === 3) {
        $template->setValue('d', '◻');
        $template->setValue('p', '◻');
        $template->setValue('c', '[X]');
        $template->setValue('pt', '◻');
        $template->setValue('ce', '◻');
        $template->setValue('cr', '◻');
      }
      if ($persona->tipo_documento_id === 4) {
        $template->setValue('d', '◻');
        $template->setValue('p', '◻');
        $template->setValue('c', '◻');
        $template->setValue('pt', '[X]');
        $template->setValue('ce', '◻');
        $template->setValue('cr', '◻');
      }
      if ($persona->tipo_documento_id === 5) {
        $template->setValue('d', '◻');
        $template->setValue('p', '◻');
        $template->setValue('c', '◻');
        $template->setValue('pt', '◻');
        $template->setValue('ce', '[X]');
        $template->setValue('cr', '◻');
      }
      if ($persona->tipo_documento_id === 6) {
        $template->setValue('d', '◻');
        $template->setValue('p', '◻');
        $template->setValue('c', '◻');
        $template->setValue('pt', '◻');
        $template->setValue('ce', '◻');
        $template->setValue('cr', '[X]');
      }
      $template->setValue('documento', $persona->numero_documento);
      $template->setValue('fecha_nacimiento', Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y'));
      $template->setValue('lugar_nacimiento', $persona->lugar_nacimiento);
      if ($persona->estado_civil_id === 1) {
        $template->setValue('so', '[X]');
        $template->setValue('ca', '◻');
        $template->setValue('vi', '◻');
        $template->setValue('di', '◻');
        $template->setValue('co', '◻');
      }
      if ($persona->estado_civil_id === 2) {
        $template->setValue('so', '◻');
        $template->setValue('ca', '[X]');
        $template->setValue('vi', '◻');
        $template->setValue('di', '◻');
        $template->setValue('co', '◻');
      }
      if ($persona->estado_civil_id === 3) {
        $template->setValue('so', '◻');
        $template->setValue('ca', '◻');
        $template->setValue('vi', '[X]');
        $template->setValue('di', '◻');
        $template->setValue('co', '◻');
      }
      if ($persona->estado_civil_id === 4) {
        $template->setValue('so', '◻');
        $template->setValue('ca', '◻');
        $template->setValue('vi', '◻');
        $template->setValue('di', '[X]');
        $template->setValue('co', '◻');
      }
      if ($persona->estado_civil_id === 5) {
        $template->setValue('so', '◻');
        $template->setValue('ca', '◻');
        $template->setValue('vi', '◻');
        $template->setValue('di', '◻');
        $template->setValue('co', '[X]');
      }
      $template->setValue('genero', $genero->genero);
      $template->setValue('direccion', $persona->direccion);
      $template->setValue('distrito', $distrito->distrito);
      if ($persona->tipo_vivienda_id === 1) {
        $template->setValue('pr', '[X]');
        $template->setValue('al', '◻');
        $template->setValue('pa', '◻');
        $template->setValue('fa', '◻');
        $template->setValue('ot', 'No registra');
      }
      if ($persona->tipo_vivienda_id === 2) {
        $template->setValue('pr', '◻');
        $template->setValue('al', '[X]');
        $template->setValue('pa', '◻');
        $template->setValue('fa', '◻');
        $template->setValue('ot', 'No registra');
      }
      if ($persona->tipo_vivienda_id === 3) {
        $template->setValue('pr', '◻');
        $template->setValue('al', '◻');
        $template->setValue('pa', '[X]');
        $template->setValue('fa', '◻');
        $template->setValue('ot', 'No registra');
      }
      if ($persona->tipo_vivienda_id === 4) {
        $template->setValue('pr', '◻');
        $template->setValue('al', '◻');
        $template->setValue('pa', '◻');
        $template->setValue('fa', '[X]');
        $template->setValue('ot', 'No registra');
      }
      if ($persona->tipo_vivienda_id === 5) {
        $template->setValue('pr', '◻');
        $template->setValue('al', '◻');
        $template->setValue('pa', '◻');
        $template->setValue('fa', '◻');
        $template->setValue('ot', $persona->otro_tipo_vivienda);
      }
      $template->setValue('telefono', $persona->telefono);
      $template->setValue('email', $persona->email);
      $template->setValue('brevete', $persona->brevete ?? 'No registra');

      $template->cloneRow('parentesco', count($parentescos));

      foreach ($parentescos as $index => $parentesco) {
        $i = $index + 1;
        $template->setValue("parentesco#{$i}", $parentesco->tipo_parentesco);
        $template->setValue("nombres_apellidos#{$i}", $parentesco->nombres_apellidos);
        $template->setValue("edad#{$i}", $parentesco->edad);
        $template->setValue("ocupacion#{$i}", $parentesco->ocupacion);
        if ($parentesco->mismo_inmueble) {
          $template->setValue("ms#{$i}", 'SI[X]');
          $template->setValue("mn#{$i}", 'NO◻');
        }
        else {
          $template->setValue("ms#{$i}", 'SI◻');
          $template->setValue("mn#{$i}", 'NO[X]');
        }
      }

      $template->cloneRow('grado', count($formAcademicas));

      foreach ($formAcademicas as $index => $academica) {
        $i = $index + 1;
        $template->setValue("grado#{$i}", $academica->grado_instruccion);
        $template->setValue("centro_estudios#{$i}", $academica->centro_estudio);
        $template->setValue("facultad#{$i}", $academica->especialidad_facultad);
        $template->setValue("inicio#{$i}", Carbon::parse($academica->fecha_inicio)->format('d/m/Y'));
        $template->setValue("termino#{$i}", Carbon::parse($academica->fecha_termino)->format('d/m/Y'));
        if ($academica->situacion) {
          $template->setValue("com#{$i}", 'SI[X]');
          $template->setValue("inc#{$i}", 'NO◻');
        }
        else {
          $template->setValue("com#{$i}", 'SI◻');
          $template->setValue("inc#{$i}", 'NO[X]');
        }
      }

      $template->cloneRow('empresa_exp', count($expLaborales));

      foreach ($expLaborales as $index => $experiencia) {
        $i = $index + 1;
        $template->setValue("empresa_exp#{$i}", $experiencia->empresa);
        $template->setValue("ingreso_exp#{$i}", $experiencia->fecha_ingreso);
        $template->setValue("salida_exp#{$i}", $experiencia->fecha_salida);
        $template->setValue("sueldo_exp#{$i}", $experiencia->sueldo_percibido);
        $template->setValue("cargo_exp#{$i}", $experiencia->cargo_desempenado);
        $template->setValue("motivo_exp#{$i}", $experiencia->motivo_salida);
      }


      if($detaExpLaborales->recibio_amonestaciones){
        $template->setValue("amonestaciones", "SI[X] NO◻");
      }
      else{
        $template->setValue("amonestaciones", "SI◻ NO[X]");
      }

      if($detaExpLaborales->solicitud_renuncia){
        $template->setValue("renuncia", "SI[X] NO◻");
      }
      else{
        $template->setValue("renuncia", "SI◻ NO[X]");
      }

      $template->setValue("explica_experiencia",$detaExpLaborales->explicacion ?? 'No registra');

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
