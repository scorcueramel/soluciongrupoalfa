<?php

namespace App\Http\Requests;

use App\Data\EmpresaData;
use Illuminate\Foundation\Http\FormRequest;

class EmpresaUpdateRequest extends FormRequest
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
      'id' => 'required|exists:empresas,id',
      'nombre_comercial' => 'required|string|max:50',
      'razon_social' => 'required|string|max:50',
    ];
  }

  public function messages(): array
  {
    return [
      'id.required' => 'El campo id es obligatorio y/o no puede ser modificado.',
      'id.exists' => 'El campo id no existe.',
      'nombre_comercial.required' => 'El campo nombre comercial es obligatorio',
      'nombre_comercial.string' => 'El campo nombre comercial debe ser una cadena',
      'nombre_comercial.max' => 'El campo nombre comercial no puede superar los 50 caracteres',
      'razon_social.required' => 'El campo razón social es obligatoria',
      'razon_social.string' => 'El campo razón social debe ser una cadena',
      'razon_social.max' => 'El campo razón debe tener los 50 caracteres',
    ];
  }

  public function passedValidation()
  {
    $this->data[] = EmpresaData::from([
      'id' => $this->id,
      'nombreComercial' => $this->nombre_comercial,
      'razonSocial' => $this->razon_social,
    ]);
  }
}
