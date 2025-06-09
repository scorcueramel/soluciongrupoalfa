<?php

namespace App\Http\Controllers;

use App\Http\Requests\InformeFinalStoreRequest;
use App\Models\ConsentimientosExamenes;
use App\Models\ConsumosBebidasAlcoholicas;
use App\Models\Distritos;
use App\Models\ExperienciasLaborales;
use App\Models\FormacionesAcademicasPersonas;
use App\Models\InformesFinales;
use App\Models\ParentescosPersonas;
use App\Models\Personas;
use App\Models\SolicitudesDatosPersonales;
use App\Models\TiposViviendas;
use App\Services\InformeFinalService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use PDF;

class InformeFinalController extends Controller
{
  public function __construct(
    private InformeFinalService $informeFinalService
  )
  {
  }

  public function store(InformeFinalStoreRequest $request)
  {
    $response = $this->informeFinalService->store($request->data);
    $jsonDecode = json_decode($response->content());

    if ($jsonDecode->code === 200) {
      return back()->with('success', $jsonDecode->message);
    }

    if ($jsonDecode->code === 500) {
      return back()->with('error', $jsonDecode->message);
    }
  }

  public function show(string $id)
  {

    try {
      $subtest = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "ñ", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"];

      $meses = [
        'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO',
        'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'
      ];

      $persona = Personas::leftJoin('tipos_documentos', 'tipos_documentos.id', '=', 'personas.tipo_documento_id')
        ->leftJoin('estados_civiles', 'estados_civiles.id', '=', 'personas.estado_civil_id')
        ->where('personas.id', $id)
        ->select('personas.*', 'tipos_documentos.tipo_documento', 'estados_civiles.estado_civil')
        ->get()[0];

      $codigoYConteo = SolicitudesDatosPersonales::leftJoin('users', 'users.id', '=', 'solicitudes_datos_personales.usuario_id')->select('users.id as userid', 'users.codigo_poligrafista', 'solicitudes_datos_personales.fecha_solicitud', 'solicitudes_datos_personales.cantidad_evaluaciones')->where('persona_id', $id)->get();
      $solDatPers = SolicitudesDatosPersonales::leftJoin('empresas', 'empresas.id', '=', 'solicitudes_datos_personales.empresa_id')->leftJoin('cargos', 'cargos.id', '=', 'solicitudes_datos_personales.cargo_id')->where('persona_id', $id)->get();

      $codigoPoli = $codigoYConteo[0]->codigo_poligrafista . "" . $codigoYConteo[0]->cantidad_evaluaciones;

      $informeFinal = InformesFinales::where('persona_id', $id)->where('usuario_id', Auth::id())->get();

      $diaNac = Carbon::parse($persona->fecha_nacimiento)->format('d');
      $mesNac = $meses[(int)Carbon::parse($persona->fecha_nacimiento)->format('m') - 1];
      $anioNac = Carbon::parse($persona->fecha_nacimiento)->format('Y');

      $distrito = Distritos::find($persona->distrito_id)->first();
      $provincia = ConsentimientosExamenes::find($persona->id)->first();
      $tiposVivienda = TiposViviendas::find($persona->tipo_vivienda_id)->first();
      $parentescos = ParentescosPersonas::leftJoin('tipos_parentescos', 'tipos_parentescos.id', '=', 'parentescos_personas.tipo_parentesco_id')->leftJoin('personas', 'personas.id', '=', 'parentescos_personas.persona_id')->where('persona_id', $persona->id)->select('tipos_parentescos.tipo_parentesco', 'parentescos_personas.nombres_apellidos', 'parentescos_personas.edad', 'parentescos_personas.ocupacion', 'parentescos_personas.mismo_inmueble')->get();
      $formAcademicas = FormacionesAcademicasPersonas::leftJoin('grados_instrucciones', 'grados_instrucciones.id', 'formaciones_academicas_personas.grado_instruccion_id')->where('persona_id', $persona->id)->select('grados_instrucciones.grado_instruccion', 'formaciones_academicas_personas.centro_estudio', 'formaciones_academicas_personas.especialidad_facultad', 'formaciones_academicas_personas.fecha_inicio', 'formaciones_academicas_personas.fecha_termino', 'formaciones_academicas_personas.situacion')->get();
      $infoFinancieras = DB::select("select pif.tiene_prestamo, pif.monto_prestamo, eb.nombre_entidad as entidad_prestamo, pif.cuota_mensual_prestamo, pif.otro_ingreso, pif.monto_ingreso, pif.origen_ingreso, pif.tiene_propiedades, pif.detalle_propiedades, pif.reportado_centrar_riesgos, eb2.nombre_entidad as entidad_reporto, pif.motivo_reportado, pif.tiempo_mora, pif.monto_deuda from personas_informaciones_financieras pif left join entidades_bancarias eb on eb.id = pif.entidad_bancaria_prestamo_id left join entidades_bancarias eb2 on eb2.id = pif.entidad_bancaria_reporto_id where persona_id = ?", [$persona->id])[0];

      $reportaInfocorp = $infoFinancieras->reportado_centrar_riesgos === true ? 'Afirmo haber estado registrado en INFOCORP' : 'Negó haber estado registrado negativamente en INFOCORP';
      $propiedades = $infoFinancieras->tiene_propiedades === true ? 'afirmo tener propiedades registradas a su nombre.' : 'negó tener propiedades registradas a su nombre.';

      $consumoBebidas = ConsumosBebidasAlcoholicas::where('persona_id', $id)->get()[0];

      $expLaborales = ExperienciasLaborales::where('persona_id', $id)->get();

      $template = new \PhpOffice\PhpWord\TemplateProcessor('storage/formatos/informefinal.docx');
      $template->setValue('codpoli', $codigoPoli);
      $template->setValue('fecinfo', Carbon::parse($codigoYConteo[0]->fecha_solicitud)->format('d/m/Y'));

      $path = storage_path('app/public/fotos/' . $persona->foto);

      $template->setImageValue('fotoeval', [
        'path' => $path,
        'width' => 150,
        'height' => 150,
        'ratio' => true,
      ]);
      $template->setValue('nombre_eval', Str::upper("$persona->nombres $persona->apellido_paterno $persona->apellido_materno"));
      $template->setValue('porc_eval', $informeFinal[0]->porcentaje_evaluacion);
      $template->setValue('tipo_doc', $persona->tipo_documento);
      $template->setValue('nro_dni', $persona->numero_documento);
      $template->setValue('cargo', $solDatPers[0]->cargo);
      $template->setValue('empresa', $solDatPers[0]->razon_social);
      $template->setValue('fec_nac', "$diaNac $mesNac $anioNac");
      $template->setValue('estado_civil', $persona->estado_civil);
      $template->setValue('direccion', $persona->direccion);
      $template->setValue('distrito', $distrito->distrito);
      $template->setValue('provincia', $provincia->ciudad);
      $template->setValue('departamento', $provincia->ciudad);
      $template->setValue('tipo_inmueble', $tiposVivienda->tipo_vivienda);
      $template->setValue('celular', $persona->telefono);
      $template->cloneRow('parentesco', count($parentescos));
      foreach ($parentescos as $index => $parentesco) {
        $i = $index + 1;
        $template->setValue("parentesco#{$i}", $parentesco->tipo_parentesco);
        $template->setValue("nombres_apellidos#{$i}", $parentesco->nombres_apellidos);
        $template->setValue("edad#{$i}", $parentesco->edad);
        $template->setValue("ocupacion#{$i}", $parentesco->ocupacion);
        if ($parentesco->mismo_inmueble) {
          $template->setValue("mismo_inmueble#{$i}", 'SI');
        } else {
          $template->setValue("mismo_inmueble#{$i}", 'NO');
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
          $template->setValue("situacion#{$i}", 'COMPLETO');
        } else {
          $template->setValue("situacion#{$i}", 'INCOMPLETO');
        }
      }

      if ($infoFinancieras->tiene_prestamo) {
        $template->setValue("info_financiera", "Indicó que tiene un préstamo de S/ $infoFinancieras->monto_prestamo.00 con $infoFinancieras->entidad_prestamo del año 2023 a cancelar en 18 cuotas de S/ $infoFinancieras->cuota_mensual_prestamo.00. $reportaInfocorp. Señaló no tener tarjetas de crédito. Finalmente, $propiedades");
      } else {
        $template->setValue("info_financiera", "Indicó que no tiene préstamos. $reportaInfocorp. Señaló no tener tarjetas de crédito. Finalmente, $propiedades");
      }

      if (is_null($consumoBebidas->frecuencia_consumo)) {
        $template->setValue('alcohol', 'Mencionó que no consume bebidas alcohólicas');
      } else {
        $template->setValue('alcohol', "Mencionó que consume bebidas alcohólicas $consumoBebidas->frecuencia_consumo");
      }

      $template->setValue('drogas_ilegales', $informeFinal[0]->drogas_ilegales);
      $template->setValue('antecedentes', $informeFinal[0]->antecedentes);
      $template->setValue('vinculos', $informeFinal[0]->vinculos);
      $template->setValue('planes_infiltracion', $informeFinal[0]->planes_infiltracion);
      $template->setValue('proyeccion_tiempo_empresa', $informeFinal[0]->proyeccion_tiempo_empresa);
      $template->setValue('conslusion_final', $informeFinal[0]->conclusion);

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

      if (is_null($informeFinal[0]->preguntas_relevantes)) {
        $template->setValue('titulo_subtest', 'No registra');
        $template->setValue('resp_nro', '');
        $template->setValue('pregunta_relevante', '');
      } else {
        $preguntas = json_decode($informeFinal[0]->preguntas_relevantes);

        $template->cloneRow('pregunta_relevante', count($preguntas));

        foreach ($preguntas as $index => $pregunta) {
          $i = $index + 1;
          $template->setValue("pregunta_relevante#{$i}", $pregunta);
        }
      }

      $fileName = "Informe Final - $persona->nombres.docx";

      $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
      $template->saveAs($tempFile);

      $headers = ['Content-Type' => 'application/octet-stream'];

      return response()->download($tempFile, $fileName, $headers)->deleteFileAfterSend(true);
    } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
      return back()->with('error', $e->getCode());
    }
  }

  public function generarPdf($id)
  {
    $descargar = request()->query('descargar') === '1';

    $subtest = range('a', 'z'); // Equivalente a tu array

    $meses = [
      'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO',
      'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'
    ];

    $persona = Personas::leftJoin('tipos_documentos', 'tipos_documentos.id', '=', 'personas.tipo_documento_id')
      ->leftJoin('estados_civiles', 'estados_civiles.id', '=', 'personas.estado_civil_id')
      ->where('personas.id', $id)
      ->select('personas.*', 'tipos_documentos.tipo_documento', 'estados_civiles.estado_civil')
      ->first();

    $codigoYConteo = SolicitudesDatosPersonales::leftJoin('users', 'users.id', '=', 'solicitudes_datos_personales.usuario_id')
      ->select('users.codigo_poligrafista', 'solicitudes_datos_personales.cantidad_evaluaciones')
      ->where('persona_id', $id)
      ->first();

    $codigoPoli = $codigoYConteo?->codigo_poligrafista . ($codigoYConteo?->cantidad_evaluaciones ?? '');

    $informeFinal = InformesFinales::where('persona_id', $id)->where('usuario_id', Auth::id())->get();

    $diaNac = Carbon::parse($persona->fecha_nacimiento)->format('d');
    $mesNac = $meses[(int)Carbon::parse($persona->fecha_nacimiento)->format('m') - 1];
    $anioNac = Carbon::parse($persona->fecha_nacimiento)->format('Y');

    $distrito = Distritos::find($persona->distrito_id);
    $provincia = ConsentimientosExamenes::find($persona->id);
    $tiposVivienda = TiposViviendas::find($persona->tipo_vivienda_id);

    $parentescos = ParentescosPersonas::leftJoin('tipos_parentescos', 'tipos_parentescos.id', '=', 'parentescos_personas.tipo_parentesco_id')
      ->select('tipos_parentescos.tipo_parentesco', 'parentescos_personas.nombres_apellidos', 'parentescos_personas.edad', 'parentescos_personas.ocupacion', 'parentescos_personas.mismo_inmueble')
      ->where('persona_id', $persona->id)
      ->get();

    $formAcademicas = FormacionesAcademicasPersonas::leftJoin('grados_instrucciones', 'grados_instrucciones.id', 'formaciones_academicas_personas.grado_instruccion_id')
      ->select('grados_instrucciones.grado_instruccion', 'formaciones_academicas_personas.centro_estudio', 'formaciones_academicas_personas.especialidad_facultad', 'formaciones_academicas_personas.fecha_inicio', 'formaciones_academicas_personas.fecha_termino', 'formaciones_academicas_personas.situacion')
      ->where('persona_id', $persona->id)
      ->get();

    $infoFinancieras = DB::selectOne("
            SELECT
                pif.tiene_prestamo, pif.monto_prestamo, eb.nombre_entidad as entidad_prestamo,
                pif.cuota_mensual_prestamo, pif.otro_ingreso, pif.monto_ingreso, pif.origen_ingreso,
                pif.tiene_propiedades, pif.detalle_propiedades, pif.reportado_centrar_riesgos,
                eb2.nombre_entidad as entidad_reporto, pif.motivo_reportado, pif.tiempo_mora, pif.monto_deuda
            FROM personas_informaciones_financieras pif
            LEFT JOIN entidades_bancarias eb ON eb.id = pif.entidad_bancaria_prestamo_id
            LEFT JOIN entidades_bancarias eb2 ON eb2.id = pif.entidad_bancaria_reporto_id
            WHERE persona_id = ?", [$persona->id]
    );

    $reportaInfocorp = $infoFinancieras?->reportado_centrar_riesgos ? 'Afirmó haber estado registrado en INFOCORP' : 'Negó haber estado registrado negativamente en INFOCORP';
    $propiedades = $infoFinancieras?->tiene_propiedades ? 'Afirmó tener propiedades registradas a su nombre.' : 'Negó tener propiedades registradas a su nombre.';

    $consumoBebidas = ConsumosBebidasAlcoholicas::where('persona_id', $id)->first();
    $expLaborales = ExperienciasLaborales::where('persona_id', $id)->get();

    $pdf = Pdf::loadView('pdf.informe', compact(
      'persona', 'diaNac', 'mesNac', 'anioNac',
      'distrito', 'provincia', 'tiposVivienda',
      'parentescos', 'formAcademicas', 'infoFinancieras',
      'reportaInfocorp', 'propiedades', 'consumoBebidas',
      'expLaborales', 'codigoPoli', 'informeFinal'
    ));

    return $descargar
      ? $pdf->download("informe_{$persona->id}.pdf")
      : $pdf->stream("informe_{$persona->id}.pdf");
  }
}
