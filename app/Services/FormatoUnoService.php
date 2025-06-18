<?php

namespace App\Services;

use App\Models\AccesoFormatos;
use App\Models\AcercaPoligrafos;
use App\Models\ComisionesDelitos;
use App\Models\ConsentimientosExamenes;
use App\Models\ConsumosBebidasAlcoholicas;
use App\Models\DetalleExperenciaLaboral;
use App\Models\ExperienciasLaborales;
use App\Models\FormacionesAcademicasPersonas;
use App\Models\ImplicacionesDrogas;
use App\Models\MotivacionesPostulaciones;
use App\Models\ParentescosPersonas;
use App\Models\Personas;
use App\Models\PersonasInformacionesFinancieras;
use App\Models\PersonasMargenLeyes;
use App\Models\SolicitudesDatosPersonales;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FormatoUnoService
{
  public static function createRegisterFormatoUno(array $data): JsonResponse
  {
    DB::beginTransaction();
    try {
      foreach ($data as $d) {
        $filename = null;

        if (!is_null($d->foto)) {
          $image = $d->foto;
          $uuid = Str::uuid()->toString();
          $extension = $image->getClientOriginalExtension();
          $filename = $uuid . "." . $extension;
          $image->storeAs('fotos', $filename, 'public');
        }

        //Datos de las personas
        $persona = Personas::create([
          'nacionalidad_id' => $d->nacionalidad["code"],
          'tipo_documento_id' => $d->tipoDocumento,
          'estado_civil_id' => $d->estadoCivil,
          'genero_id' => $d->genero,
          'distrito_id' => $d->distrito["code"],
          'tipo_vivienda_id' => $d->tipoVivienda,
          'nombres' => Str::title($d->nombres),
          'apellido_paterno' => Str::title($d->paterno),
          'apellido_materno' => Str::title($d->materno),
          'numero_documento' => $d->numeroDocumento,
          'fecha_nacimiento' => $d->fechaNacimiento,
          'lugar_nacimiento' => $d->lugarNacimiento,
          'direccion' => Str::title($d->direccion),
          'otro_tipo_vivienda' => $d->otroTipoVivienda,
          'telefono' => $d->telefono,
          'email' => $d->email,
          'brevete' => $d->brevete,
          'foto' => $filename,
        ]);

        //end Personas
        //Formaciones academicas
        //3 formaciones academicas, validadndo que solo la ultima no sea obligatoria
        FormacionesAcademicasPersonas::create([
          'persona_id' => $persona->id,
          'grado_instruccion_id' => $d->gradoInstruccionUno,
          'centro_estudio' => $d->centroEstudioUno,
          'especialidad_facultad' => $d->especialidadesFacultadUno,
          'fecha_inicio' => $d->inicioEstudiosUno,
          'fecha_termino' => $d->terminoEstudiosUno,
          'situacion' => $d->situacionUno,
        ]);
        FormacionesAcademicasPersonas::create([
          'persona_id' => $persona->id,
          'grado_instruccion_id' => $d->gradoInstruccionDos,
          'centro_estudio' => $d->centroEstudioDos,
          'especialidad_facultad' => $d->especialidadesFacultadDos,
          'fecha_inicio' => $d->inicioEstudiosDos,
          'fecha_termino' => $d->terminoEstudiosDos,
          'situacion' => $d->situacionDos,
        ]);
        if (
          !empty($d->gradoInstruccionTres) &&
          !empty($d->centroEstudioTres) &&
          !empty($d->especialidadesFacultadTres) &&
          !empty($d->inicioEstudiosTres)
        ) {
          FormacionesAcademicasPersonas::create([
            'persona_id' => $persona->id,
            'grado_instruccion_id' => $d->gradoInstruccionTres,
            'centro_estudio' => $d->centroEstudioTres,
            'especialidad_facultad' => $d->especialidadesFacultadTres,
            'fecha_inicio' => $d->inicioEstudiosTres,
            'fecha_termino' => $d->terminoEstudiosTres,
            'situacion' => $d->situacionTres,
          ]);
        }

        //end Formaciones academicas
        //Motivaviones de postulación
        MotivacionesPostulaciones::create([
          'persona_id' => $persona->id,
          'causar_danio' => $d->planeaCausarDanioPersonaEmpresa,
          'beneficio_ilegal' => $d->planeaObtenerBeneficioIlegal,
          'familiares_en_empresa' => $d->familiaresMismaEmpresa,
        ]);
        //end Motivaviones de postulación
        //Consumo de bebidas alcoholicas
        ConsumosBebidasAlcoholicas::create([
          'persona_id' => $persona->id,
          'frecuencia_consumo' => $d->fecuenciaConsumoBebidasAlcoholicas,
          'bebidas_consume' => $d->queBebidasConsume,
          'tratamiento_alcoholismo' => $d->tratamientoAlcholismo,
          'trabajo_ebrio' => $d->trabajoEbrio,
          'explicacion' => $d->expliqueLlegoEbrio,
        ]);
        //end Consumo de bebidas alcoholicas
        //Personas informacion financiera
        PersonasInformacionesFinancieras::create([
          'persona_id' => $persona->id,
          'tiene_prestamo' => $d->tienesPrestamos,
          'entidad_bancaria_prestamo_id' => $d->entidadBancaria === null ? null : $d->entidadBancaria["code"],
          'monto_prestamo' => $d->montoDeuda,
          'cuota_mensual_prestamo' => $d->cuotaMensual,
          'otro_ingreso' => $d->otroIngreso,
          'monto_ingreso' => $d->preciseMonto,
          'origen_ingreso' => $d->origenOtroIngreso,
          'tiene_propiedades' => $d->tienePropiedades,
          'detalle_propiedades' => $d->detallePropiedades,
          'reportado_centrar_riesgos' => $d->reportadoEnCentralesDeRiesgo,
          'entidad_bancaria_reporto_id' => $d->entidadDeuda === null ? null : $d->entidadDeuda["code"],
          'motivo_reportado' => $d->motivoCentralDeRiesgo,
          'tiempo_mora' => $d->tiempoMora,
          'monto_deuda' => $d->montoDeuraMora,
        ]);
        //end Personas informacion financiera
        //Perosnas al margen de la ley
        PersonasMargenLeyes::create([
          'persona_id' => $persona->id,
          'pandilleros' => $d->pandilleros,
          'sicarios' => $d->sicarios,
          'asaltantes' => $d->asaltantes,
          'traficantes_drogas' => $d->traficantesDrogas,
          'estafadores' => $d->estafadores,
          'terroristas' => $d->terroristas,
          'secuestradores' => $d->secuestradores,
          'extorsionadores' => $d->extorsionadores,
          'otros' => $d->otrasPersonasMargenLey,
          'familiares_sentenciados' => $d->familiaresSentenciadosPenales,
        ]);
        //end Perosnas al margen de la ley
        //Acerca del poligrafo
        AcercaPoligrafos::create([
          'persona_id' => $persona->id,
          'paso_antes_examen' => $d->pasoAntesExamenPoligrafo,
          'explique_paso_antes' => $d->explicacionExamenAnterior,
          'empresa' => $d->empresaPostuloAntes,
          'fecha' => $d->fechaExamenAnterior,
          'motivo' => $d->motivoPasoAntesE,
        ]);
        //end Acerca del poligrafo
        //Experiencias labroales
        ExperienciasLaborales::create([
          'persona_id' => $persona->id,
          'empresa' => $d->empresaUno,
          'fecha_ingreso' => $d->fechaIngresoUno,
          'fecha_salida' => $d->fechaSalidaUno,
          'sueldo_percibido' => $d->sueldoUno,
          'cargo_desempenado' => $d->cargoDesempenioUno,
          'motivo_salida' => $d->motivoSalidaUno,
        ]);
        ExperienciasLaborales::create([
          'persona_id' => $persona->id,
          'empresa' => $d->empresaDos,
          'fecha_ingreso' => $d->fechaIngresoDos,
          'fecha_salida' => $d->fechaSalidaDos,
          'sueldo_percibido' => $d->sueldoDos,
          'cargo_desempenado' => $d->cargoDesempenioDos,
          'motivo_salida' => $d->motivoSalidaDos,
        ]);
        ExperienciasLaborales::create([
          'persona_id' => $persona->id,
          'empresa' => $d->empresaTres,
          'fecha_ingreso' => $d->fechaIngresoTres,
          'fecha_salida' => $d->fechaSalidaTres,
          'sueldo_percibido' => $d->sueldoTres,
          'cargo_desempenado' => $d->cargoDesempenioTres,
          'motivo_salida' => $d->motivoSalidaTres,
        ]);
        //end Experiencias labroales
        //Detalle de experiencia labolar
        //almacena las opciones de amonestaciones, solicitud de renuncia, explicacion
        DetalleExperenciaLaboral::create([
          'persona_id' => $persona->id,
          'recibio_amonestaciones' => $d->amonestacionesEmpleos,
          'solicitud_renuncia' => $d->solicitudRenuncia,
          'explicacion' => $d->explicacion,
        ]);
        //end Detalle de experiencia labolar
        //Parentescos personas
        ParentescosPersonas::create([
          'persona_id' => $persona->id,
          'tipo_parentesco_id' => $d->tipoParentescoPadre,
          'nombres_apellidos' => $d->nombrespadre,
          'edad' => $d->edadpadre,
          'ocupacion' => $d->nombresocupacionpadre,
          'mismo_inmueble' => $d->mismoInmueblePadre,
        ]);
        ParentescosPersonas::create([
          'persona_id' => $persona->id,
          'tipo_parentesco_id' => $d->tipoParentescoMadre,
          'nombres_apellidos' => $d->nombresmadre,
          'edad' => $d->edadmadre,
          'ocupacion' => $d->nombresocupacionmadre,
          'mismo_inmueble' => $d->mismoInmuebleMadre,
        ]);
        if ($d->tieneConyuge) {
          ParentescosPersonas::create([
            'persona_id' => $persona->id,
            'tipo_parentesco_id' => $d->tipoParentescoConyuge,
            'nombres_apellidos' => $d->nombresConyuge,
            'edad' => $d->edadConyuge,
            'ocupacion' => $d->nombreOcupacionConyuge,
            'mismo_inmueble' => $d->mismoInmuebleConyuge,
          ]);
        }

        if (!empty($d->tieneHijos) && (int)$d->cantidadHijos > 0 && is_array($d->nombresHijos)) {
          for ($i = 0; $i < (int)$d->cantidadHijos; $i++) {
            if (
              !empty($d->nombresHijos[$i]) &&
              isset($d->edadesHijos[$i]) &&
              isset($d->nombreOcupacionesHijos[$i]) &&
              isset($d->mismoInmuebleHijos[$i])
            ) {
              ParentescosPersonas::create([
                'persona_id' => $persona->id,
                'tipo_parentesco_id' => $d->tipoParentescoHijos,
                'nombres_apellidos' => $d->nombresHijos[$i],
                'edad' => $d->edadesHijos[$i],
                'ocupacion' => $d->nombreOcupacionesHijos[$i],
                'mismo_inmueble' => $d->mismoInmuebleHijos[$i],
              ]);
            }
          }
        }

        if (!empty($d->tieneHermanos) && (int)$d->cantidadHermanos > 0 && is_array($d->nombresHermanos)) {
          for ($i = 0; $i < (int)$d->cantidadHermanos; $i++) {
            if (
              !empty($d->nombresHermanos[$i]) &&
              isset($d->edadesHermanos[$i]) &&
              isset($d->nombreOcupacionesHermanos[$i]) &&
              isset($d->mismoInmuebleHermanos[$i])
            ) {
              ParentescosPersonas::create([
                'persona_id' => $persona->id,
                'tipo_parentesco_id' => $d->tipoParentescoHermanos,
                'nombres_apellidos' => $d->nombresHermanos[$i],
                'edad' => $d->edadesHermanos[$i],
                'ocupacion' => $d->nombreOcupacionesHermanos[$i],
                'mismo_inmueble' => $d->mismoInmuebleHermanos[$i],
              ]);
            }
          }
        }
        //end Parentescos personas
        //Solicitudes Datos Personales
        SolicitudesDatosPersonales::create([
          'persona_id' => $persona->id,
          'empresa_id' => $d->empresa["code"],
          'cargo_id' => $d->cargo["code"],
          'usuario_id' => $d->usuarioId,
          'fecha_solicitud' => date("Y-m-d"),
          'firma' => $d->imagenFirma,
          'cantidad_evaluaciones' => $d->numeroEvaluaciones,
        ]);
        //end Solicitudes Datos Personales
        //Comision de delitos
        ComisionesDelitos::create([
          'persona_id' => $persona->id,
          'robo_hurto_fraude' => $d->roboHurtoFraude,
          'homicidio_involuntario' => $d->homicidioInvoluntario,
          'asalto' => $d->asalto,
          'danio_fisico_individuo' => $d->planesDanioFisico,
          'secuestro' => $d->secuestro,
          'violacion' => $d->violacion,
          'muerte_lesion_otra_persona' => $d->muerteLesionPersona,
          'trafico_ilicito_drogas' => $d->traficoIlicitoDrogas,
          'trafico_armas' => $d->traficoArmas,
          'otros_delitos' => $d->castigadoConCarcel,
          'explique_otros' => $d->explicacionCastigadoLey,
        ]);
        //end Comision de delitos
        //Implicaciones en Drogas

        ImplicacionesDrogas::create([
          'persona_id' => $persona->id,
          'marihuana' => $d->marihuana,
          'pbc' => $d->pbc,
          'cocaina' => $d->cocaina,
          'heroina' => $d->heroina,
          'lsd' => $d->lcd,
          'extasis' => $d->extasis,
          'ultimo_consumo' => $d->cantidadUltimoConsumo,
          'tiempo_transcurrido' => $d->tiempoUltimaVez,
          'familiar_consumidor' => $d->familiaresEnDrogas,
        ]);

        //end Implicaciones en Drogas
        ConsentimientosExamenes::create([
          'persona_id' => $persona->id,
          'fecha_formato' => date("Y-m-d"),
          'firma' => $d->imagenFirma,
          'ciudad' => $d->ciudad,
        ]);
      }

      $accesoFormato = AccesoFormatos::where('documento_persona', $data[0]->numeroDocumento)->where('acceso_formato', true)->first();
      $accesoFormato->acceso_formato = false;
      $accesoFormato->save();
      DB::commit();
      return response()->json(['code' => 200, 'message' => 'Guardado Satisfactoriamente']);
    } catch (\Throwable $th) {
      \Log::error('Error al guardar formato uno: ' . $th->getMessage());
      dd($th->getMessage());
      DB::rollback();
      return response()->json(['code' => 500, 'message' => $th->getMessage()]);
    }
  }
}
