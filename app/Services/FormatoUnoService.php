<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class FormatoUnoService
{
  public static function createRegisterFormatoUno(array $data): JsonResponse
  {
    //Firma examinado
    $imagenBase64 = $data[0]->imagenFirma;
    $cryptExtension = explode("/9j/", $imagenBase64)[1];

    dd($cryptExtension);


    /*
     * Models of tables from save format
     * Personas
     * Formaciones Academicas
     * Motivaciones Postulaciones
     * Consumo de Bebidas Alcoholicas
     * Personas Información Financiera
     * Personas Margen de la Ley
     * Acerca del Poligrafo
     * Formaciones Academicas Personas
     * Experiencias Laborales
     * Parentescos Personas
     * Consentimientos Examenes
     * Solicitudes Datos Personales
     * Comision de Delitos
     * Implicaciones Drogas
     * */
  }
}
