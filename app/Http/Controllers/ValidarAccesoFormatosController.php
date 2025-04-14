<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidarAccesoFormatosRequest;
use App\Models\AccesoFormatos;

use App\Models\Distritos;
use App\Models\Empresas;
use App\Models\EntidadesBancarias;
use App\Models\GradosInstrucciones;
use App\Models\TiposParentescos;
use App\Models\TiposViviendas;
use Inertia\Inertia;

class ValidarAccesoFormatosController extends Controller
{
  public function index()
  {
    return Inertia::render('Formatos/Access');
  }

//  public function test()
//  {
//    return Inertia::render('Formatos/FormatoUno', [
//      'distritos' => Distritos::all(),
//      'tiposviviendas' => TiposViviendas::all(),
//      'tiposparentescos' => TiposParentescos::all(),
//      'gradosinstrucciones' => GradosInstrucciones::all(),
//      'entidadesbancarias' => EntidadesBancarias::all()
//    ]);
//  }

    public function show(ValidarAccesoFormatosRequest $request)
    {
        $accesoFormato = AccesoFormatos::where('documento_persona', $request->documento)->first();

        if (!is_null($accesoFormato)) {
            if ($accesoFormato->acceso_formato) {
              return Inertia::render('Formatos/FormatoUno', [
                'razonessociales' => Empresas::all(),
                'distritos' => Distritos::all(),
                'tiposviviendas' => TiposViviendas::all(),
                'tiposparentescos' => TiposParentescos::all(),
                'gradosinstrucciones' => GradosInstrucciones::all(),
                'entidadesbancarias' => EntidadesBancarias::all()
              ]);
            }
        }
        return Inertia::render('Formatos/Access', ['openError' => true, 'errorMessage' => 'Verifica el documento ingresado, de lo contratio comunicate con un asesor', '']);
    }
}
