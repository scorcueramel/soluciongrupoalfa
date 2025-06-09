<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
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
      'name' => ['required', 'string', 'max:255'],
      'tipo_documento' => ['required'],
      'apellido_paterno' => 'required|string|max:50',
      'apellido_materno' => 'required|string|max:50',
      'numero_documento' => 'required|string|max:20',
      'telefono' => 'required|max:20',
      'email' => 'required|unique:users,email,' . $this->user,
      'password' => ['nullable', 'confirmed', Password::defaults()],
      'password_confirmation' => 'sometimes|required_with:password|same:password',
      'role' => ['required'],
    ];
  }

  public function messages(): array {
    return [
      'name.required' => 'El nombre de la persona es obligatorio.',
      'name.string' => 'El nombre no puede contener números.',
      'name.max' => 'El nombre no puede superar los 50 caracteres.',
      'tipo_documento.required' => 'El tipo de documento es obligatorio.',
      'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
      'apellido_paterno.string' => 'El apellido paterno no puede contener números.',
      'apellido_materno.required' => 'El apellido materno es obligatorio.',
      'apellido_materno.string' => 'El apellido materno no puede contener números.',
      'telefono.required' => 'El telefono es obligatorio.',
      'telefono.max' => 'El telefono no puede superar los 20 caracteres.',
      'numero_documento.required' => 'El número del documento es obligatorio.',
      'email.required' => 'El correo es obligatorio.',
      'email.unique' => 'El correo ya se encuentra registrado.',
      'password.required' => 'La contraseña es obligatoria.',
      'password.confirmed' => 'Las contraseñas deben coincidir',
      'password_confirmation.required_with' => 'Para cambiar la contraseña debes confirmarla',
      'role.required' => 'Debes seleccionar un rol.',
    ];
  }
}
