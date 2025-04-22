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
  if (form.gradoInstruccionUno === 0) {
    errors.value = true;
    errorsList.value.push("[Grado de Instrucción 1] : No selecciono un grado de instrucción 1");
  }
  if (form.centroEstudioUno === "") {
    errors.value = true;
    errorsList.value.push("[Centro de Estudios 1] : No ingresaste tu centro de estudios 1");
  }
  if (form.especialidadesFacultadUno === "") {
    errors.value = true;
    errorsList.value.push("[Especialidad/Facultad 1] : No ingresaste Especialidad/Facultad 1");
  }
  if (form.inicioEstudiosUno === "") {
    errors.value = true;
    errorsList.value.push("[Inicio de Estudios 1] : No ingresaste el inicio de estudios 1");
  }
  if (form.gradoInstruccionDos === 0) {
    errors.value = true;
    errorsList.value.push("[Grado de Instrucción 2] : No selecciono un grado de instrucción 2");
  }
  if (form.centroEstudioDos === "") {
    errors.value = true;
    errorsList.value.push("[Centro de Estudios 2] : No ingresaste tu centro de estudios 2");
  }
  if (form.especialidadesFacultadDos === "") {
    errors.value = true;
    errorsList.value.push("[Especialidad/Facultad 2] : No ingresaste Especialidad/Facultad 2");
  }
  if (form.inicioEstudiosDos === "") {
    errors.value = true;
    errorsList.value.push("[Inicio de Estudios 2] : No ingresaste el inicio de estudios 2");
  }

  if (form.gradoInstruccionTres === 0) {
    errors.value = true;
    errorsList.value.push("[Grado de Instrucción 3] : No selecciono un grado de instrucción 3");
  }
  if (form.centroEstudioTres === "") {
    errors.value = true;
    errorsList.value.push("[Centro de Estudios 3] : No ingresaste tu centro de estudios 3");
  }
  if (form.especialidadesFacultadTres === "") {
    errors.value = true;
    errorsList.value.push("[Especialidad/Facultad 3] : No ingresaste Especialidad/Facultad 3");
  }
  if (form.inicioEstudiosTres === "") {
    errors.value = true;
    errorsList.value.push("[Inicio de Estudios 3] : No ingresaste el inicio de estudios 3");
  }

  return (errors, errorsList);
}
