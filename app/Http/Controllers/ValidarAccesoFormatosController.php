<?php

namespace App\Http\Controllers;

use App\Models\AccesoFormatos;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ValidarAccesoFormatosController extends Controller
{
    public function index()
    {
        return Inertia::render('Formularios/Access');
    }

    public function validarAcceso(Request $request)
    {

        DB::beginTransaction();
        try {
            $accesoFormato = AccesoFormatos::where('documento_persona', $request->documento)->first();
            if (!is_null($accesoFormato)) {
                if ($accesoFormato->acceso_formato) {
                    DB::commit();
                    return Inertia::render('Formularios/FormatoUno');
                }
            }else{
                DB::rollback();
                return Inertia::render('Formularios/NotAccess');
            }

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Error updating ' . $th->getMessage());
        }
    }
}
