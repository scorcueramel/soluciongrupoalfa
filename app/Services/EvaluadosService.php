<?php

namespace App\Services;

use App\Models\AcercaPoligrafos;
use App\Models\ComisionesDelitos;
use App\Models\ConsumosBebidasAlcoholicas;
use App\Models\DetalleExperenciaLaboral;
use App\Models\EntidadesBancarias;
use App\Models\ExperienciasLaborales;
use App\Models\FormacionesAcademicasPersonas;
use App\Models\ImplicacionesDrogas;
use App\Models\MotivacionesPostulaciones;
use App\Models\ParentescosPersonas;
use App\Models\Personas;
use App\Models\PersonasInformacionesFinancieras;
use App\Models\PersonasMargenLeyes;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class EvaluadosService
{

  //pendiente pasar el index por aquí

  public static function obtenerDetallePersona(string $personaId): JsonResponse
  {
    DB::beginTransaction();
    try {
      DB::commit();
      $personas = Personas::where('personas.id', $personaId)
        ->leftJoin('nacionalidades', 'nacionalidades.id', '=', 'personas.nacionalidad_id')
        ->leftJoin('tipos_documentos', 'tipos_documentos.id', '=', 'personas.tipo_documento_id')
        ->leftJoin('estados_civiles', 'estados_civiles.id', '=', 'personas.estado_civil_id')
        ->leftJoin('generos', 'generos.id', '=', 'personas.genero_id')
        ->leftJoin('distritos', 'distritos.id', '=', 'personas.distrito_id')
        ->leftJoin('tipos_viviendas', 'tipos_viviendas.id', '=', 'personas.tipo_vivienda_id')
        ->leftJoin('solicitudes_datos_personales', 'solicitudes_datos_personales.persona_id', '=', 'personas.id')
        ->leftJoin('empresas', 'empresas.id', '=', 'solicitudes_datos_personales.empresa_id')
        ->leftJoin('cargos', 'cargos.id', '=', 'solicitudes_datos_personales.cargo_id')
        ->select('personas.id as personaId', 'personas.foto', 'personas.nombres', 'personas.apellido_paterno', 'personas.apellido_materno', 'personas.numero_documento', 'personas.fecha_nacimiento', 'personas.direccion', 'personas.otro_tipo_vivienda', 'personas.telefono', 'personas.email', 'personas.brevete', 'personas.lugar_nacimiento', 'nacionalidades.nacionalidad as nacionalidad', 'tipos_documentos.tipo_documento', 'estados_civiles.estado_civil', 'generos.genero', 'distritos.distrito', 'tipos_viviendas.tipo_vivienda', 'solicitudes_datos_personales.fecha_solicitud', 'solicitudes_datos_personales.firma', 'empresas.nombre_comercial', 'cargos.cargo')
        ->get();

      $parentescos = ParentescosPersonas::leftJoin('tipos_parentescos','tipos_parentescos.id','=','parentescos_personas.tipo_parentesco_id')
        ->where('persona_id', $personaId)
        ->select('parentescos_personas.*','tipos_parentescos.tipo_parentesco')
        ->get();

      $formacionesAcademicas = FormacionesAcademicasPersonas::leftJoin('grados_instrucciones','grados_instrucciones.id','=','formaciones_academicas_personas.grado_instruccion_id')->where('persona_id', $personaId)->get();

      $experienciasLaborales = ExperienciasLaborales::where('persona_id', $personaId)->get();

      $detallesExperienciasLaborales = DetalleExperenciaLaboral::where('persona_id', $personaId)->get();

      $informacionesFinancieras = PersonasInformacionesFinancieras::where('persona_id', $personaId)->get();

      $consumoBebidasAlcoholicas = ConsumosBebidasAlcoholicas::where('persona_id', $personaId)->get();

      $implicaonesDrogas = ImplicacionesDrogas::where('persona_id', $personaId)->get();

      $comisionDelitos = ComisionesDelitos::where('persona_id', $personaId)->get();

      $personasMargenLeyes = PersonasMargenLeyes::where('persona_id', $personaId)->get();

      $motivacionesPostulacion = MotivacionesPostulaciones::where('persona_id', $personaId)->get();

      $acercaPoligrafo = AcercaPoligrafos::where('persona_id', $personaId)->get();

      $entidadesBancarias = EntidadesBancarias::all();

      return response()->json(['code' => 200, 'data' => [
        "parentescos" => $parentescos,
        "personas" => $personas,
        "formacionesAcademicas" => $formacionesAcademicas,
        "experienciasLaborales" => $experienciasLaborales,
        "detallesExperienciasLaborales" => $detallesExperienciasLaborales,
        "informacionesFinancieras" => $informacionesFinancieras,
        "consumoBebidasAlcoholicas" => $consumoBebidasAlcoholicas,
        "implicaonesDrogas" => $implicaonesDrogas,
        "comisionDelitos" => $comisionDelitos,
        "personasMargenLeyes" => $personasMargenLeyes,
        "motivacionesPostulacion" => $motivacionesPostulacion,
        "acercaPoligrafo" => $acercaPoligrafo,
        "entidadesBancarias" => $entidadesBancarias,
      ]]);
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json(['code' => 500, 'message' => $th->getMessage()]);
    }
  }
}
