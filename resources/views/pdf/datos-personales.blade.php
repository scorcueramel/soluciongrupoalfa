<table>
  <tr>
    <td><strong>Fecha de nacimiento:</strong></td>
    <td>{{ $dia }} {{ $mes }} {{ $anio }}</td>
    <td><strong>Estado civil:</strong></td>
    <td>{{ $estadoCivil }}</td>
  </tr>
  <tr>
    <td><strong>Dirección:</strong></td>
    <td>{{ $direccion }}</td>
    <td><strong>Distrito:</strong></td>
    <td>{{ $distrito ?? 'N/A' }}</td>
  </tr>
{{--  <tr>--}}
{{--    <td><strong>Provincia:</strong></td>--}}
{{--    <td>{{ $provincia ?? 'N/A' }}</td>--}}
{{--    <td><strong>Departamento:</strong></td>--}}
{{--    <td>{{ $departamento ?? 'N/A' }}</td>--}}
{{--  </tr>--}}
  <tr>
    <td><strong>Tipo de inmueble:</strong></td>
    <td>{{ $tiposVivienda ?? 'N/A' }}</td>
    <td><strong>Celular:</strong></td>
    <td>{{ $telefono }}</td>
  </tr>
</table>
