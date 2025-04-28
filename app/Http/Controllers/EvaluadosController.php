<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluadosIndexRequest;
use App\Models\Personas;
use App\Models\SolicitudesDatosPersonales;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EvaluadosController extends Controller
{
    public function index(EvaluadosIndexRequest $request){

      $role = auth()->user()->roles->pluck('name')[0];

      $personas = Personas::query();

      if($role != 'superadmin'){
        $solDatPer = SolicitudesDatosPersonales::where('usuario_id',\Auth::id())->get();
        $personas->where('id',$solDatPer[0]->persona_id);
      }

      if ($request->has('search')) {
        $personas->where('numero_documento', 'LIKE', '%' . $request->search . '%');
      }

      if ($request->has(['field', 'order'])) {
        $personas->orderBy($request->field, $request->order);
      }

      return Inertia::render('Evaluado/Index',[
        'filters' => $request->all(['search', 'field', 'order']),
        'evaluados' => $personas->with('nacionalidad')->with('tipoDocumento')->with('estadoCivil')->with('genero')->with('distrito')->with('tipoVivienda')->paginate(10)
      ]);
    }
}
