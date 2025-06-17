<table>
  <tr>
    <th>Parentesco</th>
    <th>Nombres y Apellidos</th>
    <th>Edad</th>
    <th>Ocupación</th>
    <th>Mismo inmueble</th>
  </tr>
  @foreach($parentescos as $parentesco)
    <tr>
      <td>{{ $parentesco->tipo_parentesco }}</td>
      <td>{{ $parentesco->nombres_apellidos }}</td>
      <td>{{ $parentesco->edad }}</td>
      <td>{{ $parentesco->ocupacion }}</td>
      <td>{{ $parentesco->mismo_inmueble ? 'SI' : 'NO' }}</td>
    </tr>
  @endforeach
</table>
