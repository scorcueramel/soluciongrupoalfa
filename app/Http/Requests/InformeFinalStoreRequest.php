<?php

namespace App\Http\Requests;

use App\Data\InformeFinalData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class InformeFinalStoreRequest extends FormRequest
{
  public array $data = [];

  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      "personaId" =>'required|string',
      "drogasIlegales" => 'required|string',
      "antecedentes" => 'required|string',
      "vinculos" => 'required|string',
      "planesInfiltracion" => 'required|string',
      "proyeccion" => 'required|string',
      "conclusion" => 'required|string',
      "porcentajeEvaluacion" => 'required|string',
    ];
  }

  public function messages(): array
  {
    return [
      'drogasIlegales.required' => 'El campo drogas ilegales es obligatorio',
      'drogasIlegales.string' => 'El campo drogas ilegales no tiene un formato correcto',
      'antecedentes.required' => 'El campo antecedentes  es obligatorio',
      'antecedentes.string' => 'El campo antecedentes debe ser texto',
      'vinculos.required' => 'El campo vinculos  es obligatorio',
      'vinculos.string' => 'El campo vinculos debe ser texto',
      'planesInfiltracion.required' => 'El campo Planes de Infiltración  es obligatorio',
      'planesInfiltracion.string' => 'El campo Planes de Infiltración debe ser texto',
      'proyeccion.required' => 'El campo proyeccion  es obligatorio',
      'proyeccion.string' => 'El campo proyeccion debe ser texto',
      'conclusion.required' => 'El campo conclusion  es obligatorio',
      'conclusion.string' => 'El campo conclusion debe ser texto',
      'porcentajeEvaluacion.required' => 'El campo porcentaje de evaluación  es obligatorio',
      'porcentajeEvaluacion.string' => 'El campo porcentaje de evaluación debe ser texto',
    ];
  }

  public function passedValidation()
  {
    $this->data[] = InformeFinalData::from([
      "persona_id" => $this->personaId,
      "usuario_id" => Auth::id(),
      "drogas_ilegales" => $this->drogasIlegales,
      "antecedentes" => $this->antecedentes,
      "vinculos" => $this->vinculos,
      "planes_infiltracion" => $this->planesInfiltracion,
      "proyeccion_tiempo_empresa" => $this->proyeccion,
      "preguntas_relevantes" => $this->preguntasRelevantes,
      "conclusion" => $this->conclusion,
      "porcentaje_evaluacion" => $this->porcentajeEvaluacion,
    ]);
  }

}
