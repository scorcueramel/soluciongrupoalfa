<?php

namespace App\Services;

use App\Models\Cargos;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CargoService{

  public static function store(array $data): JsonResponse
  {
    DB::beginTransaction();
    try {
      DB::commit();
      $ultimoCodigo = Cargos::orderBy('id','desc')->first();
      $codigo = Str::of($ultimoCodigo->codigo)->explode('-')[0];
      $numeracion = Str::of($ultimoCodigo->codigo)->explode('-')[1];

      Cargos::create([
        'cargo' => $data[0]->cargo,
        'codigo' => $codigo."-".((int)$numeracion+1),
      ]);

      return response()->json(['code' => 200, 'message' => 'Creado Satisfactoriamente']);
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json(['code' => 500, 'message' => $th->getMessage()]);
    }
  }

  public static function update(array $data):JsonResponse{
    DB::beginTransaction();
    try {
      DB::commit();
      Cargos::find($data[0]->id)->update([
        'cargo' => $data[0]->cargo,
      ]);
      return response()->json(['code' => 200, 'message' => 'El cargo se ha actualizado correctamente']);
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json(['code' => 500, 'message' => $th->getMessage()]);
    }
  }

  public static function destroy(Cargos $cargo): JsonResponse
  {
    DB::beginTransaction();
    try {
      DB::commit();
      $cargo->delete();
      return response()->json(['code' => 200, 'message' => 'El cargo '.$cargo->cargo.' se ha eliminado correctamente']);
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json(['code' => 500, 'message' => $th->getMessage()]);
    }
  }
}
