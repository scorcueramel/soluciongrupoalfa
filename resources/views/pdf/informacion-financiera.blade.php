@if($tienePrestamo)
  <p>Indicó que tiene un préstamo de S/ {{ $montoPrestamo }}.00
    con {{ $entidadPrestamo }} a cancelar en cuotas de
    S/ {{ $cuotaMensualPrestamo }}.00. {{ $reportaInfocorp }}. Señaló no tener tarjetas de crédito.
    Finalmente, {{ $propiedades }}</p>
@else
  <p>Indicó que no tiene préstamos. {{ $reportaInfocorp }}. Señaló no tener tarjetas de crédito.
    Finalmente, {{ $propiedades }}</p>
@endif
