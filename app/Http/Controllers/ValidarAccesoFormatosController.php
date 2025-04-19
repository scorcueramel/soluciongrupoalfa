<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccesoFormatosIndexRequest;
use App\Http\Requests\AccesoFormatosStoreRequest;
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
use App\Services\AccesoFormatosService;
use Inertia\Inertia;

class ValidarAccesoFormatosController extends Controller
{
  public function __construct(
    private AccesoFormatosService $accesoFormatosService
  )
  {
  }

  public function index()
  {
    return Inertia::render('Formatos/AccessFormat');
  }

  public function allowAccessToFormat(AccesoFormatosIndexRequest $request)
  {
    $evaluados = AccesoFormatos::query();

    if ($request->has('search')) {
      $evaluados->where('documento_formato', 'LIKE', '%' . $request->search . '%');
      $evaluados->orWhere('codigo_formato', 'LIKE', '%' . $request->search . '%');
      $evaluados->orWhere('fecha_examen', 'LIKE', '%' . $request->search . '%');
    }

    if ($request->has(['field', 'order'])) {
      $evaluados->orderBy($request->field, $request->order);
    }

    return Inertia::render('Formatos/AccesoEvaluados',[
      'evaluados'=>$evaluados->paginate(10),
      'filters' => $request->all(['search', 'field', 'order']),
    ]);
  }

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

    return Inertia::render('Formatos/AccessFormat', ['openError' => true, 'errorMessage' => 'Verifica el documento ingresado, de lo contratio comunicate con un asesor', '']);
  }

  public function createAllowAccessToFormat(AccesoFormatosStoreRequest $request)
  {
    $test = $this->accesoFormatosService->createAccessFormat($request->data);
    $jsonDecode = json_decode($test->content());

    if ($jsonDecode->code === 200) {
      return back()->with('success', $jsonDecode->message);
    }

    if ($jsonDecode->code === 500) {
      return back()->with('error', $jsonDecode->message);
    }
  }
}
