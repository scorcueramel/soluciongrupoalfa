<?php

namespace App\Http\Requests;

use App\Data\CargoData;
use Illuminate\Foundation\Http\FormRequest;

class CargoStoreRequest extends FormRequest
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
        'cargo' => 'required|string|max:50',
      ];
    }
  public function messages(): array
  {
    return [
      'cargo.required' => 'El campo nombre cargo es obligatorio',
      'cargo.max' => 'El campo nombre cargo no puede superar los 50 caracteres',
      'cargo.string' => 'El campo nombre cargo no tiene un formato correcto',
    ];
  }

  public function passedValidation()
  {
    $this->data[] = CargoData::from([
      'cargo' => $this->cargo,
    ]);
  }
}
