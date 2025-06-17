<table>
  <tr>
    <th>Grado de instrucción</th>
    <th>Centro de estudios</th>
    <th>Facultad/Especialidad</th>
    <th>Inicio</th>
    <th>Término</th>
    <th>Situación</th>
  </tr>
  @foreach($formAcademicas as $academica)
    <tr>
      <td>{{ $academica->grado_instruccion }}</td>
      <td>{{ $academica->centro_estudio }}</td>
      <td>{{ $academica->especialidad_facultad }}</td>
      <td>{{ Carbon\Carbon::parse($academica->fecha_inicio)->format('d/m/Y') }}</td>
      <td>{{ Carbon\Carbon::parse($academica->fecha_termino)->format('d/m/Y') }}</td>
      <td>{{ $academica->situacion ? 'COMPLETO' : 'INCOMPLETO' }}</td>
    </tr>
  @endforeach
</table>
