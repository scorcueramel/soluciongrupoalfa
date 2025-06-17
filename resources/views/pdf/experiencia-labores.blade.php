<table>
  <tr>
    <th>Empresa</th>
    <th>F. Ingreso</th>
    <th>F. Salida</th>
    <th>Sueldo</th>
    <th>Cargo</th>
    <th>Motivo de salida</th>
  </tr>
  @foreach($expLaborales as $experiencia)
    <tr>
      <td>{{ $experiencia->empresa }}</td>
      <td>{{ $experiencia->fecha_ingreso }}</td>
      <td>{{ $experiencia->fecha_salida }}</td>
      <td>{{ $experiencia->sueldo_percibido }}</td>
      <td>{{ $experiencia->cargo_desempenado }}</td>
      <td>{{ $experiencia->motivo_salida }}</td>
    </tr>
  @endforeach
</table>
