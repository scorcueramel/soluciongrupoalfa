<?php

namespace App\Services;

use App\Models\AcercaPoligrafos;
use App\Models\ConsumosBebidasAlcoholicas;
use App\Models\ExperienciasLaborales;
use App\Models\FormacionesAcademicasPersonas;
use App\Models\MotivacionesPostulaciones;
use App\Models\Personas;
use App\Models\PersonasInformacionesFinancieras;
use App\Models\PersonasMargenLeyes;
use Illuminate\Http\JsonResponse;

class FormatoUnoService
{
  public static function createRegisterFormatoUno(array $data): JsonResponse
  {
    dd($data[0]);
    //Firma examinado Base64
    $cryptExtension = explode("/9j/", $data[0]->imagenFirma)[1];

    foreach ($data as $d) {
      //Datos de las personas
      $persona = Personas::create([
        'nacionalidad_id' => $d->nacionalidad["code"],
        'tipo_documento_id' => $d->tipoDocumento,
        'estado_civil_id' => $d->estadoCivil,
        'genero_id' => $d->genero,
        'distrito_id' => $d->distrito["code"],
        'tipo_vivienda_id' => $d->tipoVivienda,
        'nombres' => $d->nombres,
        'apellido_paterno' => $d->paterno,
        'apellido_materno' => $d->materno,
        'numero_documento' => $d->numeroDocumento,
        'fecha_nacimiento' => $d->fechaNacimiento,
        'lugar_nacimiento' => $d->lugarNacimiento,
        'dirección' => $d->direccion,
        'otro_tipo_vivienda' => $d->otroTipoVivienda,
        'telefono' => $d->telefono,
        'email' => $d->email,
        'brevete' => $d->brevete,
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
      if ($d->gradoInstruccionTres != 0 && !is_null($d->centroEstudioTres) && !is_null($d->especialidadesFacultadTres) && !is_null($d->inicioEstudiosTres)) {
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
        'entidad_bancaria_id' => $d->entidadBancaria["code"],
        'tiene_prestamo' => $d->tienesPrestamos,
        'monto_prestamo' => $d->montoDeuda,
        'cuota_mensual_prestamo' => $d->cuotaMensual,
        'otro_ingreso' => $d->otroIngreso,
        'monto_ingreso' => $d->preciseMonto,
        'origen_ingreso' => $d->origenOtroIngreso,
        'tiene_propiedades' => $d->tienePropiedades,
        'detalle_propiedades' => $d->detallePropiedades,
        'reportado_centrar_riesgos' => $d->reportadoEnCentralesDeRiesgo,
        'entidad_reportado' => $d->entidadDeuda["code"],
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
        'recibio_amonestaciones' => $d->,
        'explicacion' => $d->,
      ]);
      //end Experiencias labroales


      dd($persona);
    }


    /*
     * Models of tables from save format
     * Personas -> OK
     * Formaciones Academicas -> OK
     * Motivaciones Postulaciones ->OK
     * Consumo de Bebidas Alcoholicas ->OK
     * Personas Información Financiera ->OK
     * Personas Margen de la Ley ->OK
     * Acerca del Poligrafo ->OK
     * Experiencias Laborales
     * Parentescos Personas
     * Consentimientos Examenes
     * Solicitudes Datos Personales
     * Comision de Delitos
     * Implicaciones Drogas
     * */
  }
}
