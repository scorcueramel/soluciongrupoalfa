<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidarAccesoFormatosRequest;
use App\Models\AccesoFormatos;

use Inertia\Inertia;

class ValidarAccesoFormatosController extends Controller
{
    public function index()
    {
        return Inertia::render('Formularios/Access');
    }

    public function test()
    {
        return Inertia::render('Formularios/FormatoUno');
    }

//    public function show(ValidarAccesoFormatosRequest $request)
//    {
//        $accesoFormato = AccesoFormatos::where('documento_persona', $request->documento)->first();
//
//        if (!is_null($accesoFormato)) {
//            if ($accesoFormato->acceso_formato) {
//                return Inertia::render('Formularios/FormatoUno', ['success' => 'Bienvenido, puedes continuar con la evaluación.']);
//            }
//        }
//        return Inertia::render('Formularios/Access', ['openError' => true, 'errorMessage' => 'Verifica el documento ingresado, de lo contratio comunicate con un asesor', '']);
//    }
}
