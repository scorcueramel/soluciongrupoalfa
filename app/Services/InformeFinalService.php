<?php

namespace App\Services;

use App\Models\InformesFinales;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class InformeFinalService{

  public static function store(array $data):JsonResponse{
    DB::beginTransaction();
    try {
      DB::commit();

      InformesFinales::create([
        'usuario_id'=>$data[0]->usuario_id,
        'persona_id'=>$data[0]->persona_id,
        'drogas_ilegales'=>$data[0]->drogas_ilegales,
        'antecedentes'=>$data[0]->antecedentes,
        'vinculos'=>$data[0]->vinculos,
        'planes_infiltracion'=>$data[0]->planes_infiltracion,
        'proyeccion_tiempo_empresa'=>$data[0]->proyeccion_tiempo_empresa,
        'preguntas_relevantes'=>$data[0]->preguntas_relevantes,
        'conclusion'=>$data[0]->conclusion,
      ]);

      return response()->json(['code' => 200, 'message' => 'Guardado Satisfactoriamente']);
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json(['code' => 500, 'message' => $th->getMessage()]);
    }
  }
}
