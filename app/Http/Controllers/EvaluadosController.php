<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluadosIndexRequest;
use App\Models\AcercaPoligrafos;
use App\Models\ComisionesDelitos;
use App\Models\ConsentimientosExamenes;
use App\Models\ConsumosBebidasAlcoholicas;
use App\Models\DetalleExperenciaLaboral;
use App\Models\Distritos;
use App\Models\ExperienciasLaborales;
use App\Models\FormacionesAcademicasPersonas;
use App\Models\Generos;
use App\Models\ImplicacionesDrogas;
use App\Models\MotivacionesPostulaciones;
use App\Models\Nacionalidades;
use App\Models\ParentescosPersonas;
use App\Models\Personas;
use App\Models\PersonasMargenLeyes;
use App\Models\SolicitudesDatosPersonales;
use App\Services\EvaluadosService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
    $valuados = Personas::leftJoin('nacionalidades', 'nacionalidades.id', '=', 'personas.nacionalidad_id')
      ->leftJoin('tipos_documentos', 'tipos_documentos.id', '=', 'personas.tipo_documento_id')
      ->leftJoin('solicitudes_datos_personales', 'solicitudes_datos_personales.persona_id', '=', 'personas.id')
      ->select('personas.id as personaId', 'personas.nombres', 'personas.apellido_paterno', 'personas.apellido_materno', 'personas.numero_documento', 'personas.informe_final', 'tipos_documentos.tipo_documento');

    if ($request->has('search')) {
      $valuados->where('solicitudes_datos_personales.usuario_id',\Auth::id())->where('numero_documento', 'LIKE', '%' . $request->search . '%');
    }

    if ($request->has(['field', 'order'])) {
      $valuados->orderBy($request->field, $request->order);
    }

    return Inertia::render('Evaluado/Index', [
      'filters' => $request->all(['search', 'field', 'order']),
      'evaluados' => $valuados->where('solicitudes_datos_personales.usuario_id',\Auth::id())->paginate(10),
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
      $formAcademicas = FormacionesAcademicasPersonas::leftJoin('grados_instrucciones', 'grados_instrucciones.id', 'formaciones_academicas_personas.grado_instruccion_id')->where('persona_id', $persona->id)->select('grados_instrucciones.grado_instruccion', 'formaciones_academicas_personas.centro_estudio', 'formaciones_academicas_personas.especialidad_facultad', 'formaciones_academicas_personas.fecha_inicio', 'formaciones_academicas_personas.fecha_termino', 'formaciones_academicas_personas.situacion')->get();
      $expLaborales = ExperienciasLaborales::where('persona_id', $id)->get();
      $detaExpLaborales = DetalleExperenciaLaboral::where('persona_id', $id)->get()[0];
      $infoFinancieras = DB::select("select pif.tiene_prestamo, pif.monto_prestamo, eb.nombre_entidad as entidad_prestamo, pif.cuota_mensual_prestamo, pif.otro_ingreso, pif.monto_ingreso, pif.origen_ingreso, pif.tiene_propiedades, pif.detalle_propiedades, pif.reportado_centrar_riesgos, eb2.nombre_entidad as entidad_reporto, pif.motivo_reportado, pif.tiempo_mora, pif.monto_deuda from personas_informaciones_financieras pif left join entidades_bancarias eb on eb.id = pif.entidad_bancaria_prestamo_id left join entidades_bancarias eb2 on eb2.id = pif.entidad_bancaria_reporto_id where persona_id = ?", [$persona->id])[0];
      $consumoBebidas = ConsumosBebidasAlcoholicas::where('persona_id', $id)->get()[0];
      $dograsIlegales = ImplicacionesDrogas::where('persona_id', $id)->get()[0];
      $comisionDelitos = ComisionesDelitos::where('persona_id', $id)->get()[0];
      $personasMargenLeyes = PersonasMargenLeyes::where('persona_id', $id)->get()[0];
      $motivacionesPostulaciones = MotivacionesPostulaciones::where('persona_id', $id)->get()[0];
      $acercaPoligrafo = AcercaPoligrafos::where('persona_id', $id)->get()[0];
      $consentimientos = ConsentimientosExamenes::where('persona_id', $id)->get()[0];

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
        } else {
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
          $template->setValue("com#{$i}", 'COMPLETO[X]');
          $template->setValue("inc#{$i}", 'INCOMPLETO◻');
        } else {
          $template->setValue("com#{$i}", 'COMPLETO◻');
          $template->setValue("inc#{$i}", 'INCOMPLETO[X]');
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
      if ($detaExpLaborales->recibio_amonestaciones) {
        $template->setValue("amonestaciones", "SI[X] NO◻");
      } else {
        $template->setValue("amonestaciones", "SI◻ NO[X]");
      }
      if ($detaExpLaborales->solicitud_renuncia) {
        $template->setValue("renuncia", "SI[X] NO◻");
      } else {
        $template->setValue("renuncia", "SI◻ NO[X]");
      }
      $template->setValue("explica_experiencia", $detaExpLaborales->explicacion ?? 'No registra');
      if ($infoFinancieras->tiene_prestamo) {
        $template->setValue("tienepres", "SI[X] NO◻");
      } else {
        $template->setValue("tienepres", "SI◻ NO[X]");
      }
      $template->setValue('montdeu', $infoFinancieras->monto_prestamo);
      $template->setValue('entdeu', $infoFinancieras->entidad_prestamo ?? 'No registra');
      $template->setValue('cuotdeu', $infoFinancieras->cuota_mensual_prestamo);
      if ($infoFinancieras->otro_ingreso) {
        $template->setValue("otroing", "SI[X] NO◻");
      } else {
        $template->setValue("otroing", "SI◻ NO[X]");
      }
      $template->setValue('monting', $infoFinancieras->monto_ingreso);
      $template->setValue('origotring', $infoFinancieras->origen_ingreso ?? 'No registra');
      if ($infoFinancieras->tiene_propiedades) {
        $template->setValue("tieneprop", "SI[X] NO◻");
      } else {
        $template->setValue("tieneprop", "SI◻ NO[X]");
      }
      $template->setValue('detalle', $infoFinancieras->detalle_propiedades ?? 'No registra');
      if ($infoFinancieras->reportado_centrar_riesgos) {
        $template->setValue("reportado", "SI[X] NO◻");
      } else {
        $template->setValue("reportado", "SI◻ NO[X]");
      }
      $template->setValue('entidadreporta', $infoFinancieras->entidad_reporto ?? 'No registra');
      $template->setValue('motirepo', $infoFinancieras->motivo_reportado ?? 'No registra');
      $template->setValue('tiempmora', $infoFinancieras->tiempo_mora ?? 'No registra');
      $template->setValue('montodeuda', $infoFinancieras->monto_deuda ?? 'No registra');
      $template->setValue('frecuencia_consumo', $consumoBebidas->frecuencia_consumo ?? 'No registra');
      $template->setValue('bebidas_consume', $consumoBebidas->bebidas_consume ?? 'No registra');
      if ($consumoBebidas->tratamiento_alcoholismo) {
        $template->setValue("tratamiento", "SI[X] NO◻");
      } else {
        $template->setValue("tratamiento", "SI◻ NO[X]");
      }
      if ($consumoBebidas->trabajo_ebrio) {
        $template->setValue("hebriotrabajar", "SI[X] NO◻");
      } else {
        $template->setValue("hebriotrabajar", "SI◻ NO[X]");
      }
      $template->setValue('explicacionbebidas', $consumoBebidas->explicacion ?? 'No registra');
      if ($dograsIlegales->marihuana) {
        $template->setValue("marihuana", "SI[X] NO◻");
      } else {
        $template->setValue("marihuana", "SI◻ NO[X]");
      }
      if ($dograsIlegales->pbc) {
        $template->setValue("pbc", "SI[X] NO◻");
      } else {
        $template->setValue("pbc", "SI◻ NO[X]");
      }
      if ($dograsIlegales->cocaina) {
        $template->setValue("coca", "SI[X] NO◻");
      } else {
        $template->setValue("coca", "SI◻ NO[X]");
      }
      if ($dograsIlegales->heroina) {
        $template->setValue("heroina", "SI[X] NO◻");
      } else {
        $template->setValue("heroina", "SI◻ NO[X]");
      }
      if ($dograsIlegales->lsd) {
        $template->setValue("lcd", "SI[X] NO◻");
      } else {
        $template->setValue("lcd", "SI◻ NO[X]");
      }
      if ($dograsIlegales->extasis) {
        $template->setValue("extasis", "SI[X] NO◻");
      } else {
        $template->setValue("extasis", "SI◻ NO[X]");
      }
      if ($dograsIlegales->ultimo_consumo === 'dias') {
        $template->setValue("ult_consumo", "Días [X]	Meses ◻  Años ◻");
      } elseif ($dograsIlegales->ultimo_consumo === 'meses') {
        $template->setValue("ult_consumo", "Días ◻	Meses [X]  Años ◻");
      } elseif ($dograsIlegales->ultimo_consumo === 'anio') {
        $template->setValue("ult_consumo", "Días ◻	Meses ◻  Años [X]");
      } else {
        $template->setValue("ult_consumo", "Días ◻	Meses ◻  Años ◻");
      }
      $template->setValue('cantidad', $dograsIlegales->tiempo_transcurrido);
      if ($dograsIlegales->familiar_consumidor) {
        $template->setValue("familiar_adicto", "SI[X] NO◻");
      } else {
        $template->setValue("familiar_adicto", "SI◻ NO[X]");
      }
      if ($comisionDelitos->robo_hurto_fraude) {
        $template->setValue("robo", "SI[X] NO◻");
      } else {
        $template->setValue("robo", "SI◻ NO[X]");
      }
      if ($comisionDelitos->homicidio_involuntario) {
        $template->setValue("homicidio", "SI[X] NO◻");
      } else {
        $template->setValue("homicidio", "SI◻ NO[X]");
      }
      if ($comisionDelitos->asalto) {
        $template->setValue("asalto", "SI[X] NO◻");
      } else {
        $template->setValue("asalto", "SI◻ NO[X]");
      }
      if ($comisionDelitos->danio_fisico_individuo) {
        $template->setValue("causar_danio", "SI[X] NO◻");
      } else {
        $template->setValue("causar_danio", "SI◻ NO[X]");
      }
      if ($comisionDelitos->secuestro) {
        $template->setValue("secuestro", "SI[X] NO◻");
      } else {
        $template->setValue("secuestro", "SI◻ NO[X]");
      }
      if ($comisionDelitos->violacion) {
        $template->setValue("violacion", "SI[X] NO◻");
      } else {
        $template->setValue("violacion", "SI◻ NO[X]");
      }
      if ($comisionDelitos->muerte_lesion_otra_persona) {
        $template->setValue("causar_muerte", "SI[X] NO◻");
      } else {
        $template->setValue("causar_muerte", "SI◻ NO[X]");
      }
      if ($comisionDelitos->trafico_ilicito_drogas) {
        $template->setValue("trafico_drog", "SI[X] NO◻");
      } else {
        $template->setValue("trafico_drog", "SI◻ NO[X]");
      }
      if ($comisionDelitos->trafico_armas) {
        $template->setValue("trafico_armas", "SI[X] NO◻");
      } else {
        $template->setValue("trafico_armas", "SI◻ NO[X]");
      }
      if ($comisionDelitos->otros_delitos) {
        $template->setValue("conspiracion", "SI[X] NO◻");
      } else {
        $template->setValue("conspiracion", "SI◻ NO[X]");
      }
      if ($comisionDelitos->explique_otros) {
        $template->setValue("respuesta_afirmativa", "SI[X] NO◻");
      } else {
        $template->setValue("respuesta_afirmativa", "SI◻ NO[X]");
      }
      if ($personasMargenLeyes->pandilleros) {
        $template->setValue("pandilleros", "SI[X] NO◻");
      } else {
        $template->setValue("pandilleros", "SI◻ NO[X]");
      }
      if ($personasMargenLeyes->sicarios) {
        $template->setValue("sicarios", "SI[X] NO◻");
      } else {
        $template->setValue("sicarios", "SI◻ NO[X]");
      }
      if ($personasMargenLeyes->asaltantes) {
        $template->setValue("asaltantes", "SI[X] NO◻");
      } else {
        $template->setValue("asaltantes", "SI◻ NO[X]");
      }
      if ($personasMargenLeyes->traficantes_drogas) {
        $template->setValue("drogas", "SI[X] NO◻");
      } else {
        $template->setValue("drogas", "SI◻ NO[X]");
      }
      if ($personasMargenLeyes->estafadores) {
        $template->setValue("estafadores", "SI[X] NO◻");
      } else {
        $template->setValue("estafadores", "SI◻ NO[X]");
      }
      if ($personasMargenLeyes->terroristas) {
        $template->setValue("terroristas", "SI[X] NO◻");
      } else {
        $template->setValue("terroristas", "SI◻ NO[X]");
      }
      if ($personasMargenLeyes->secustradores) {
        $template->setValue("secuestros", "SI[X] NO◻");
      } else {
        $template->setValue("secuestros", "SI◻ NO[X]");
      }
      if ($personasMargenLeyes->extorsionadores) {
        $template->setValue("extorsionadores", "SI[X] NO◻");
      } else {
        $template->setValue("extorsionadores", "SI◻ NO[X]");
      }
      if ($personasMargenLeyes->otros) {
        $template->setValue("otros", "SI[X] NO◻");
      } else {
        $template->setValue("otros", "SI◻ NO[X]");
      }
      if ($personasMargenLeyes->familiares_sentenciados) {
        $template->setValue("familiar_penales", "SI[X] NO◻");
      } else {
        $template->setValue("familiar_penales", "SI◻ NO[X]");
      }
      if ($motivacionesPostulaciones->causar_danio) {
        $template->setValue("danio_persona", "SI[X] NO◻");
      } else {
        $template->setValue("danio_persona", "SI◻ NO[X]");
      }
      if ($motivacionesPostulaciones->beneficio_ilegal) {
        $template->setValue("bene_ilegal", "SI[X] NO◻");
      } else {
        $template->setValue("bene_ilegal", "SI◻ NO[X]");
      }
      if ($motivacionesPostulaciones->familiares_en_empresa) {
        $template->setValue("emp_post", "SI[X] NO◻");
      } else {
        $template->setValue("emp_post", "SI◻ NO[X]");
      }
      if ($acercaPoligrafo->paso_antes_examen) {
        $template->setValue("paso_antes", "SI[X] NO◻");
      } else {
        $template->setValue("paso_antes", "SI◻ NO[X]");
      }
      $template->setValue("afirma_explique", $acercaPoligrafo->explique_paso_antes ?? 'No registra');
      $template->setValue("paso_antes_empresa", $acercaPoligrafo->empresa ?? 'No registra');
      $template->setValue("acerca_fec", $acercaPoligrafo->fecha ?? 'No registra');
      $template->setValue("acerca_motivo", $acercaPoligrafo->motivo ?? 'No registra');
      $template->setValue('nombres_persona_evaluada', "$persona->nombres $persona->apellido_paterno $persona->apellido_materno");
      $template->setValue('dni_persona_evaluada', $persona->numero_documento);
      $template->setValue('ciudad_eva', $consentimientos->ciudad);
      $template->setValue('dia_eva', Carbon::parse($consentimientos->fecha_formato)->format('d'));
      $template->setValue('mes_eva', $this->getMonth(Carbon::parse($consentimientos->fecha_formato)->format('m')));
      $template->setValue('anio_eva', Carbon::parse($consentimientos->fecha_formato)->format('Y'));

      $base64Image = $solDatPers[0]->firma;
      // Quitar el encabezado "data:image/...;base64,"
      [$type, $data] = explode(';', $base64Image);
      [, $data] = explode(',', $data);
      $data = base64_decode($data);
      // Obtener extensión (opcional)
      $extension = explode('/', mime_content_type($base64Image))[1];

      // Nombre único para la imagen
      $fileName = 'imagen_' . time() . '.' . $extension;

      // Guardar en storage/app/public/
      Storage::disk('public')->put($fileName, $data);

      // Obtener URL o ruta
      $path = storage_path('app/public/' . $fileName); // para acceder desde PHP

      // Insertar imagen en el marcador ${firma_evaluado}
      $template->setImageValue('firma_evaluado', [
        'path' => $path,
        'width' => 280,
        'height' => 180,
        'ratio' => true, // mantiene la proporción
      ]);

      // Eliminar imagen temporal

      $fileName = "Formato Uno - $persona->nombres.docx";

      $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
      $template->saveAs($tempFile);

      $headers = ['Content-Type' => 'application/octet-stream'];

      unlink($path);

      return response()->download($tempFile, $fileName, $headers)->deleteFileAfterSend(true);

    } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
      return back()->with('error', $e->getCode());
    }
  }

  public function exportFormatConcent(string $id)
  {
    $persona = DB::select("select p.nombres || ' ' || p.apellido_paterno || ' ' || p.apellido_materno as nombres, p.numero_documento, EXTRACT(YEAR FROM AGE(CURRENT_DATE, p.fecha_nacimiento)) AS edad, ce.firma, ce.fecha_formato, u.codigo_poligrafista || spd.cantidad_evaluaciones as codigo_poligrafista from consentimientos_examenes ce left join personas p on p.id = ce.persona_id left join solicitudes_datos_personales spd on spd.persona_id = p.id left join users u on u.id = spd.usuario_id where p.id = ?", [$id])[0];
    $boldFontStyle = ['bold' => true];
    $template = new \PhpOffice\PhpWord\TemplateProcessor('storage/formatos/formatoconsentimiento.docx');
    $template->setValue("codpoli", $persona->codigo_poligrafista);
    $template->setValue("fechaeval", Carbon::parse($persona->fecha_formato)->format('d/m/Y'));
    $template->setValue("nombres_evaluado", $persona->nombres);
    $template->setValue("numero_docu", $persona->numero_documento);
    $template->setValue("edad", $persona->edad);
    $template->setValue("documento_conentimiento", $persona->numero_documento);
    $template->setValue("fecha_consentimiento", Carbon::parse($persona->fecha_formato)->format('d/m/Y'));

    $base64Image = $persona->firma;
    // Quitar el encabezado "data:image/...;base64,"
    [$type, $data] = explode(';', $base64Image);
    [, $data] = explode(',', $data);
    $data = base64_decode($data);
    // Obtener extensión (opcional)
    $extension = explode('/', mime_content_type($base64Image))[1];

    // Nombre único para la imagen
    $fileName = 'imagen_' . time() . '.' . $extension;

    // Guardar en storage/app/public/
    Storage::disk('public')->put($fileName, $data);

    // Obtener URL o ruta
    $path = storage_path('app/public/' . $fileName); // para acceder desde PHP

    // Insertar imagen en el marcador ${firma_evaluado}
    $template->setImageValue('firma_consentimiento', [
      'path' => $path,
      'width' => 280,
      'height' => 180,
      'ratio' => true, // mantiene la proporción
    ]);

    // Eliminar imagen temporal

    $fileName = "Consentimiento - $persona->nombres.docx";

    $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
    $template->saveAs($tempFile);

    $headers = ['Content-Type' => 'application/octet-stream'];

    unlink($path);

    return response()->download($tempFile, $fileName, $headers)->deleteFileAfterSend(true);
  }

  protected function getMonth(string $numberMonth): string
  {
    $months = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "setiembre", "octubre", "noviembre", "diciembre"];
    return $months[$numberMonth - 1];
  }
}
