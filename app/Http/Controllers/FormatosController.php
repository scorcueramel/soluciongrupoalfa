<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormatoCreateRequest;
use App\Http\Requests\ValidarAccesoFormatosRequest;
use App\Models\AccesoFormatos;
use App\Models\AcercaPoligrafos;
use App\Models\Cargos;
use App\Models\ComisionesDelitos;
use App\Models\ConsentimientosExamenes;
use App\Models\ConsumosBebidasAlcoholicas;
use App\Models\DetalleExperenciaLaboral;
use App\Models\Distritos;
use App\Models\Empresas;
use App\Models\EntidadesBancarias;
use App\Models\EstadosCiviles;
use App\Models\ExperienciasLaborales;
use App\Models\FormacionesAcademicasPersonas;
use App\Models\Generos;
use App\Models\GradosInstrucciones;
use App\Models\ImplicacionesDrogas;
use App\Models\MotivacionesPostulaciones;
use App\Models\Nacionalidades;
use App\Models\ParentescosPersonas;
use App\Models\Personas;
use App\Models\PersonasInformacionesFinancieras;
use App\Models\PersonasMargenLeyes;
use App\Models\SolicitudesDatosPersonales;
use App\Models\TiposDocumentos;
use App\Models\TiposParentescos;
use App\Models\TiposViviendas;

use App\Services\FormatoUnoService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class FormatosController extends Controller
{
  public function __construct(
    private readonly FormatoUnoService $formatoUnoService,
  )
  {
  }

  public function index(ValidarAccesoFormatosRequest $request)
  {
    $accesoFormato = AccesoFormatos::where('documento_persona', $request->documento)->where('acceso_formato',true)->first();

    if (!is_null($accesoFormato)) {
      if ($accesoFormato->acceso_formato) {
        return Inertia::render('Formato/FormatoUno', [
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
          'numerodocumento' => $request->documento,
        ]);
      }
    }

    return Inertia::render('Formato/AccessFormat', ['openError' => true, 'errorMessage' => 'Verifica el documento ingresado, de lo contratio comunicate con un asesor', '']);
  }

  public function store(FormatoCreateRequest $request)
  {
    $response = $this->formatoUnoService->createRegisterFormatoUno($request->data);
    $jsonDecode = json_decode($response->content());

    if ($jsonDecode->code === 200) {
      return back()->with('success', $jsonDecode->message);
    }

    if ($jsonDecode->code === 500) {
      return back()->with('error', $jsonDecode->message);
    }
  }

  public function formatoDos(Request $request)
  {
    $requestatosEvaluado = [
      "nombres" => $request->nombres . " " . $request->paterno . " " . $request->materno,
      "documento" => $request->numeroDocumento,
      "edad" => $request->fechaNacimiento,
      "codigo_poligrafista" => $request->codigoPoligrafista,
    ];

    return Inertia::render('Formato/FormatoDos', [
      'datosEvaluados' => $requestatosEvaluado,
    ]);
  }

  public function formatoDosStore(Request $request)
  {
    $this->validate($request,[
      'imagenFirma' => 'required'
    ]);

    return Inertia::render('Formato/AccessFormat');
  }
}
