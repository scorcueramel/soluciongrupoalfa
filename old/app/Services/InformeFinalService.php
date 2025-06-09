<?php

namespace App\Services;

use App\Models\InformesFinales;
use App\Models\Personas;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class InformeFinalService
{

  public static function store(array $data): JsonResponse
  {
    DB::beginTransaction();
    try {
      DB::commit();

      $jsonEnconde = json_encode($data[0]->preguntas_relevantes);

      InformesFinales::create([
        'usuario_id' => $data[0]->usuario_id,
        'persona_id' => $data[0]->persona_id,
        'drogas_ilegales' => $data[0]->drogas_ilegales,
        'antecedentes' => $data[0]->antecedentes,
        'vinculos' => $data[0]->vinculos,
        'planes_infiltracion' => $data[0]->planes_infiltracion,
        'proyeccion_tiempo_empresa' => $data[0]->proyeccion_tiempo_empresa,
        'preguntas_relevantes' => $jsonEnconde,
        'conclusion' => $data[0]->conclusion,
        'porcentaje_evaluacion' => $data[0]->porcentaje_evaluacion,
      ]);

      Personas::find($data[0]->persona_id)->update([
        'informe_final' => true
      ]);

      return response()->json(['code' => 200, 'message' => 'Guardado Satisfactoriamente']);
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json(['code' => 500, 'message' => $th->getMessage()]);
    }
  }
}
