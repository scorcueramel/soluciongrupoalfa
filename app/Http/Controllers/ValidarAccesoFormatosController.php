<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidarAccesoFormatosRequest;
use App\Models\AccesoFormatos;

use App\Models\Distritos;
use App\Models\Empresas;
use App\Models\EntidadesBancarias;
use App\Models\EstadosCiviles;
use App\Models\Generos;
use App\Models\GradosInstrucciones;
use App\Models\Nacionalidades;
use App\Models\TiposDocumentos;
use App\Models\TiposParentescos;
use App\Models\TiposViviendas;
use App\Services\testService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ValidarAccesoFormatosController extends Controller
{
  public function __construct(){

  }

  public function index()
  {
    return Inertia::render('Formatos/AccessFormat');
  }

  public function allowAccessToFormat(){
    return Inertia::render('Formatos/AccesoEvaluados');
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
          'distritos' => Distritos::all(),
          'entidadesbancarias' => EntidadesBancarias::all(),
          'estadosciviles' => EstadosCiviles::all(),
          'generos' => Generos::all(),
          'gradosinstrucciones' => GradosInstrucciones::all(),
          'nacionalidades' => Nacionalidades::all(),
          'razonessociales' => Empresas::all(),
          'tiposdocumentos' => TiposDocumentos::all(),
          'tiposparentescos' => TiposParentescos::all(),
          'tiposviviendas' => TiposViviendas::all(),
        ]);
      }
    }
    return Inertia::render('Formatos/Access', ['openError' => true, 'errorMessage' => 'Verifica el documento ingresado, de lo contratio comunicate con un asesor', '']);
  }

  public function createAllowAccessToFormat(Request $request){
    $userConteoEvaluaciones = \Auth::user()->conteo_evaluaciones;
    $userCode = \Auth::user()->codigo;
    if ($userConteoEvaluaciones < 10){
      dd("00".$userConteoEvaluaciones+=1);
    }
    if ($userConteoEvaluaciones >= 10 && $userConteoEvaluaciones <= 99){
      dd("0".$userConteoEvaluaciones+=1);
    }
    else {
      dd($userConteoEvaluaciones+=1);
    }
    //Aqui va toda la logica
  }
}
