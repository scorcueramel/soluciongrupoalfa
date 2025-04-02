<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ValidarAccesoFormatosController extends Controller
{
    public function index()
    {
        return Inertia::render('Formularios/Access');
    }

    public function validarAcceso(Request $request)
    {
        $validado = false;

        if ($validado) {
            return Inertia::render('Formularios/FormatoUno');
        }

        return back()->with('error', 'No tienes permitido acceder al formato.');
    }
}
