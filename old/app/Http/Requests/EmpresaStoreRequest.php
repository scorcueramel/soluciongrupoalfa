<?php

namespace App\Http\Requests;

use App\Data\EmpresaData;
use Illuminate\Foundation\Http\FormRequest;

class EmpresaStoreRequest extends FormRequest
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
      'nombre_comercial' => 'required|string|max:50',
      'razon_social' => 'required|string|max:50',
    ];
  }

  public function messages(): array
  {
    return [
      'nombre_comercial.required' => 'El campo nombre comercial es obligatorio',
      'nombre_comercial.max' => 'El campo nombre comercial no puede superar los 50 caracteres',
      'nombre_comercial.string' => 'El campo nombre comercial no tiene un formato correcto',
      'razon_social.required' => 'El campo razon social  es obligatorio',
      'razon_social.max' => 'El campo razon social debe tener 50 caracteres',
      'razon_social.string' => 'El campo razon social debe ser texto',
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
