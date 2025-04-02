<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidarAccesoFormatosRequest;
use App\Models\AccesoFormatos;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class ValidarAccesoFormatosController extends Controller
{
    public function index()
    {
        return Inertia::render('Formularios/Access');
    }

    public function show(ValidarAccesoFormatosRequest $request)
    {
        $accesoFormato = AccesoFormatos::where('documento_persona', $request->documento)->first();

        if (!is_null($accesoFormato)) {
            if ($accesoFormato->acceso_formato) {
                return Inertia::render('Formularios/FormatoUno');
            }
        }

        return redirect()->back()->with('error', 'No tienes permitido acceder al formato.');
    }
}
