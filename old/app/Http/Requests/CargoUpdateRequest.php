<?php

namespace App\Http\Requests;

use App\Data\CargoData;
use Illuminate\Foundation\Http\FormRequest;

class CargoUpdateRequest extends FormRequest
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
      'id' => 'required|exists:cargos,id',
      'cargo' => 'required|string|max:50',
    ];
  }

  public function messages(): array
  {
    return [
      'id.required' => 'El campo id es obligatorio y/o no puede ser modificado.',
      'id.exists' => 'El campo id no existe.',
      'cargo.required' => 'El campo nombre cargo es obligatorio',
      'cargo.max' => 'El campo nombre cargo no puede superar los 50 caracteres',
      'cargo.string' => 'El campo nombre cargo no tiene un formato correcto',
    ];
  }

  public function passedValidation()
  {
    $this->data[] = CargoData::from([
      'id' => $this->id,
      'cargo' => $this->cargo,
    ]);
  }
}
