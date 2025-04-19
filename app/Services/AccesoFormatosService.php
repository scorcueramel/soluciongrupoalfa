<?php

namespace App\Services;

use App\Models\AccesoFormatos;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AccesoFormatosService
{
  public static function createAccessFormat(array $data): JsonResponse
  {
    DB::beginTransaction();
    try {
      DB::commit();
      $conteEval = \Auth::user()->conteo_evaluaciones;
      $userCode = \Auth::user()->codigo_poligrafista;
      $counterIncrement = $conteEval += 1;
      $codeEnd = "";
      $evalDate = date("Y-m-d");

      if ($counterIncrement <= 9) {
        $codeEnd = "00" . $counterIncrement;
      } elseif ($counterIncrement > 9 && $counterIncrement <= 99) {
        $codeEnd = "0" . $counterIncrement;
      } elseif ($counterIncrement >= 100) {
        $codeEnd = "" . $counterIncrement;
      }

      foreach ($data as $d) {
        AccesoFormatos::create([
          'documento_persona' => $d->numero_documento,
          'acceso_formato' => $d->acceder_formato,
          'codigo_poligrafista' => $userCode,
          'numero_evaluaciones' => $codeEnd,
          'fecha_examen' => $evalDate,
        ]);
      }

      User::find(\Auth::user()->id)->update(['conteo_evaluaciones' => $counterIncrement]);

      return response()->json(['code' => 200, 'message' => 'Formulario Habilitado Satisfactoriamente']);
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json(['code' => 500, 'message' => $th->getMessage()]);
    }
  }

  public static function allowAndDisallowFormat(string $id): JsonResponse
  {
    DB::beginTransaction();
    try {
      DB::commit();
      $getEvaluated = AccesoFormatos::findOrFail($id);
      if ($getEvaluated->acceso_formato) {
        $getEvaluated->acceso_formato = false;
        $getEvaluated->save();
      } else {
        $getEvaluated->acceso_formato = true;
        $getEvaluated->save();
      }
      return response()->json(['code' => 200, 'message' => 'Se restringio el acceso al formato al evaluado']);
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json(['code' => 500, 'message' => $th->getMessage()]);
    }
  }
}
