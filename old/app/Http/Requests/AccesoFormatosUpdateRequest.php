<?php

namespace App\Http\Requests;

use App\Data\AccesoFormatoData;
use Illuminate\Foundation\Http\FormRequest;

class AccesoFormatosUpdateRequest extends FormRequest
{
  public array $data;

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
      'numero_documento' => 'required|string',
      'acceder_formato' => 'required|boolean',
    ];
  }

  public function messages(): array
  {
    return [
      'id.required' => 'El campo id es obligatorio y no puede ser modificado.',
      'numero_documento.required' => 'El campo número de documento es obligatorio',
      'acceder_formato.required' => 'El campo acceder a formato es obligatorio',
    ];
  }

  public function passedValidation()
  {
    $this->data[] = AccesoFormatoData::from([
      'id' => $this->id,
      'numero_documento' => $this->numero_documento,
      'acceder_formato' => $this->acceder_formato
    ]);
  }
}
