<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormatoCreateRequest;
use App\Http\Requests\ValidarAccesoFormatosRequest;
use App\Models\AccesoFormatos;
use App\Models\Cargos;
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

use App\Services\FormatoUnoService;
use Inertia\Inertia;

class FormatosController extends Controller
{
  protected $codecrypt = "data:image/jpeg;base64,/9j/";

  public function __construct(
    private FormatoUnoService $formatoUnoService,
  )
  {
  }

  public function index(ValidarAccesoFormatosRequest $request)
  {
    $accesoFormato = AccesoFormatos::where('documento_persona', $request->documento)->first();

    if (!is_null($accesoFormato)) {
      if ($accesoFormato->acceso_formato) {
        return Inertia::render('Formatos/FormatoUno', [
          'cargos' => Cargos::all(),
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
          'datosevaluado' => $accesoFormato,
        ]);
      }
    }

    return Inertia::render('Formatos/AccessFormat', ['openError' => true, 'errorMessage' => 'Verifica el documento ingresado, de lo contratio comunicate con un asesor', '']);
  }

  public function store(FormatoCreateRequest $request)
  {
      $this->formatoUnoService->createRegisterFormatoUno($request->data);
  }
}
