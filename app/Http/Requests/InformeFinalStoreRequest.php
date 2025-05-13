<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InformeFinalStoreRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return false;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      "drogasIlegales" => 'required|string|max:650',
      "antecedentes" => 'required|string|max:650',
      "vinculos" => 'required|string|max:650',
      "planesInfiltracion" => 'required|string|max:650',
      "proyeccion" => 'required|string|max:650',
      "conclusion" => 'required|string|max:650',
    ];
  }

  public function messages(): array
  {
    return [
      'drogasIlegales.required' => 'El campo drogas ilegales es obligatorio',
      'drogasIlegales.string' => 'El campo drogas ilegales no tiene un formato correcto',
      'drogasIlegales.max' => 'El campo drogas ilegales no puede superar los 50 caracteres',
      'antecedentes.required' => 'El campo antecedentes  es obligatorio',
      'antecedentes.max' => 'El campo antecedentes debe tener 50 caracteres',
      'antecedentes.string' => 'El campo antecedentes debe ser texto',
      'vinculos.required' => 'El campo vinculos  es obligatorio',
      'vinculos.max' => 'El campo vinculos debe tener 50 caracteres',
      'vinculos.string' => 'El campo vinculos debe ser texto',
      'planesInfiltracion.required' => 'El campo Planes de Infiltración  es obligatorio',
      'planesInfiltracion.max' => 'El campo Planes de Infiltración debe tener 50 caracteres',
      'planesInfiltracion.string' => 'El campo Planes de Infiltración debe ser texto',
      'proyeccion.required' => 'El campo proyeccion  es obligatorio',
      'proyeccion.max' => 'El campo proyeccion debe tener 50 caracteres',
      'proyeccion.string' => 'El campo proyeccion debe ser texto',
      'conclusion.required' => 'El campo conclusion  es obligatorio',
      'conclusion.max' => 'El campo conclusion debe tener 50 caracteres',
      'conclusion.string' => 'El campo conclusion debe ser texto',
    ];
  }

  public function passedValidation()
  {
    $this->data[] = EmpresaData::from([
      'nombreComercial' => $this->nombre_comercial,
      'razonSocial' => $this->razon_social,
    ]);
  }
}
