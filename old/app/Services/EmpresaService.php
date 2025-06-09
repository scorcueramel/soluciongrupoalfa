<?php

namespace App\Services;

use App\Models\Empresas;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmpresaService
{
  public static function store(array $data): JsonResponse
  {
    DB::beginTransaction();
    try {
      DB::commit();
      $ultimoCodigo = Empresas::orderBy('id','desc')->first();
      $codigo = Str::of($ultimoCodigo->codigo)->explode('-')[0];
      $numeracion = Str::of($ultimoCodigo->codigo)->explode('-')[1];

      Empresas::create([
        'nombre_comercial' => $data[0]->nombreComercial,
        'razon_social' => $data[0]->razonSocial,
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
      Empresas::find($data[0]->id)->update([
        'nombre_comercial' => $data[0]->nombreComercial,
        'razon_social' => $data[0]->razonSocial,
      ]);
      return response()->json(['code' => 200, 'message' => 'La empresa '.$data[0]->nombreComercial.' se ha actualizado correctamente']);
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json(['code' => 500, 'message' => $th->getMessage()]);
    }
  }

  public static function destroy(Empresas $empresa): JsonResponse
  {
    DB::beginTransaction();
    try {
      DB::commit();
      $empresa->delete();
      return response()->json(['code' => 200, 'message' => 'La empresa '.$empresa->nombre_comercial.' se ha eliminado correctamente']);
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json(['code' => 500, 'message' => $th->getMessage()]);
    }
  }

}
