<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Informe Final de Evaluación</title>
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    .header { text-align: center; margin-bottom: 20px; }
    .foto { float: right; width: 150px; height: 150px; margin-left: 20px; }
    .clear { clear: both; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    table, th, td { border: 1px solid #ddd; }
    th, td { padding: 8px; text-align: left; }
    .section-title { background-color: #f2f2f2; font-weight: bold; padding: 8px; margin-top: 20px; }
  </style>
</head>
<body>

@include('pdf.encabezado',[$codigoPoli, $fechaSolicitud])

<!-- Foto y datos básicos -->
@if(!is_null($persona->foto))
@if(file_exists($fotoPath))
@include('pdf.foto-evaluado',[$fotoPath])
@endif
@endif

@include('pdf.datos-empresa',['nombres'=>"$persona->nombres $persona->apellido_paterno $persona->apellido_materno",'porcentaje'=>$informeFinal->porcentaje_evaluacion,'tipoDocumento' => $persona->tipo_documento,'numeroDocumento' => $persona->numero_documento,'cargo' => $solDatPers->cargo,'empresa'=>$solDatPers->razon_social])

<!-- Datos personales -->
<div class="section-title">DATOS PERSONALES</div>
@include('pdf.datos-personales',['dia'=>$fechaNacimiento['dia'],'mes'=>$fechaNacimiento['mes'],'anio'=>$fechaNacimiento['anio'],'estadoCivil' => $persona->estado_civil,'direccion' => $persona->direccion,'distrito' => $distrito->distrito,'provincia' => $provincia->ciudad,'departamento' => $provincia->ciudad,'tiposVivienda' => $tiposVivienda->tipo_vivienda,'telefono'=>$persona->telefono])

<!-- Familiares -->
<div class="section-title">FAMILIARES</div>
@include('pdf.familiares',[$parentescos])

<!-- Formación académica -->
<div class="section-title">FORMACIÓN ACADÉMICA</div>
@include('pdf.formacion-academica',[$formAcademicas])

<!-- Información financiera -->
<div class="section-title">INFORMACIÓN FINANCIERA</div>
@include('pdf.informacion-financiera',['tienePrestamo' => $infoFinancieras->tiene_prestamo,'montoPrestamo' => $infoFinancieras->monto_prestamo,'entidadPrestamo' => $infoFinancieras->entidad_prestamo,'cuotaMensualPrestamo' => $infoFinancieras->cuota_mensual_prestamo,'reportaInfocorp' => $reportaInfocorp,'propiedades' => $propiedades])

<!-- Consumo de alcohol -->
<div class="section-title">CONSUMO DE BEBIDAS ALCOHÓLICAS</div>
@include('pdf.consumo-bebidas-alcholicas',['consumoBebidasAlcoholicas' => $consumoBebidas->frecuencia_consumo])

<!-- Experiencia laboral -->
<div class="section-title">EXPERIENCIA LABORAL</div>
@include('pdf.experiencia-labores',['expLaborales' => $expLaborales])

<!-- Información adicional -->
<div class="section-title">INFORMACIÓN ADICIONAL</div>
@include('pdf.informacion-adicional',['drogasIlegales' => $informeFinal->drogas_ilegales,'antecedentes' => $informeFinal->antecedentes,'vinculos' => $informeFinal->vinculos,'planeInfiltracion' => $informeFinal->planes_infiltracion,'proyeccionTiempoEmpresa' => $informeFinal->proyeccion_tiempo_empresa])

<!-- Preguntas relevantes -->
@if(!is_null($informeFinal->preguntas_relevantes))
  <div class="section-title">PREGUNTAS RELEVANTES</div>
  @php $preguntas = json_decode($informeFinal->preguntas_relevantes); @endphp
  @include('pdf.preguntas-relevantes',['preguntas'=>$preguntas])
@endif

<!-- Conclusión final -->
<div class="section-title">CONCLUSIÓN FINAL</div>
<p>{{ $informeFinal->conclusion }}</p>
</body>
</html>
