<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Informe Personal</title>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      line-height: 1.5;
    }
    h1, h2, h3 {
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }
    th, td {
      border: 1px solid #000;
      padding: 4px;
      text-align: left;
    }
    ul {
      padding-left: 20px;
    }
  </style>
</head>
<body>
<h1>Informe Personal</h1>

<h2>Datos Personales</h2>
<p><strong>Nombre:</strong> {{ $persona->nombres }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</p>
<p><strong>Fecha de Nacimiento:</strong> {{ $diaNac }} de {{ $mesNac }} de {{ $anioNac }}</p>
<p><strong>Documento:</strong> {{ $persona->tipo_documento }} {{ $persona->numero_documento }}</p>
<p><strong>Estado Civil:</strong> {{ $persona->estado_civil }}</p>
<p><strong>Distrito:</strong> {{ $distrito->nombre ?? 'No disponible' }}</p>

<h2>Información Financiera</h2>
<p>{{ $reportaInfocorp }}</p>
<p>{{ $propiedades }}</p>
<p><strong>Tiene préstamo:</strong> {{ $infoFinancieras->tiene_prestamo ? 'Sí' : 'No' }}</p>
@if($infoFinancieras->tiene_prestamo)
  <p><strong>Entidad:</strong> {{ $infoFinancieras->entidad_prestamo }}</p>
  <p><strong>Monto:</strong> S/ {{ $infoFinancieras->monto_prestamo }}</p>
  <p><strong>Cuota mensual:</strong> S/ {{ $infoFinancieras->cuota_mensual_prestamo }}</p>
@endif

@if($infoFinancieras->otro_ingreso)
  <p><strong>Otros Ingresos:</strong> S/ {{ $infoFinancieras->monto_ingreso }} ({{ $infoFinancieras->origen_ingreso }})</p>
@endif

@if($infoFinancieras->reportado_centrar_riesgos)
  <p><strong>Entidad que reportó:</strong> {{ $infoFinancieras->entidad_reporto }}</p>
  <p><strong>Motivo:</strong> {{ $infoFinancieras->motivo_reportado }}</p>
  <p><strong>Tiempo de mora:</strong> {{ $infoFinancieras->tiempo_mora }}</p>
  <p><strong>Monto de deuda:</strong> S/ {{ $infoFinancieras->monto_deuda }}</p>
@endif

<h2>Formación Académica</h2>
<table>
  <thead>
  <tr>
    <th>Grado</th>
    <th>Centro</th>
    <th>Especialidad</th>
    <th>Inicio</th>
    <th>Término</th>
    <th>Situación</th>
  </tr>
  </thead>
  <tbody>
  @foreach($formAcademicas as $form)
    <tr>
      <td>{{ $form->grado_instruccion }}</td>
      <td>{{ $form->centro_estudio }}</td>
      <td>{{ $form->especialidad_facultad }}</td>
      <td>{{ $form->fecha_inicio }}</td>
      <td>{{ $form->fecha_termino }}</td>
      <td>{{ $form->situacion }}</td>
    </tr>
  @endforeach
  </tbody>
</table>

<h2>Experiencias Laborales</h2>
<ul>
  @foreach($expLaborales as $exp)
    <li><strong>{{ $exp->empresa }}</strong>: {{ $exp->cargo }} ({{ $exp->fecha_inicio }} - {{ $exp->fecha_termino }})</li>
  @endforeach
</ul>

<h2>Parentescos</h2>
<table>
  <thead>
  <tr>
    <th>Parentesco</th>
    <th>Nombre</th>
    <th>Edad</th>
    <th>Ocupación</th>
    <th>Vive en mismo inmueble</th>
  </tr>
  </thead>
  <tbody>
  @foreach($parentescos as $par)
    <tr>
      <td>{{ $par->tipo_parentesco }}</td>
      <td>{{ $par->nombres_apellidos }}</td>
      <td>{{ $par->edad }}</td>
      <td>{{ $par->ocupacion }}</td>
      <td>{{ $par->mismo_inmueble ? 'Sí' : 'No' }}</td>
    </tr>
  @endforeach
  </tbody>
</table>

<h2>Consumo de Alcohol</h2>
<p><strong>Frecuencia:</strong> {{ $consumoBebidas->frecuencia ?? 'No especificado' }}</p>

</body>
</html>
