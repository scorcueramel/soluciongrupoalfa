export function validateForm(form, errors, errorsList) {
  if (form.empresa === "") {
    errors.value = true;
    errorsList.value.push("[Empresa] : No selecciono una empresa a la que postular");
  }
  if (form.cargo === "") {
    errors.value = true;
    errorsList.value.push("[Cargo] : No selecciono un cargo al que postular");
  }
  if (form.nombres === "") {
    errors.value = true;
    errorsList.value.push("[Nombres] : No introdujo sus nombres");
  }
  if (form.paterno === "") {
    errors.value = true;
    errorsList.value.push("[Apellido Paterno] : No introdujo su apellido paterno");
  }
  if (form.materno === "") {
    errors.value = true;
    errorsList.value.push("[Apellido Materno] : No introdujo su apellido materno");
  }
  if (form.nacionalidad === 0) {
    errors.value = true;
    errorsList.value.push("[Nacionalidad] : No selecciono una nacionalidad");
  }
  if (form.tipoDocumento === "") {
    errors.value = true;
    errorsList.value.push("[Tipo de Documento] : No selecciono un tipo de documento");
  }
  if (form.numeroDocumento === "") {
    errors.value = true;
    errorsList.value.push("[Número de Documento] : No ingresaste tu número de documento");
  }
  if (form.fechaNacimiento === "") {
    errors.value = true;
    errorsList.value.push("[Fecha de Nacimiento] : No ingresaste tu fecha de nacimiento");
  }
  if (form.lugarNacimiento === "") {
    errors.value = true;
    errorsList.value.push("[Lugar de Nacimiento] : No ingresaste tu lugar de nacimiento");
  }
  if (form.estadoCivil === "") {
    errors.value = true;
    errorsList.value.push("[Estado Civil] : No selecciono un estado civil");
  }
  if (form.genero === "") {
    errors.value = true;
    errorsList.value.push("[Genero] : No selecciono un genero");
  }
  if (form.distrito === "") {
    errors.value = true;
    errorsList.value.push("[Distrito] : No selecciono un distrito");
  }
  if (form.direccion === "") {
    errors.value = true;
    errorsList.value.push("[Dirección] : No ingresaste tu dirección");
  }
  if (form.tipoVivienda === "") {
    errors.value = true;
    errorsList.value.push("[Tipo de Vivienda] : No selecciono un tipo de vivienda");
  }
  if (form.telefono === "") {
    errors.value = true;
    errorsList.value.push("[Teléfono] : No ingresaste tu número de contacto");
  }
  if (form.email === "") {
    errors.value = true;
    errorsList.value.push("[Email] : No ingresaste tu email de contacto");
  }
  if (form.nombrespadre === "") {
    errors.value = true;
    errorsList.value.push("[Nombres y Apellidos Padre] : No ingresaste el nombre y apellido de tu padre");
  }
  if (form.edadpadre === 0) {
    errors.value = true;
    errorsList.value.push("[Edad Padre] : No ingresaste la edad de tu padre");
  }
  if (form.nombresocupacionpadre === "") {
    errors.value = true;
    errorsList.value.push("[Ocupación Padre] : No ingresaste la ocupación de tu padre");
  }
  if (form.nombresmadre === "") {
    errors.value = true;
    errorsList.value.push("[Nombres y Apellidos Madre] : No ingresaste el nombre y apellido de tu madre");
  }
  if (form.edadmadre === 0) {
    errors.value = true;
    errorsList.value.push("[Edad Madre] : No ingresaste la edad de tu madre");
  }
  if (form.nombresocupacionmadre === "") {
    errors.value = true;
    errorsList.value.push("[Ocupación Madre] : No ingresaste la ocupación de tu madre");
  }


    "tipoParentescoConyuge" => 'required|numeric',
    "nombresConyuge" => 'string|min:3|max:100',
    "edadConyuge" => ['numeric'],
    "nombreOcupacionConyuge" => 'string|min:3|max:150',
    "mismoInmuebleConyuge" => 'required|boolean',
    "tipoParentescoHijos" => ['numeric'],
    "nombresHijos" => ['array'],
    "edadesHijos" => ['array'],
    "nombreOcupacionesHijos" => ['array'],
    "mismoInmuebleHijos" => ['array'],
    "tipoParentescoHermanos" => ['numeric'],
    "nombresHermanos" => ['array'],
    "edadesHermanos" => ['array'],
    "nombreOcupacionesHermanos" => ['array'],
    "mismoInmuebleHermanos" => ['array'],
    "gradoInstruccionUno" => 'required|numeric',
    "centroEstudioUno" => 'required|string|min:3|max:100',
    "especialidadesFacultadUno" => 'required|string|min:3|max:150',
    "inicioEstudiosUno" => 'required|date',
    "terminoEstudiosUno" => ['date'],
    "situacionUno" => 'required|boolean',
    "gradoInstruccionDos" => 'required|numeric',
    "centroEstudioDos" => 'required|string|min:3|max:100',
    "especialidadesFacultadDos" => 'required|string|min:3|max:150',
    "inicioEstudiosDos" => 'required|date',
    "terminoEstudiosDos" => ['date'],
    "situacionDos" => 'required|boolean',
    "gradoInstruccionTres" => ['numeric'],
    "centroEstudioTres" => 'string|min:3|max:100',
    "especialidadesFacultadTres" => 'string|min:3|max:150',
    "inicioEstudiosTres" => ['date'],
    "terminoEstudiosTres" => ['date'],
    "situacionTres" => 'required|boolean',
    "empresaUno" => 'string|min:3|max:100',
    "fechaIngresoUno" => ['string'],
    "fechaSalidaUno" => ['string'],
    "sueldoUno" => ['numeric'],
    "cargoDesempenioUno" => 'string|min:3|max:150',
    "motivoSalidaUno" => 'string|min:3|max:150',
    "empresaDos" => 'string|min:3|max:150',
    "fechaIngresoDos" => ['string'],
    "fechaSalidaDos" => ['string'],
    "sueldoDos" => ['numeric'],
    "cargoDesempenioDos" => 'string|min:3|max:150',
    "motivoSalidaDos" => 'string|min:3|max:150',
    "empresaTres" => 'string|min:3|max:150',
    "fechaIngresoTres" => ['string'],
    "fechaSalidaTres" => ['string'],
    "sueldoTres" => ['numeric'],
    "cargoDesempenioTres" => 'string|min:3|max:150',
    "motivoSalidaTres" => 'string|min:3|max:150',
    "amonestacionesEmpleos" => 'required|boolean',
    "solicitudRenuncia" => 'required|boolean',
    "explicacion" => 'string|min:3|max:150',
    "tienesPrestamos" => 'required|boolean',
    "entidadBancaria" => ['numeric'],
    "montoDeuda" => ['numeric'],
    "otroIngreso" => 'required|boolean',
    "preciseMonto" => ['numeric'],
    "origenOtroIngreso" => ['string'],
    "tienePropiedades" => 'required|boolean',
    "detallePropiedades" => 'string|min:3|max:150',
    "reportadoEnCentralesDeRiesgo" => 'required|boolean',
    "preciseDeSerAfirmativo" => ['string'],
    "entidadDeuda" => ['numeric'],
    "motivoCentralDeRiesgo" => 'string|min:3|max:150',
    "tiempoMora" => 'string|min:3|max:150',
    "montoDeuraMora" => ['numeric'],
    "fecuenciaConsumoBebidasAlcoholicas" => 'string|min:3|max:300',
    "queBebidasConsume" => 'string|min:3|max:150',
    "tratamientoAlcholismo" => 'required|boolean',
    "trabajoEbrio" => 'required|boolean',
    "expliqueLlegoEbrio" => 'string|min:3|max:300',
    "marihuana" => 'required|boolean',
    "pbc" => 'required|boolean',
    "cocaina" => 'required|boolean',
    "heroina" => 'required|boolean',
    "lcd" => 'required|boolean',
    "extasis" => 'required|boolean',
    "tiempoUltimaVez" => ['string'],
    "cantidadUltimoConsumo" => ['numeric'],
    "familiaresEnDrogas" => 'required|boolean',
    "roboHurtoFraude" => 'required|boolean',
    "homicidioInvoluntario" => 'required|boolean',
    "asalto" => 'required|boolean',
    "planesDanioFisico" => 'required|boolean',
    "secuestro" => 'required|boolean',
    "violacion" => 'required|boolean',
    "muerteLesionPersona" => 'required|boolean',
    "traficoIlicitoDrogas" => 'required|boolean',
    "traficoArmas" => 'required|boolean',
    "castigadoConCarcel" => 'required|boolean',
    "explicacionCastigadoLey" => 'string|min:3|max:300',
    "sicarios" => 'required|boolean',
    "asaltantes" => 'required|boolean',
    "traficantesDrogas" => 'required|boolean',
    "estafadores" => 'required|boolean',
    "terroristas" => 'required|boolean',
    "secuestradores" => 'required|boolean',
    "extorsionadores" => 'required|boolean',
    "otrasPersonasMargenLey" => 'required|boolean',
    "familiaresSentenciadosPenales" => 'required|boolean',
    "planeaCausarDanioPersonaEmpresa" => 'required|boolean',
    "planeaObtenerBeneficioIlegal" => 'required|boolean',
    "familiaresMismaEmpresa" => 'required|boolean',
    "pasoAntesExamenPoligrafo" => 'required|boolean',
    "explicacionExamenAnterior" => 'string|min:3|max:300',
    "empresaPostuloAntes" => 'string|min:3|max:300',
    "fechaExamenAnterior" => 'string|min:3|max:300',
    "motivoPasoAntesE" => 'string|min:3|max:300',
  return (errors, errorsList);
}
