<?php

namespace App\Services;

use App\Models\ParentescosPersonas;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class EvaluadosService{

  //pendiente pasar el index por aquí

  public static function obtenerParentescos(string $personaId):JsonResponse{
    DB::beginTransaction();
    try {
      DB::commit();
      $parentescos = ParentescosPersonas::where('persona_id', $personaId)->get();
      return response()->json(['code' => 200, 'parentescos' => $parentescos]);
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json(['code' => 500, 'message' => $th->getMessage()]);
    }
  }
}
